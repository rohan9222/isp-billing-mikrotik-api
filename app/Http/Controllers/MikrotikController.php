<?php

namespace App\Http\Controllers;

use App\Models\RouterList;
use App\Models\NotificationLogs;
use App\Services\MikrotikSSHService;
use App\Services\MikrotikApiService;

class MikrotikController extends Controller
{
    protected $mikrotikSSHService;
    protected $mikrotikApiService;

    public function __construct(?MikrotikSSHService $mikrotikSSHService = null, ?MikrotikApiService $mikrotikApiService = null)
    {
        $this->mikrotikSSHService = $mikrotikSSHService;
        $this->mikrotikApiService = $mikrotikApiService;
    }

    // =========================================================================
    // CORE HELPERS
    // =========================================================================

    /**
     * Try API first (if api_port set), fall back to SSH.
     * Returns parsed array or error string.
     */
    public function checkConnection($ip_address, $ssh_port, $api_port, $username, $password, $api_command = null, $ssh_command = null)
    {
        if ($api_port) {
            try {
                $mikrotikApiService = new MikrotikApiService($ip_address, $api_port, $username, $password);
                $response = $mikrotikApiService->executeCommand($api_command);
                if (is_string($response) && str_starts_with($response, 'Error:')) {
                    throw new \Exception($response);
                }
                return $response;
            } catch (\Exception $e) {
                if ($ssh_port) {
                    try {
                        $mikrotikSSHService = new MikrotikSSHService($ip_address, $ssh_port, $username, $password);
                        return $mikrotikSSHService->executeCommandParsable($ssh_command);
                    } catch (\Exception $sshEx) {
                        return 'Both API and SSH failed: ' . $sshEx->getMessage();
                    }
                }
                return 'API failed and no SSH port provided: ' . $e->getMessage();
            }
        }

        if ($ssh_port) {
            try {
                $mikrotikSSHService = new MikrotikSSHService($ip_address, $ssh_port, $username, $password);
                return $mikrotikSSHService->executeCommandParsable($ssh_command);
            } catch (\Exception $sshEx) {
                return 'SSH failed: ' . $sshEx->getMessage();
            }
        }

        return 'No API or SSH port provided';
    }

    /**
     * Run a READ command on one or all connected routers.
     * Returns ['router_name' => array|string, ...]
     */
    public function routerList($routerIdentifier = null, $api_command = null, $ssh_command = null)
    {
        $query = RouterList::where('action', 'connected');

        if ($routerIdentifier) {
            if (is_numeric($routerIdentifier)) {
                $query->where('id', $routerIdentifier);
            } else {
                $query->where('router_name', $routerIdentifier);
            }
        }

        $routers = $query->get();
        $results = [];
        foreach ($routers as $router) {
            try {
                $results[$router->router_name] = $this->checkConnection(
                    $router->ip_address,
                    $router->ssh_port,
                    $router->api_port,
                    $router->username,
                    $router->password,
                    $api_command,
                    $ssh_command
                );
            } catch (\Exception $e) {
                $results[$router->router_name] = 'Error: ' . $e->getMessage();
            }
        }
        return $results;
    }

    /**
     * Run a READ on a SINGLE router by name. Returns flat array of items.
     * @throws \Exception on connection error
     */
    protected function singleRead(string $routerName, string $apiCmd, string $sshCmd): array
    {
        $results = $this->routerList($routerName, $apiCmd, $sshCmd);
        $data    = $results[$routerName] ?? [];
        if (is_string($data)) {
            throw new \Exception($data);
        }
        return is_array($data) ? $data : [];
    }

    /**
     * Run a WRITE command on a single router via SSH (add/set/remove).
     * @throws \Exception if router not found or SSH fails
     */
    protected function singleWrite(string $routerName, string $command): string
    {
        $router = RouterList::where('router_name', $routerName)
            ->where('action', 'connected')
            ->first();

        if (! $router) {
            throw new \Exception("Router '{$routerName}' not found or not connected.");
        }

        if (! $router->ssh_port) {
            throw new \Exception("Router '{$routerName}' has no SSH port configured.");
        }

        $ssh = new MikrotikSSHService(
            $router->ip_address,
            $router->ssh_port,
            $router->username,
            $router->password
        );

        return $ssh->executeCommand($command) ?? '';
    }

    // =========================================================================
    // SYSTEM & CONTROLS
    // =========================================================================

    public function moveItem(string $routerName, string $path, string $id, ?string $destinationId = null): string
    {
        $cmd = "{$path} move numbers={$id}";
        if ($destinationId) {
            $cmd .= " destination={$destinationId}";
        }
        return $this->singleWrite($routerName, $cmd);
    }

    public function systemOverview()
    {
        return $this->routerList(null, '/system/resource/print', '/system resource print');
    }

    // =========================================================================
    // PPP SECRETS (existing methods kept intact)
    // =========================================================================

    public function enablePPPSecret($customerID, $router_name, $PPPSecretPPPSecret)
    {
        $router = RouterList::where('router_name', $router_name)->first();
        if ($router) {
            try {
                $mikrotikSSHService = new MikrotikSSHService($router->ip_address, $router->ssh_port, $router->username, $router->password);
                return $mikrotikSSHService->executeCommand('/ppp secret enable ' . $PPPSecretPPPSecret);
            } catch (\Exception $e) {
                NotificationLogs::create(['title' => 'Enable User', 'message' => $customerID . ' (' . $PPPSecretPPPSecret . ')' . $e->getMessage(), 'status' => 'Error on Mikrotik Command', 'type' => 'Mikrotik Command']);
            }
        } else {
            return 'Router not found';
        }
    }

    public function disablePPPSecret($customerID, $router_name, $PPPSecretPPPSecret)
    {
        $router = RouterList::where('router_name', $router_name)->first();
        if ($router) {
            try {
                $mikrotikSSHService = new MikrotikSSHService($router->ip_address, $router->ssh_port, $router->username, $router->password);
                return $mikrotikSSHService->executeCommand('/ppp secret disable ' . $PPPSecretPPPSecret);
            } catch (\Exception $e) {
                NotificationLogs::create(['title' => 'Disable User', 'message' => $customerID . ' (' . $PPPSecretPPPSecret . ')' . $e->getMessage(), 'status' => 'Error on Mikrotik Command', 'type' => 'Mikrotik Command']);
            }
        } else {
            return 'Router not found';
        }
    }

    public function removePPPSecret($customerID, $router_name, $PPPSecretPPPSecret)
    {
        $router = RouterList::where('router_name', $router_name)->first();
        if ($router) {
            try {
                $mikrotikSSHService = new MikrotikSSHService($router->ip_address, $router->ssh_port, $router->username, $router->password);
                return $mikrotikSSHService->executeCommand('/ppp secret remove ' . $PPPSecretPPPSecret);
            } catch (\Exception $e) {
                NotificationLogs::create(['title' => 'Remove User', 'message' => $customerID . ' (' . $PPPSecretPPPSecret . ')' . $e->getMessage(), 'status' => 'Error on Mikrotik Command', 'type' => 'Mikrotik Command']);
            }
        } else {
            return 'Router not found';
        }
    }

    public function updatePPPSecret($router_name, $PPPSecretusername, $PPPSecretField, $PPPSecretData)
    {
        $PPPSecretData     = str_replace('"', '\"', $PPPSecretData);
        $PPPSecretusername = str_replace('"', '\"', $PPPSecretusername);

        $router = RouterList::where('router_name', $router_name)->first();
        if ($router && $router->action === 'connected') {
            try {
                $mikrotikSSHService = new MikrotikSSHService($router->ip_address, $router->ssh_port, $router->username, $router->password);
                return $mikrotikSSHService->executeCommand(
                    '/ppp secret set ' . $PPPSecretField . '="' . $PPPSecretData . '" [find name="' . $PPPSecretusername . '"]'
                );
            } catch (\Exception $e) {
                return 'Error: ' . $e->getMessage();
            }
        } else {
            return 'Router is not connected or not found';
        }
    }

    // =========================================================================
    // PPP PROFILES (existing methods kept intact)
    // =========================================================================

    public function pushProfileToRouters(string $name, string $rateLimit, ?string $localAddress = null, ?string $remoteAddress = null, ?string $routerName = null): array
    {
        $query = RouterList::where('action', 'connected');
        if ($routerName) $query->where('router_name', $routerName);
        $routers = $query->get();
        $results = [];

        foreach ($routers as $router) {
            try {
                $ssh     = new MikrotikSSHService($router->ip_address, $router->ssh_port, $router->username, $router->password);
                $options = 'rate-limit="' . $rateLimit . '"';
                if (!empty($localAddress))  $options .= ' local-address="' . $localAddress . '"';
                if (!empty($remoteAddress)) $options .= ' remote-address="' . $remoteAddress . '"';

                $check  = $ssh->executeCommand('/ppp profile print count-only where name="' . $name . '"');
                $exists = intval(trim($check)) > 0;

                $cmd = $exists
                    ? '/ppp profile set ' . $options . ' [find name="' . $name . '"]'
                    : '/ppp profile add name="' . $name . '" ' . $options;

                $ssh->executeCommand($cmd);
                $results[$router->router_name] = 'OK';
            } catch (\Exception $e) {
                $results[$router->router_name] = 'Error: ' . $e->getMessage();
            }
        }

        return $results;
    }

    public function updateProfileOnRouters(string $oldName, string $newName, string $rateLimit, ?string $localAddress = null, ?string $remoteAddress = null, ?string $routerName = null): array
    {
        $query = RouterList::where('action', 'connected');
        if ($routerName) $query->where('router_name', $routerName);
        $routers = $query->get();
        $results = [];

        foreach ($routers as $router) {
            try {
                $ssh     = new MikrotikSSHService($router->ip_address, $router->ssh_port, $router->username, $router->password);
                if ($oldName !== $newName) {
                    $ssh->executeCommand('/ppp profile set name="' . $newName . '" [find name="' . $oldName . '"]');
                }
                $options = 'rate-limit="' . $rateLimit . '"';
                if (!empty($localAddress))  $options .= ' local-address="' . $localAddress . '"';
                if (!empty($remoteAddress)) $options .= ' remote-address="' . $remoteAddress . '"';
                $ssh->executeCommand('/ppp profile set ' . $options . ' [find name="' . $newName . '"]');
                $results[$router->router_name] = 'OK';
            } catch (\Exception $e) {
                $results[$router->router_name] = 'Error: ' . $e->getMessage();
            }
        }

        return $results;
    }

    public function deleteProfileFromRouters(string $name, ?string $routerName = null): array
    {
        $query = RouterList::where('action', 'connected');
        if ($routerName) $query->where('router_name', $routerName);
        $routers = $query->get();
        $results = [];

        foreach ($routers as $router) {
            try {
                $ssh = new MikrotikSSHService($router->ip_address, $router->ssh_port, $router->username, $router->password);
                $ssh->executeCommand('/ppp profile remove [find name="' . $name . '"]');
                $results[$router->router_name] = 'OK';
            } catch (\Exception $e) {
                $results[$router->router_name] = 'Error: ' . $e->getMessage();
            }
        }

        return $results;
    }

    // =========================================================================
    // IP ADDRESSES
    // =========================================================================

    public function getIpAddresses(string $routerName): array
    {
        return $this->singleRead($routerName, '/ip/address/print', '/ip address print without-paging terse');
    }

    public function addIpAddress(string $routerName, string $address, string $interface, ?string $comment = null, ?string $editId = null): string
    {
        $cmd = $editId
            ? "/ip address set numbers={$editId} address=\"{$address}\" interface=\"{$interface}\""
            : "/ip address add address=\"{$address}\" interface=\"{$interface}\"";
        if ($comment) $cmd .= ' comment="' . addslashes($comment) . '"';
        return $this->singleWrite($routerName, $cmd);
    }

    public function removeIpAddress(string $routerName, string $address): string
    {
        return $this->singleWrite($routerName, "/ip address remove [find address=\"{$address}\"]");
    }

    // =========================================================================
    // IP POOLS
    // =========================================================================

    public function getIpPools(string $routerName): array
    {
        return $this->singleRead($routerName, '/ip/pool/print', '/ip pool print without-paging terse');
    }

    public function addIpPool(string $routerName, string $name, string $ranges, ?string $nextPool = null, ?string $editId = null): string
    {
        $cmd = $editId
            ? "/ip pool set numbers={$editId} name=\"{$name}\" ranges=\"{$ranges}\""
            : "/ip pool add name=\"{$name}\" ranges=\"{$ranges}\"";
        if ($nextPool) $cmd .= " next-pool=\"{$nextPool}\"";
        return $this->singleWrite($routerName, $cmd);
    }

    public function removeIpPool(string $routerName, string $name): string
    {
        return $this->singleWrite($routerName, "/ip pool remove [find name=\"{$name}\"]");
    }

    // =========================================================================
    // DHCP SERVER
    // =========================================================================

    public function getDhcpServers(string $routerName): array
    {
        return $this->singleRead($routerName, '/ip/dhcp-server/print', '/ip dhcp-server print without-paging terse');
    }

    public function getDhcpNetworks(string $routerName): array
    {
        return $this->singleRead($routerName, '/ip/dhcp-server/network/print', '/ip dhcp-server network print without-paging terse');
    }

    public function addDhcpServer(string $routerName, array $p, ?string $editId = null): string
    {
        $name    = addslashes($p['name']      ?? '');
        $iface   = $p['interface']           ?? '';
        $pool    = $p['address_pool']        ?? 'static-only';
        $lease   = $p['lease_time']          ?? '00:10:00';
        $comment = addslashes($p['comment']   ?? '');

        $cmd = $editId
            ? "/ip dhcp-server set numbers={$editId} name=\"{$name}\" interface=\"{$iface}\" address-pool=\"{$pool}\" lease-time={$lease}"
            : "/ip dhcp-server add name=\"{$name}\" interface=\"{$iface}\" address-pool=\"{$pool}\" lease-time={$lease}";
        if ($comment) $cmd .= " comment=\"{$comment}\"";
        return $this->singleWrite($routerName, $cmd);
    }

    public function removeDhcpServer(string $routerName, string $name): string
    {
        return $this->singleWrite($routerName, "/ip dhcp-server remove [find name=\"{$name}\"]");
    }

    public function toggleDhcpServer(string $routerName, string $name, bool $enable): string
    {
        $action = $enable ? 'enable' : 'disable';
        return $this->singleWrite($routerName, "/ip dhcp-server {$action} [find name=\"{$name}\"]");
    }

    public function addDhcpNetwork(string $routerName, array $p, ?string $editId = null): string
    {
        $address = $p['address']    ?? '';
        $gateway = $p['gateway']    ?? '';
        $dns     = $p['dns_server'] ?? '';
        $comment = addslashes($p['comment'] ?? '');

        $cmd = $editId
            ? "/ip dhcp-server network set numbers={$editId} address=\"{$address}\" gateway=\"{$gateway}\" dns-server=\"{$dns}\""
            : "/ip dhcp-server network add address=\"{$address}\" gateway=\"{$gateway}\" dns-server=\"{$dns}\"";
        if ($comment) $cmd .= " comment=\"{$comment}\"";
        return $this->singleWrite($routerName, $cmd);
    }

    public function removeDhcpNetwork(string $routerName, string $address): string
    {
        return $this->singleWrite($routerName, "/ip dhcp-server network remove [find address=\"{$address}\"]");
    }

    // =========================================================================
    // INTERFACES
    // =========================================================================

    public function getInterfaces(string $routerName): array
    {
        return $this->singleRead($routerName, '/interface/print', '/interface print without-paging terse');
    }

    public function toggleInterface(string $routerName, string $name, bool $enable): string
    {
        $action = $enable ? 'enable' : 'disable';
        return $this->singleWrite($routerName, "/interface {$action} [find name=\"{$name}\"]");
    }

    // =========================================================================
    // VLANs
    // =========================================================================

    public function getVlans(string $routerName): array
    {
        return $this->singleRead($routerName, '/interface/vlan/print', '/interface vlan print without-paging terse');
    }

    public function addVlan(string $routerName, string $name, int $vlanId, string $interface, ?string $comment = null, ?string $editId = null): string
    {
        $cmd = $editId
            ? "/interface vlan set numbers={$editId} name=\"{$name}\" vlan-id={$vlanId} interface=\"{$interface}\""
            : "/interface vlan add name=\"{$name}\" vlan-id={$vlanId} interface=\"{$interface}\"";
        if ($comment) $cmd .= ' comment="' . addslashes($comment) . '"';
        return $this->singleWrite($routerName, $cmd);
    }

    public function removeVlan(string $routerName, string $name): string
    {
        return $this->singleWrite($routerName, "/interface vlan remove [find name=\"{$name}\"]");
    }

    // =========================================================================
    // BRIDGES
    // =========================================================================

    public function getBridges(string $routerName): array
    {
        return $this->singleRead($routerName, '/interface/bridge/print', '/interface bridge print without-paging terse');
    }

    public function getBridgePorts(string $routerName): array
    {
        return $this->singleRead($routerName, '/interface/bridge/port/print', '/interface bridge port print without-paging terse');
    }

    public function addBridge(string $routerName, string $name, ?string $comment = null): string
    {
        $cmd = "/interface bridge add name=\"{$name}\"";
        if ($comment) $cmd .= ' comment="' . addslashes($comment) . '"';
        return $this->singleWrite($routerName, $cmd);
    }

    public function removeBridge(string $routerName, string $name): string
    {
        return $this->singleWrite($routerName, "/interface bridge remove [find name=\"{$name}\"]");
    }

    // =========================================================================
    // PPPoE SERVER
    // =========================================================================

    public function getPppoeServers(string $routerName): array
    {
        return $this->singleRead($routerName, '/interface/pppoe-server/print', '/interface pppoe-server server print without-paging terse');
    }

    public function addPppoeServer(string $routerName, array $p, ?string $editId = null): string
    {
        $iface   = $p['interface']      ?? 'ether1';
        $svcName = $p['service_name']   ?? 'pppoe-server';
        $name    = $p['name']           ?? $svcName;
        $mtu     = $p['max_mtu']        ?? 1480;
        $mru     = $p['max_mru']        ?? 1480;
        $ka      = $p['keepalive']      ?? 10;
        $auth    = $p['authentication'] ?? 'mschap2';

        $cmd = $editId
            ? "/interface pppoe-server server set numbers={$editId} interface=\"{$iface}\" service-name=\"{$svcName}\" " .
              "name=\"{$name}\" max-mtu={$mtu} max-mru={$mru} keepalive-timeout={$ka} authentication={$auth}"
            : "/interface pppoe-server server add interface=\"{$iface}\" service-name=\"{$svcName}\" " .
              "name=\"{$name}\" max-mtu={$mtu} max-mru={$mru} keepalive-timeout={$ka} authentication={$auth}";

        return $this->singleWrite($routerName, $cmd);
    }

    public function removePppoeServer(string $routerName, string $name): string
    {
        return $this->singleWrite($routerName, "/interface pppoe-server server remove [find name=\"{$name}\"]");
    }

    // =========================================================================
    // PPP PROFILES & SECRETS (setup read methods)
    // =========================================================================

    public function getPppProfiles(string $routerName): array
    {
        return $this->singleRead($routerName, '/ppp/profile/print', '/ppp profile print without-paging terse');
    }

    public function getPppSecrets(string $routerName): array
    {
        return $this->singleRead($routerName, '/ppp/secret/print', '/ppp secret print without-paging terse');
    }

    public function addPppSecret(string $routerName, array $p, ?string $editId = null): string
    {
        $name    = addslashes($p['name']     ?? '');
        $pass    = addslashes($p['password'] ?? '');
        $profile = addslashes($p['profile']  ?? 'default');
        $service = $p['service']             ?? 'pppoe';
        $comment = addslashes($p['comment']  ?? '');

        $cmd = $editId
            ? "/ppp secret set numbers={$editId} name=\"{$name}\" password=\"{$pass}\" profile=\"{$profile}\" service={$service}"
            : "/ppp secret add name=\"{$name}\" password=\"{$pass}\" profile=\"{$profile}\" service={$service}";
        if ($comment) $cmd .= " comment=\"{$comment}\"";
        return $this->singleWrite($routerName, $cmd);
    }

    public function deletePppSecret(string $routerName, string $name): string
    {
        return $this->singleWrite($routerName, "/ppp secret remove [find name=\"{$name}\"]");
    }

    public function getActivePppSessions(string $routerName): array
    {
        return $this->singleRead($routerName, '/ppp/active/print', '/ppp active print without-paging terse');
    }

    // =========================================================================
    // HOTSPOT
    // =========================================================================

    public function getHotspotServers(string $routerName): array
    {
        return $this->singleRead($routerName, '/ip/hotspot/print', '/ip hotspot print without-paging terse');
    }

    public function getHotspotProfiles(string $routerName): array
    {
        return $this->singleRead($routerName, '/ip/hotspot/profile/print', '/ip hotspot profile print without-paging terse');
    }

    public function getHotspotUsers(string $routerName): array
    {
        return $this->singleRead($routerName, '/ip/hotspot/user/print', '/ip hotspot user print without-paging terse');
    }

    public function getHotspotUserProfiles(string $routerName): array
    {
        return $this->singleRead($routerName, '/ip/hotspot/user/profile/print', '/ip hotspot user profile print without-paging terse');
    }

    public function getHotspotActiveSessions(string $routerName): array
    {
        return $this->singleRead($routerName, '/ip/hotspot/active/print', '/ip hotspot active print without-paging terse');
    }

    public function addHotspotUser(string $routerName, array $p, ?string $editId = null): string
    {
        $name    = addslashes($p['name']     ?? '');
        $pass    = addslashes($p['password'] ?? '');
        $profile = addslashes($p['profile']  ?? 'default');
        $comment = addslashes($p['comment']  ?? '');

        $cmd = $editId
            ? "/ip hotspot user set numbers={$editId} name=\"{$name}\" password=\"{$pass}\" profile=\"{$profile}\""
            : "/ip hotspot user add name=\"{$name}\" password=\"{$pass}\" profile=\"{$profile}\"";
        if ($comment) $cmd .= " comment=\"{$comment}\"";
        return $this->singleWrite($routerName, $cmd);
    }

    public function removeHotspotUser(string $routerName, string $name): string
    {
        return $this->singleWrite($routerName, "/ip hotspot user remove [find name=\"{$name}\"]");
    }

    public function addHotspotUserProfile(string $routerName, array $p, ?string $editId = null): string
    {
        $name        = addslashes($p['name']            ?? '');
        $rateLimit   = $p['rate_limit']                 ?? '';
        $sharedUsers = $p['shared_users']               ?? 1;
        $sessionTime = $p['session_timeout']            ?? '';

        $cmd = $editId
            ? "/ip hotspot user profile set numbers={$editId} name=\"{$name}\" shared-users={$sharedUsers}"
            : "/ip hotspot user profile add name=\"{$name}\" shared-users={$sharedUsers}";
        if ($rateLimit)   $cmd .= " rate-limit=\"{$rateLimit}\"";
        if ($sessionTime) $cmd .= " session-timeout={$sessionTime}";
        return $this->singleWrite($routerName, $cmd);
    }

    public function removeHotspotUserProfile(string $routerName, string $name): string
    {
        return $this->singleWrite($routerName, "/ip hotspot user profile remove [find name=\"{$name}\"]");
    }

    // =========================================================================
    // RADIUS
    // =========================================================================

    public function getRadiusServers(string $routerName): array
    {
        return $this->singleRead($routerName, '/radius/print', '/radius print without-paging terse');
    }

    public function addRadiusServer(string $routerName, array $p, ?string $editId = null): string
    {
        $address  = $p['address']   ?? '';
        $secret   = addslashes($p['secret']  ?? '');
        $service  = $p['service']   ?? 'ppp';
        $authPort = $p['auth_port'] ?? 1812;
        $acctPort = $p['acct_port'] ?? 1813;
        $timeout  = $p['timeout']   ?? 3000;
        $comment  = addslashes($p['comment'] ?? '');

        $cmd = $editId
            ? "/radius set numbers={$editId} address={$address} secret=\"{$secret}\" service={$service} authentication-port={$authPort} accounting-port={$acctPort} timeout={$timeout}"
            : "/radius add address={$address} secret=\"{$secret}\" service={$service} authentication-port={$authPort} accounting-port={$acctPort} timeout={$timeout}";
        if ($comment) $cmd .= " comment=\"{$comment}\"";
        return $this->singleWrite($routerName, $cmd);
    }

    public function removeRadiusServer(string $routerName, string $address): string
    {
        return $this->singleWrite($routerName, "/radius remove [find address={$address}]");
    }

    public function toggleRadiusServer(string $routerName, string $address, bool $enable): string
    {
        $action = $enable ? 'enable' : 'disable';
        return $this->singleWrite($routerName, "/radius {$action} [find address={$address}]");
    }

    // =========================================================================
    // FIREWALL — FILTER
    // =========================================================================

    public function getFirewallFilter(string $routerName): array
    {
        return $this->singleRead($routerName, '/ip/firewall/filter/print', '/ip firewall filter print without-paging terse');
    }

    public function addFirewallFilter(string $routerName, array $p, ?string $editId = null): string
    {
        $chain    = $p['chain']       ?? 'forward';
        $action   = $p['action']      ?? 'accept';
        $protocol = $p['protocol']    ?? '';
        $src      = $p['src_address'] ?? '';
        $dst      = $p['dst_address'] ?? '';
        $comment  = addslashes($p['comment'] ?? '');

        $cmd = $editId 
            ? "/ip firewall filter set numbers={$editId} chain={$chain} action={$action}"
            : "/ip firewall filter add chain={$chain} action={$action}";

        if ($protocol) $cmd .= " protocol={$protocol}";
        if ($src)      $cmd .= " src-address={$src}";
        if ($dst)      $cmd .= " dst-address={$dst}";
        if ($comment)  $cmd .= " comment=\"{$comment}\"";
        return $this->singleWrite($routerName, $cmd);
    }

    public function toggleFirewallFilter(string $routerName, int $index, bool $enable): string
    {
        $action = $enable ? 'enable' : 'disable';
        return $this->singleWrite($routerName, "/ip firewall filter {$action} {$index}");
    }

    public function removeFirewallFilter(string $routerName, int $index): string
    {
        return $this->singleWrite($routerName, "/ip firewall filter remove {$index}");
    }

    // =========================================================================
    // FIREWALL — NAT
    // =========================================================================

    public function getFirewallNat(string $routerName): array
    {
        return $this->singleRead($routerName, '/ip/firewall/nat/print', '/ip firewall nat print without-paging terse');
    }

    public function addFirewallNat(string $routerName, array $p, ?string $editId = null): string
    {
        $chain    = $p['chain']         ?? 'srcnat';
        $action   = $p['action']        ?? 'masquerade';
        $outIface = $p['out_interface'] ?? '';
        $src      = $p['src_address']   ?? '';
        $comment  = addslashes($p['comment'] ?? '');

        $cmd = $editId
            ? "/ip firewall nat set numbers={$editId} chain={$chain} action={$action}"
            : "/ip firewall nat add chain={$chain} action={$action}";

        if ($outIface) $cmd .= " out-interface=\"{$outIface}\"";
        if ($src)      $cmd .= " src-address={$src}";
        if ($comment)  $cmd .= " comment=\"{$comment}\"";
        return $this->singleWrite($routerName, $cmd);
    }

    public function toggleFirewallNat(string $routerName, int $index, bool $enable): string
    {
        $action = $enable ? 'enable' : 'disable';
        return $this->singleWrite($routerName, "/ip firewall nat {$action} {$index}");
    }

    public function removeFirewallNat(string $routerName, int $index): string
    {
        return $this->singleWrite($routerName, "/ip firewall nat remove {$index}");
    }

    // =========================================================================
    // FIREWALL — MANGLE
    // =========================================================================

    public function getFirewallMangle(string $routerName): array
    {
        return $this->singleRead($routerName, '/ip/firewall/mangle/print', '/ip firewall mangle print without-paging terse');
    }

    public function toggleFirewallMangle(string $routerName, int $index, bool $enable): string
    {
        $action = $enable ? 'enable' : 'disable';
        return $this->singleWrite($routerName, "/ip firewall mangle {$action} {$index}");
    }

    public function removeFirewallMangle(string $routerName, int $index): string
    {
        return $this->singleWrite($routerName, "/ip firewall mangle remove {$index}");
    }

    // =========================================================================
    // FIREWALL — ADDRESS LISTS
    // =========================================================================

    public function getAddressLists(string $routerName): array
    {
        return $this->singleRead($routerName, '/ip/firewall/address-list/print', '/ip firewall address-list print without-paging terse');
    }

    public function addAddressList(string $routerName, string $list, string $address, ?string $comment = null, ?string $editId = null): string
    {
        $cmd = $editId
            ? "/ip firewall address-list set numbers={$editId} list=\"{$list}\" address={$address}"
            : "/ip firewall address-list add list=\"{$list}\" address={$address}";
        if ($comment) $cmd .= ' comment="' . addslashes($comment) . '"';
        return $this->singleWrite($routerName, $cmd);
    }

    public function removeAddressList(string $routerName, string $list, string $address): string
    {
        return $this->singleWrite($routerName, "/ip firewall address-list remove [find list=\"{$list}\" address={$address}]");
    }

    // =========================================================================
    // QUEUES — SIMPLE
    // =========================================================================

    public function getSimpleQueues(string $routerName): array
    {
        return $this->singleRead($routerName, '/queue/simple/print', '/queue simple print without-paging terse');
    }

    public function addSimpleQueue(string $routerName, array $p, ?string $editId = null): string
    {
        $name     = addslashes($p['name']     ?? '');
        $target   = $p['target']              ?? '';
        $maxLimit = $p['max_limit']           ?? '10M/10M';
        $comment  = addslashes($p['comment']  ?? '');

        $cmd = $editId
            ? "/queue simple set numbers={$editId} name=\"{$name}\" target={$target} max-limit={$maxLimit}"
            : "/queue simple add name=\"{$name}\" target={$target} max-limit={$maxLimit}";
        if ($comment) $cmd .= " comment=\"{$comment}\"";
        return $this->singleWrite($routerName, $cmd);
    }

    public function removeSimpleQueue(string $routerName, string $name): string
    {
        return $this->singleWrite($routerName, "/queue simple remove [find name=\"{$name}\"]");
    }

    public function toggleSimpleQueue(string $routerName, string $name, bool $enable): string
    {
        $action = $enable ? 'enable' : 'disable';
        return $this->singleWrite($routerName, "/queue simple {$action} [find name=\"{$name}\"]");
    }

    // =========================================================================
    // QUEUES — TREE
    // =========================================================================

    public function getQueueTree(string $routerName): array
    {
        return $this->singleRead($routerName, '/queue/tree/print', '/queue tree print without-paging terse');
    }

    public function addQueueTree(string $routerName, array $p, ?string $editId = null): string
    {
        $name     = addslashes($p['name']     ?? '');
        $parent   = $p['parent']              ?? 'global';
        $maxLimit = $p['max_limit']           ?? '10M';
        $limitAt  = $p['limit_at']            ?? '';
        $priority = $p['priority']            ?? 8;
        $comment  = addslashes($p['comment']  ?? '');

        $cmd = $editId
            ? "/queue tree set numbers={$editId} name=\"{$name}\" parent={$parent} max-limit={$maxLimit} priority={$priority}"
            : "/queue tree add name=\"{$name}\" parent={$parent} max-limit={$maxLimit} priority={$priority}";
        if ($limitAt) $cmd .= " limit-at={$limitAt}";
        if ($comment) $cmd .= " comment=\"{$comment}\"";
        return $this->singleWrite($routerName, $cmd);
    }

    public function removeQueueTree(string $routerName, string $name): string
    {
        return $this->singleWrite($routerName, "/queue tree remove [find name=\"{$name}\"]");
    }

    public function getQueueTypes(string $routerName): array
    {
        return $this->singleRead($routerName, '/queue/type/print', '/queue type print without-paging terse');
    }

    // =========================================================================
    // VPN — L2TP / PPTP / SSTP
    // =========================================================================

    public function getL2tpStatus(string $routerName): array
    {
        return $this->singleRead($routerName, '/interface/l2tp-server/server/print', '/interface l2tp-server server print without-paging');
    }

    public function setL2tpServer(string $routerName, bool $enabled, string $profile = 'default', string $auth = 'mschap2', ?string $ipsecSecret = null): string
    {
        $e   = $enabled ? 'yes' : 'no';
        $cmd = "/interface l2tp-server server set enabled={$e} default-profile=\"{$profile}\" authentication={$auth}";
        if ($ipsecSecret) $cmd .= ' ipsec-secret="' . addslashes($ipsecSecret) . '" use-ipsec=yes';
        return $this->singleWrite($routerName, $cmd);
    }

    public function getPptpStatus(string $routerName): array
    {
        return $this->singleRead($routerName, '/interface/pptp-server/server/print', '/interface pptp-server server print without-paging');
    }

    public function setPptpServer(string $routerName, bool $enabled, string $profile = 'default', string $auth = 'mschap2'): string
    {
        $e = $enabled ? 'yes' : 'no';
        return $this->singleWrite($routerName, "/interface pptp-server server set enabled={$e} default-profile=\"{$profile}\" authentication={$auth}");
    }

    public function getSstpStatus(string $routerName): array
    {
        return $this->singleRead($routerName, '/interface/sstp-server/server/print', '/interface sstp-server server print without-paging');
    }

    public function setSstpServer(string $routerName, bool $enabled, string $profile = 'default', int $port = 443): string
    {
        $e = $enabled ? 'yes' : 'no';
        return $this->singleWrite($routerName, "/interface sstp-server server set enabled={$e} default-profile=\"{$profile}\" port={$port}");
    }

    // =========================================================================
    // TRAFFIC MONITORING
    // =========================================================================

    public function getLiveTraffic(string $routerName, string $interface): array
    {
        $router = RouterList::where('router_name', $routerName)->where('action', 'connected')->first();
        if (!$router) return ['rx-bits-per-second' => 0, 'tx-bits-per-second' => 0];

        try {
            if ($router->api_port) {
                try {
                    $api = new MikrotikApiService($router->ip_address, $router->api_port, $router->username, $router->password);
                    $res = $api->executeCommand('/interface/monitor-traffic', ['interface' => $interface, 'once' => '']);
                    if (is_array($res) && isset($res[0])) {
                        return $this->normalizeTrafficData($res[0]);
                    }
                } catch (\Exception $e) {
                    // API failed, fallback to SSH
                }
            }

            if ($router->ssh_port) {
                $ssh = new MikrotikSSHService($router->ip_address, $router->ssh_port, $router->username, $router->password);
                // Removed 'as-value' for SSH compatibility, added normalization
                $res = $ssh->executeCommandParsable("/interface monitor-traffic \"{$interface}\" once");
                if (is_array($res) && isset($res[0])) {
                    return $this->normalizeTrafficData($res[0]);
                }
            }
        } catch (\Exception $e) {
            // silent fail
        }

        return ['rx-bits-per-second' => 0, 'tx-bits-per-second' => 0];
    }

    /**
     * Normalizes speed values (e.g., '12.3kbps' or '1.2Mbps') into raw bits-per-second integers.
     */
    protected function normalizeTrafficData(array $data): array
    {
        $keys = ['rx-bits-per-second', 'tx-bits-per-second'];
        foreach ($keys as $key) {
            if (!isset($data[$key])) {
                $data[$key] = 0;
                continue;
            }

            $val = strtolower((string)$data[$key]);
            if (is_numeric($val)) {
                $data[$key] = (int)$val;
                continue;
            }

            $multiplier = 1;
            if (str_contains($val, 'gbps')) {
                $multiplier = 1000000000;
                $val = str_replace('gbps', '', $val);
            } elseif (str_contains($val, 'mbps')) {
                $multiplier = 1000000;
                $val = str_replace('mbps', '', $val);
            } elseif (str_contains($val, 'kbps')) {
                $multiplier = 1000;
                $val = str_replace('kbps', '', $val);
            } elseif (str_contains($val, 'bps')) {
                $multiplier = 1;
                $val = str_replace('bps', '', $val);
            }

            $data[$key] = (int)((float)trim($val) * $multiplier);
        }
        return $data;
    }
}
