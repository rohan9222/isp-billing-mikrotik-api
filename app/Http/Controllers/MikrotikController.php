<?php

namespace App\Http\Controllers;

use App\Models\PPPSecrets;
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


    public function systemOverview()
    {
        $routers = RouterList::all();
        $results = [];
        foreach ($routers as $router) {
            try {
                $systemOverview = $this->checkConnection(
                    $router->ip_address,
                    $router->ssh_port,
                    $router->api_port,
                    $router->username,
                    $router->password,
                    '/system/resource/print',
                    '/system resource print'
                );
                $results[$router->router_name] = $systemOverview;
            } catch (\Exception $e) {
                $results[$router->router_name] = 'Error: '.$e->getMessage();
            }
        }
        return $results;
    }

    public function getPPPSecrets()
    {
        $routers = RouterList::all();
        $results = [];
        foreach ($routers as $router) {
            try {
                $systemOverview = $this->checkConnection(
                    $router->ip_address,
                    $router->ssh_port,
                    $router->api_port,
                    $router->username,
                    $router->password,
                    '/ppp/secret/print',
                    '/ppp secret print without-paging terse'
                );
                $results[$router->router_name] = $systemOverview;
            } catch (\Exception $e) {
                $results[$router->router_name] = 'Error: '.$e->getMessage();
            }
        }
        dd($results);
        return $results;
    }

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

    // use this as getPPPSecrets on mikrotikSSHService
    // public function showPPPSecrets()
    // {
    //     $routerLists = RouterList::all();
    //     foreach ($routerLists as $routerList) {

    //         $host = $routerList->ip_address;

    //         $port = $routerList->ssh_port; // Default SSH port
    //         $username = $routerList->username;
    //         $password = $routerList->password;
    //         $router_name = $routerList->router_name;

    //         try {
    //             $mikrotikSSHService = new MikrotikSSHService($host, $port, $username, $password);

    //             $pppSecrets = $mikrotikSSHService->getPPPSecrets();

    //             // if (isset($pppSecrets['error'])) {
    //             //     return view('ppp-secrets', ['error' => $pppSecrets['error']]);
    //             // }
    //         // \dd($pppSecrets);
    //             // PPPSecrets::truncate();
    //             PPPSecrets::query()->update(['status' => 'removed']);

    //             foreach ($pppSecrets as $secret) {
    //                 // কেস-সেনসিটিভ চেক করা হচ্ছে
    //                 $existingSecret = PPPSecrets::where('router_name', $router_name)->whereRaw('BINARY `username` = ?', [$secret['name']])->first();
    //                 // \dd($existingSecret);

    //                 if ($existingSecret) {
    //                     // যদি ডুপ্লিকেট পাওয়া যায়, তখন এটি আপডেট করুন
    //                     $existingSecret->update([
    //                         'caller_id' => $secret['caller_id'] ?? '',
    //                         'service'   => $secret['service'] ?? '-',
    //                         'profile'   => $secret['profile'] ?? '-',
    //                         'password'  => $secret['password'] ?? '',
    //                         'comment'   => $secret['comment'] ?? '',
    //                         'status'    => ($secret['active'] === 'Disable') ? 'Disable' : $secret['active'],
    //                         'updated_at' => date('Y-m-d H:i:s'),
    //                     ]);
    //                 } else {
    //                     // যদি ডুপ্লিকেট না পাওয়া যায়, তখন নতুন ডাটা ইনসার্ট করুন
    //                     PPPSecrets::create([
    //                         'router_name' => $router_name,
    //                         'username'      => $secret['name'],
    //                         'caller_id' => $secret['caller_id'] ?? '',
    //                         'service'   => $secret['service'] ?? '-',
    //                         'profile'   => $secret['profile'] ?? '-',
    //                         'password'  => $secret['password'] ?? '',
    //                         'comment'   => $secret['comment'] ?? '',
    //                         'status'    => ($secret['active'] === 'Disable') ? 'Disable' : $secret['active'],
    //                     ]);
    //                 }
    //             }

    //             PPPSecrets::where('status', 'removed')->delete();
    //         } catch (\Exception $e) {
    //             return response()->json(['error' => $e->getMessage()], 500);
    //         }
    //     }
    //     $DBpppSecrets = PPPSecrets::all();
    //     // return view('mikrotik.ppp-secrets', compact('DBpppSecrets','pppSecrets'));
    //     return view('mikrotik.ppp-secrets', compact('DBpppSecrets'));
    // }
}
