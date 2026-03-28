<?php

namespace App\Http\Controllers;

use App\Models\RouterList;
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

    public function checkConnection($ip_address, $ssh_port, $api_port, $username, $password, $api_command = null, $ssh_command = null)
    {
        // Step 1: Try MikroTik API
        if ($api_port) {
            try {
                $mikrotikApiService = new MikrotikApiService($ip_address, $api_port, $username, $password);
                $response = $mikrotikApiService->executeCommand($api_command);

                // Check if response indicates an error
                if (is_string($response) && str_starts_with($response, 'Error:')) {
                    throw new \Exception($response);
                }

                return $response;
            } catch (\Exception $e) {
                // API fail → try SSH
                if ($ssh_port) {
                    try {
                        $mikrotikSSHService = new MikrotikSSHService($ip_address, $ssh_port, $username, $password);
                        $response = $mikrotikSSHService->executeCommandParsable($ssh_command);
                        return $response;
                    } catch (\Exception $sshEx) {
                        return 'Both API and SSH failed: ' . $sshEx->getMessage();
                    }
                } else {
                    return 'API failed and no SSH port provided: ' . $e->getMessage();
                }
            }
        }

        // Step 2: If no API port, try SSH directly
        if ($ssh_port) {
            try {
                $mikrotikSSHService = new MikrotikSSHService($ip_address, $ssh_port, $username, $password);
                $response = $mikrotikSSHService->executeCommandParsable($ssh_command);
                return $response;
            } catch (\Exception $sshEx) {
                return 'SSH failed: ' . $sshEx->getMessage();
            }
        }

        return 'No API or SSH port provided';
    }

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
                $systemOverview = $this->checkConnection(
                    $router->ip_address,
                    $router->ssh_port,
                    $router->api_port,
                    $router->username,
                    $router->password,
                    $api_command,
                    $ssh_command
                );
                $results[$router->router_name] = $systemOverview;
            } catch (\Exception $e) {
                $results[$router->router_name] = 'Error: '.$e->getMessage();
            }
        }
        return $results;
    }

    public function systemOverview()
    {
        return $this->routerList(null, '/system/resource/print', '/system resource print');
    }

    /**
     * Enable PPP Secret on Mikrotik Router
     *
     * @param int $customerID
     * @param string $router_name
     * @param string $PPPSecretPPPSecret
     * @return string
     *
     * @throws \Exception
     */

    public function enablePPPSecret($customerID, $router_name, $PPPSecretPPPSecret)
    {
        $router = RouterList::where('router_name', $router_name)->first();
        if ($router) {
            try {
                $mikrotikSSHService = new MikrotikSSHService($router->ip_address, $router->ssh_port, $router->username, $router->password);

                $interfaces = $mikrotikSSHService->executeCommand('/ppp secret enable '.$PPPSecretPPPSecret);

                return $interfaces;
                if ($interfaces != '') {
                    NotificationLogs::create([
                        'title' => 'Enable User',
                        'message' => $customerID.' ('.$PPPSecretPPPSecret.')',
                        'status' => 'Success on Mikrotik Command',
                        'type' => 'Mikrotik Command',
                    ]);
                }
            } catch (\Exception $e) {
                NotificationLogs::create([
                    'title' => 'Enable User',
                    'message' => $customerID.' ('.$PPPSecretPPPSecret.')'.$e->getMessage(),
                    'status' => 'Error on Mikrotik Command',
                    'type' => 'Mikrotik Command',
                ]);
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
                $interfaces = $mikrotikSSHService->executeCommand('/ppp secret disable '.$PPPSecretPPPSecret);

                return $interfaces;
                if ($interfaces != '') {
                    NotificationLogs::create([
                        'title' => 'Disable User',
                        'message' => $customerID.' ('.$PPPSecretPPPSecret.')',
                        'status' => 'Success on Mikrotik Command',
                        'type' => 'Mikrotik Command',
                    ]);
                }
            } catch (\Exception $e) {
                NotificationLogs::create([
                    'title' => 'Disable User',
                    'message' => $customerID.' ('.$PPPSecretPPPSecret.')'.$e->getMessage(),
                    'status' => 'Error on Mikrotik Command',
                    'type' => 'Mikrotik Command',
                ]);
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
                $interfaces = $mikrotikSSHService->executeCommand('/ppp secret remove '.$PPPSecretPPPSecret);

                return $interfaces;
                if ($interfaces != '') {
                    NotificationLogs::create([
                        'title' => 'Remove User',
                        'message' => $customerID.' ('.$PPPSecretPPPSecret.')',
                        'status' => 'Success on Mikrotik Command',
                        'type' => 'Mikrotik Command',
                    ]);
                }
            } catch (\Exception $e) {
                NotificationLogs::create([
                    'title' => 'Remove User',
                    'message' => $customerID.' ('.$PPPSecretPPPSecret.')'.$e->getMessage(),
                    'status' => 'Error on Mikrotik Command',
                    'type' => 'Mikrotik Command',
                ]);
            }
        } else {
            return 'Router not found';
        }
    }

    public function updatePPPSecret($router_name, $PPPSecretusername, $PPPSecretField, $PPPSecretData)
    {
        $PPPSecretData = str_replace('"', '\"', $PPPSecretData);
        $PPPSecretusername = str_replace('"', '\"', $PPPSecretusername);

        $router = RouterList::where('router_name', $router_name)->first();
        if ($router && $router->action === 'connected') {
            try {
                $mikrotikSSHService = new MikrotikSSHService($router->ip_address, $router->ssh_port, $router->username, $router->password);
                // $interfaces = $mikrotikSSHService->executeCommand("PPPSecretField/ppp secret set profile=NamepProfile [find name=NameSecret]");

                $interfaces = $mikrotikSSHService->executeCommand(
                    '/ppp secret set ' . $PPPSecretField . '="' . $PPPSecretData . '" [find name="' . $PPPSecretusername . '"]'
                );

                return $interfaces;
            } catch (\Exception $e) {
                return 'Error: '.$e->getMessage();
            }
        } else {
            return 'Router is not connected or not found';
        }
    }

    /**
     * Push (create or update) a PPP profile on connected routers (all or specified).
     */
    public function pushProfileToRouters(string $name, string $rateLimit, ?string $localAddress = null, ?string $remoteAddress = null, ?int $routerId = null): array
    {
        $routers = RouterList::where('action', 'connected')->when($routerId, fn($q) => $q->where('id', $routerId))->get();
        $results = [];

        foreach ($routers as $router) {
            try {
                $ssh = new MikrotikSSHService(
                    $router->ip_address, $router->ssh_port,
                    $router->username, $router->password
                );

                // Build optional arguments
                $options = 'rate-limit="' . $rateLimit . '"';
                if (!empty($localAddress)) {
                    $options .= ' local-address="' . $localAddress . '"';
                }
                if (!empty($remoteAddress)) {
                    $options .= ' remote-address="' . $remoteAddress . '"';
                }

                // Check if profile already exists
                $check = $ssh->executeCommand('/ppp profile print count-only where name="' . $name . '"');
                $exists = intval(trim($check)) > 0;

                if ($exists) {
                    $cmd = '/ppp profile set ' . $options . ' [find name="' . $name . '"]';
                } else {
                    $cmd = '/ppp profile add name="' . $name . '" ' . $options;
                }

                $ssh->executeCommand($cmd);
                $results[$router->router_name] = 'OK';
            } catch (\Exception $e) {
                $results[$router->router_name] = 'Error: ' . $e->getMessage();
            }
        }

        return $results;
    }

    /**
     * Update a PPP profile's name or configurations on connected routers (all or specified).
     */
    public function updateProfileOnRouters(string $oldName, string $newName, string $rateLimit, ?string $localAddress = null, ?string $remoteAddress = null, ?int $routerId = null): array
    {
        $routers = RouterList::where('action', 'connected')->when($routerId, fn($q) => $q->where('id', $routerId))->get();
        $results = [];

        foreach ($routers as $router) {
            try {
                $ssh = new MikrotikSSHService(
                    $router->ip_address, $router->ssh_port,
                    $router->username, $router->password
                );

                // Rename if needed
                if ($oldName !== $newName) {
                    $ssh->executeCommand('/ppp profile set name="' . $newName . '" [find name="' . $oldName . '"]');
                }

                // Build option updates
                $options = 'rate-limit="' . $rateLimit . '"';
                if (!empty($localAddress)) {
                    $options .= ' local-address="' . $localAddress . '"';
                }
                if (!empty($remoteAddress)) {
                    $options .= ' remote-address="' . $remoteAddress . '"';
                }

                $ssh->executeCommand('/ppp profile set ' . $options . ' [find name="' . $newName . '"]');
                $results[$router->router_name] = 'OK';
            } catch (\Exception $e) {
                $results[$router->router_name] = 'Error: ' . $e->getMessage();
            }
        }

        return $results;
    }

    /**
     * Delete a PPP profile from connected routers (all or specified).
     */
    public function deleteProfileFromRouters(string $name, ?int $routerId = null): array
    {
        $routers = RouterList::where('action', 'connected')->when($routerId, fn($q) => $q->where('id', $routerId))->get();
        $results = [];

        foreach ($routers as $router) {
            try {
                $ssh = new MikrotikSSHService(
                    $router->ip_address, $router->ssh_port,
                    $router->username, $router->password
                );

                $ssh->executeCommand('/ppp profile remove [find name="' . $name . '"]');
                $results[$router->router_name] = 'OK';
            } catch (\Exception $e) {
                $results[$router->router_name] = 'Error: ' . $e->getMessage();
            }
        }

        return $results;
    }

}
