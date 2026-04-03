<?php

namespace App\Services;

use App\Http\Controllers\MikrotikController;
use App\Models\RouterList;
use Exception;

/**
 * MikrotikSetupService
 *
 * Uses the existing MikrotikController::routerList() / checkConnection() for all
 * READ operations (API-first, SSH fallback), and MikrotikSSHService directly for
 * all WRITE operations — consistent with how MikrotikController already works.
 */
class MikrotikSetupService
{
    protected MikrotikController $ctrl;

    public function __construct(MikrotikController $ctrl)
    {
        $this->ctrl = $ctrl;
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    /**
     * Run a READ command on a single named router.
     * Uses MikrotikController::routerList() which tries API first then SSH.
     * Returns flat array of items.
     *
     * @throws Exception on connection/command error
     */
    protected function read(string $routerName, string $apiCmd, string $sshCmd): array
    {
        $results = $this->ctrl->routerList($routerName, $apiCmd, $sshCmd);
        $data = $results[$routerName] ?? [];

        if (is_string($data)) {
            throw new Exception($data);
        }

        return is_array($data) ? $data : [];
    }

    /**
     * Run a WRITE command (add/set/remove) on a single named router via SSH.
     * Mirrors the pattern used by MikrotikController::enablePPPSecret() etc.
     *
     * @throws Exception if router not found or SSH fails
     */
    protected function write(string $routerName, string $command): string
    {
        $router = RouterList::where('router_name', $routerName)
            ->where('action', 'connected')
            ->first();

        if (! $router) {
            throw new Exception("Router '{$routerName}' not found or not connected.");
        }

        $ssh = new MikrotikSSHService(
            $router->ip_address,
            $router->ssh_port,
            $router->username,
            $router->password
        );

        return $ssh->executeCommand($command) ?? '';
    }

    // ─── IP Addresses ─────────────────────────────────────────────────────────

    public function getIpAddresses(string $routerName): array
    {
        return $this->read($routerName,
            '/ip/address/print',
            '/ip address print without-paging terse'
        );
    }

    public function addIpAddress(string $routerName, string $address, string $interface, ?string $comment = null): string
    {
        $cmd = "/ip address add address=\"{$address}\" interface=\"{$interface}\"";
        if ($comment) {
            $cmd .= ' comment="'.addslashes($comment).'"';
        }

        return $this->write($routerName, $cmd);
    }

    public function removeIpAddress(string $routerName, string $address): string
    {
        return $this->write($routerName, "/ip address remove [find address=\"{$address}\"]");
    }

    // ─── IP Pools ─────────────────────────────────────────────────────────────

    public function getIpPools(string $routerName): array
    {
        return $this->read($routerName,
            '/ip/pool/print',
            '/ip pool print without-paging terse'
        );
    }

    public function addIpPool(string $routerName, string $name, string $ranges, ?string $nextPool = null): string
    {
        $cmd = "/ip pool add name=\"{$name}\" ranges=\"{$ranges}\"";
        if ($nextPool) {
            $cmd .= " next-pool=\"{$nextPool}\"";
        }

        return $this->write($routerName, $cmd);
    }

    public function editIpPool(string $routerName, string $name, string $ranges, ?string $nextPool = null): string
    {
        $cmd = "/ip pool set ranges=\"{$ranges}\"";
        if ($nextPool) {
            $cmd .= " next-pool=\"{$nextPool}\"";
        } else {
            $cmd .= ' next-pool=none';
        }
        $cmd .= " [find name=\"{$name}\"]";

        return $this->write($routerName, $cmd);
    }

    public function removeIpPool(string $routerName, string $name): string
    {
        return $this->write($routerName, "/ip pool remove [find name=\"{$name}\"]");
    }

    // ─── DHCP Server ──────────────────────────────────────────────────────────

    public function getDhcpServers(string $routerName): array
    {
        return $this->read($routerName,
            '/ip/dhcp-server/print',
            '/ip dhcp-server print without-paging terse'
        );
    }

    public function getDhcpNetworks(string $routerName): array
    {
        return $this->read($routerName,
            '/ip/dhcp-server/network/print',
            '/ip dhcp-server network print without-paging terse'
        );
    }

    // ─── Interfaces ───────────────────────────────────────────────────────────

    public function getInterfaces(string $routerName): array
    {
        return $this->read($routerName,
            '/interface/print',
            '/interface print without-paging terse'
        );
    }

    public function toggleInterface(string $routerName, string $name, bool $enable): string
    {
        $action = $enable ? 'enable' : 'disable';

        return $this->write($routerName, "/interface {$action} [find name=\"{$name}\"]");
    }

    // ─── VLANs ────────────────────────────────────────────────────────────────

    public function getVlans(string $routerName): array
    {
        return $this->read($routerName,
            '/interface/vlan/print',
            '/interface vlan print without-paging terse'
        );
    }

    public function addVlan(string $routerName, string $name, int $vlanId, string $interface, ?string $comment = null): string
    {
        $cmd = "/interface vlan add name=\"{$name}\" vlan-id={$vlanId} interface=\"{$interface}\"";
        if ($comment) {
            $cmd .= ' comment="'.addslashes($comment).'"';
        }

        return $this->write($routerName, $cmd);
    }

    public function removeVlan(string $routerName, string $name): string
    {
        return $this->write($routerName, "/interface vlan remove [find name=\"{$name}\"]");
    }

    // ─── Bridges ──────────────────────────────────────────────────────────────

    public function getBridges(string $routerName): array
    {
        return $this->read($routerName,
            '/interface/bridge/print',
            '/interface bridge print without-paging terse'
        );
    }

    public function getBridgePorts(string $routerName): array
    {
        return $this->read($routerName,
            '/interface/bridge/port/print',
            '/interface bridge port print without-paging terse'
        );
    }

    public function addBridge(string $routerName, string $name, ?string $comment = null): string
    {
        $cmd = "/interface bridge add name=\"{$name}\"";
        if ($comment) {
            $cmd .= ' comment="'.addslashes($comment).'"';
        }

        return $this->write($routerName, $cmd);
    }

    public function removeBridge(string $routerName, string $name): string
    {
        return $this->write($routerName, "/interface bridge remove [find name=\"{$name}\"]");
    }

    // ─── PPPoE Server ─────────────────────────────────────────────────────────

    public function getPppoeServers(string $routerName): array
    {
        return $this->read($routerName,
            '/interface/pppoe-server/print',
            '/interface pppoe-server server print without-paging terse'
        );
    }

    public function addPppoeServer(string $routerName, array $p): string
    {
        $iface = $p['interface'] ?? 'ether1';
        $svcName = $p['service_name'] ?? 'pppoe-server';
        $name = $p['name'] ?? $svcName;
        $mtu = $p['max_mtu'] ?? 1480;
        $mru = $p['max_mru'] ?? 1480;
        $ka = $p['keepalive'] ?? 10;
        $auth = $p['authentication'] ?? 'mschap2';

        return $this->write($routerName,
            "/interface pppoe-server server add interface=\"{$iface}\" service-name=\"{$svcName}\" name=\"{$name}\" ".
            "max-mtu={$mtu} max-mru={$mru} keepalive-timeout={$ka} authentication={$auth}"
        );
    }

    public function removePppoeServer(string $routerName, string $name): string
    {
        return $this->write($routerName, "/interface pppoe-server server remove [find name=\"{$name}\"]");
    }

    // ─── PPP Profiles & Secrets ───────────────────────────────────────────────

    public function getPppProfiles(string $routerName): array
    {
        return $this->read($routerName,
            '/ppp/profile/print',
            '/ppp profile print without-paging terse'
        );
    }

    public function getPppSecrets(string $routerName): array
    {
        return $this->read($routerName,
            '/ppp/secret/print',
            '/ppp secret print without-paging terse'
        );
    }

    public function addPppSecret(string $routerName, array $p): string
    {
        $name = addslashes($p['name'] ?? '');
        $pass = addslashes($p['password'] ?? '');
        $profile = addslashes($p['profile'] ?? 'default');
        $service = $p['service'] ?? 'pppoe';
        $comment = addslashes($p['comment'] ?? '');

        $cmd = "/ppp secret add name=\"{$name}\" password=\"{$pass}\" profile=\"{$profile}\" service={$service}";
        if ($comment) {
            $cmd .= " comment=\"{$comment}\"";
        }

        return $this->write($routerName, $cmd);
    }

    public function removePppSecret(string $routerName, string $name): string
    {
        return $this->write($routerName, "/ppp secret remove [find name=\"{$name}\"]");
    }

    public function getActivePppSessions(string $routerName): array
    {
        return $this->read($routerName,
            '/ppp/active/print',
            '/ppp active print without-paging terse'
        );
    }

    // ─── Hotspot ──────────────────────────────────────────────────────────────

    public function getHotspotServers(string $routerName): array
    {
        return $this->read($routerName,
            '/ip/hotspot/print',
            '/ip hotspot print without-paging terse'
        );
    }

    public function getHotspotProfiles(string $routerName): array
    {
        return $this->read($routerName,
            '/ip/hotspot/profile/print',
            '/ip hotspot profile print without-paging terse'
        );
    }

    public function getHotspotUsers(string $routerName): array
    {
        return $this->read($routerName,
            '/ip/hotspot/user/print',
            '/ip hotspot user print without-paging terse'
        );
    }

    public function getHotspotUserProfiles(string $routerName): array
    {
        return $this->read($routerName,
            '/ip/hotspot/user/profile/print',
            '/ip hotspot user profile print without-paging terse'
        );
    }

    public function getHotspotActiveSessions(string $routerName): array
    {
        return $this->read($routerName,
            '/ip/hotspot/active/print',
            '/ip hotspot active print without-paging terse'
        );
    }

    public function addHotspotUser(string $routerName, array $p): string
    {
        $name = addslashes($p['name'] ?? '');
        $pass = addslashes($p['password'] ?? '');
        $profile = addslashes($p['profile'] ?? 'default');
        $comment = addslashes($p['comment'] ?? '');

        $cmd = "/ip hotspot user add name=\"{$name}\" password=\"{$pass}\" profile=\"{$profile}\"";
        if ($comment) {
            $cmd .= " comment=\"{$comment}\"";
        }

        return $this->write($routerName, $cmd);
    }

    public function removeHotspotUser(string $routerName, string $name): string
    {
        return $this->write($routerName, "/ip hotspot user remove [find name=\"{$name}\"]");
    }

    public function addHotspotUserProfile(string $routerName, array $p): string
    {
        $name = addslashes($p['name'] ?? '');
        $rateLimit = $p['rate_limit'] ?? '';
        $sharedUsers = $p['shared_users'] ?? 1;
        $sessionTime = $p['session_timeout'] ?? '';

        $cmd = "/ip hotspot user profile add name=\"{$name}\" shared-users={$sharedUsers}";
        if ($rateLimit) {
            $cmd .= " rate-limit=\"{$rateLimit}\"";
        }
        if ($sessionTime) {
            $cmd .= " session-timeout={$sessionTime}";
        }

        return $this->write($routerName, $cmd);
    }

    public function removeHotspotUserProfile(string $routerName, string $name): string
    {
        return $this->write($routerName, "/ip hotspot user profile remove [find name=\"{$name}\"]");
    }

    // ─── RADIUS ───────────────────────────────────────────────────────────────

    public function getRadiusServers(string $routerName): array
    {
        return $this->read($routerName,
            '/radius/print',
            '/radius print without-paging terse'
        );
    }

    public function addRadiusServer(string $routerName, array $p): string
    {
        $address = $p['address'] ?? '';
        $secret = addslashes($p['secret'] ?? '');
        $service = $p['service'] ?? 'ppp';
        $authPort = $p['auth_port'] ?? 1812;
        $acctPort = $p['acct_port'] ?? 1813;
        $timeout = $p['timeout'] ?? 3000;
        $comment = addslashes($p['comment'] ?? '');

        $cmd = "/radius add address={$address} secret=\"{$secret}\" service={$service} ".
               "authentication-port={$authPort} accounting-port={$acctPort} timeout={$timeout}";
        if ($comment) {
            $cmd .= " comment=\"{$comment}\"";
        }

        return $this->write($routerName, $cmd);
    }

    public function removeRadiusServer(string $routerName, string $address): string
    {
        return $this->write($routerName, "/radius remove [find address={$address}]");
    }

    public function toggleRadiusServer(string $routerName, string $address, bool $enable): string
    {
        $action = $enable ? 'enable' : 'disable';

        return $this->write($routerName, "/radius {$action} [find address={$address}]");
    }

    // ─── Firewall ─────────────────────────────────────────────────────────────

    public function getFirewallFilter(string $routerName): array
    {
        return $this->read($routerName,
            '/ip/firewall/filter/print',
            '/ip firewall filter print without-paging terse'
        );
    }

    public function getFirewallNat(string $routerName): array
    {
        return $this->read($routerName,
            '/ip/firewall/nat/print',
            '/ip firewall nat print without-paging terse'
        );
    }

    public function getFirewallMangle(string $routerName): array
    {
        return $this->read($routerName,
            '/ip/firewall/mangle/print',
            '/ip firewall mangle print without-paging terse'
        );
    }

    public function getAddressLists(string $routerName): array
    {
        return $this->read($routerName,
            '/ip/firewall/address-list/print',
            '/ip firewall address-list print without-paging terse'
        );
    }

    public function addFirewallFilter(string $routerName, array $p): string
    {
        $chain = $p['chain'] ?? 'forward';
        $action = $p['action'] ?? 'accept';
        $protocol = $p['protocol'] ?? '';
        $src = $p['src_address'] ?? '';
        $dst = $p['dst_address'] ?? '';
        $comment = addslashes($p['comment'] ?? '');

        $cmd = "/ip firewall filter add chain={$chain} action={$action}";
        if ($protocol) {
            $cmd .= " protocol={$protocol}";
        }
        if ($src) {
            $cmd .= " src-address={$src}";
        }
        if ($dst) {
            $cmd .= " dst-address={$dst}";
        }
        if ($comment) {
            $cmd .= " comment=\"{$comment}\"";
        }

        return $this->write($routerName, $cmd);
    }

    public function addFirewallNat(string $routerName, array $p): string
    {
        $chain = $p['chain'] ?? 'srcnat';
        $action = $p['action'] ?? 'masquerade';
        $outIface = $p['out_interface'] ?? '';
        $src = $p['src_address'] ?? '';
        $comment = addslashes($p['comment'] ?? '');

        $cmd = "/ip firewall nat add chain={$chain} action={$action}";
        if ($outIface) {
            $cmd .= " out-interface=\"{$outIface}\"";
        }
        if ($src) {
            $cmd .= " src-address={$src}";
        }
        if ($comment) {
            $cmd .= " comment=\"{$comment}\"";
        }

        return $this->write($routerName, $cmd);
    }

    public function removeFirewallRule(string $routerName, string $table, int $index): string
    {
        return $this->write($routerName, "/ip firewall {$table} remove {$index}");
    }

    public function toggleFirewallRule(string $routerName, string $table, int $index, bool $enable): string
    {
        $action = $enable ? 'enable' : 'disable';

        return $this->write($routerName, "/ip firewall {$table} {$action} {$index}");
    }

    public function addAddressList(string $routerName, string $list, string $address, ?string $comment = null): string
    {
        $cmd = "/ip firewall address-list add list=\"{$list}\" address={$address}";
        if ($comment) {
            $cmd .= ' comment="'.addslashes($comment).'"';
        }

        return $this->write($routerName, $cmd);
    }

    public function removeAddressList(string $routerName, string $list, string $address): string
    {
        return $this->write($routerName,
            "/ip firewall address-list remove [find list=\"{$list}\" address={$address}]"
        );
    }

    // ─── Queues ───────────────────────────────────────────────────────────────

    public function getSimpleQueues(string $routerName): array
    {
        return $this->read($routerName,
            '/queue/simple/print',
            '/queue simple print without-paging terse'
        );
    }

    public function addSimpleQueue(string $routerName, array $p): string
    {
        $name = addslashes($p['name'] ?? '');
        $target = $p['target'] ?? '';
        $maxLimit = $p['max_limit'] ?? '10M/10M';
        $comment = addslashes($p['comment'] ?? '');

        $cmd = "/queue simple add name=\"{$name}\" target={$target} max-limit={$maxLimit}";
        if ($comment) {
            $cmd .= " comment=\"{$comment}\"";
        }

        return $this->write($routerName, $cmd);
    }

    public function removeSimpleQueue(string $routerName, string $name): string
    {
        return $this->write($routerName, "/queue simple remove [find name=\"{$name}\"]");
    }

    public function toggleSimpleQueue(string $routerName, string $name, bool $enable): string
    {
        $action = $enable ? 'enable' : 'disable';

        return $this->write($routerName, "/queue simple {$action} [find name=\"{$name}\"]");
    }

    public function getQueueTree(string $routerName): array
    {
        return $this->read($routerName,
            '/queue/tree/print',
            '/queue tree print without-paging terse'
        );
    }

    public function addQueueTree(string $routerName, array $p): string
    {
        $name = addslashes($p['name'] ?? '');
        $parent = $p['parent'] ?? 'global';
        $maxLimit = $p['max_limit'] ?? '10M';
        $limitAt = $p['limit_at'] ?? '';
        $priority = $p['priority'] ?? 8;
        $comment = addslashes($p['comment'] ?? '');

        $cmd = "/queue tree add name=\"{$name}\" parent={$parent} max-limit={$maxLimit} priority={$priority}";
        if ($limitAt) {
            $cmd .= " limit-at={$limitAt}";
        }
        if ($comment) {
            $cmd .= " comment=\"{$comment}\"";
        }

        return $this->write($routerName, $cmd);
    }

    public function removeQueueTree(string $routerName, string $name): string
    {
        return $this->write($routerName, "/queue tree remove [find name=\"{$name}\"]");
    }

    public function getQueueTypes(string $routerName): array
    {
        return $this->read($routerName,
            '/queue/type/print',
            '/queue type print without-paging terse'
        );
    }

    // ─── VPN ──────────────────────────────────────────────────────────────────

    public function getL2tpStatus(string $routerName): array
    {
        return $this->read($routerName,
            '/interface/l2tp-server/server/print',
            '/interface l2tp-server server print without-paging'
        );
    }

    public function setL2tpServer(string $routerName, bool $enabled, string $profile = 'default', string $auth = 'mschap2', ?string $ipsecSecret = null): string
    {
        $e = $enabled ? 'yes' : 'no';
        $cmd = "/interface l2tp-server server set enabled={$e} default-profile=\"{$profile}\" authentication={$auth}";
        if ($ipsecSecret) {
            $cmd .= ' ipsec-secret="'.addslashes($ipsecSecret).'" use-ipsec=yes';
        }

        return $this->write($routerName, $cmd);
    }

    public function getPptpStatus(string $routerName): array
    {
        return $this->read($routerName,
            '/interface/pptp-server/server/print',
            '/interface pptp-server server print without-paging'
        );
    }

    public function setPptpServer(string $routerName, bool $enabled, string $profile = 'default', string $auth = 'mschap2'): string
    {
        $e = $enabled ? 'yes' : 'no';

        return $this->write($routerName,
            "/interface pptp-server server set enabled={$e} default-profile=\"{$profile}\" authentication={$auth}"
        );
    }

    public function getSstpStatus(string $routerName): array
    {
        return $this->read($routerName,
            '/interface/sstp-server/server/print',
            '/interface sstp-server server print without-paging'
        );
    }

    public function setSstpServer(string $routerName, bool $enabled, string $profile = 'default', int $port = 443): string
    {
        $e = $enabled ? 'yes' : 'no';

        return $this->write($routerName,
            "/interface sstp-server server set enabled={$e} default-profile=\"{$profile}\" port={$port}"
        );
    }
}
