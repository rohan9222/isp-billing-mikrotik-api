<?php

namespace App\Services;

use phpseclib3\Exception\RuntimeException;
use phpseclib3\Net\SSH2;

class MikrotikSSHServicecopy
{
    protected $ssh;

    public function __construct($host, $port, $username, $password)
    {
        $this->ssh = new SSH2($host, $port);

        if (! $this->ssh->login($username, $password)) {
            throw new \Exception('Login Failed');
        }
    }

    // SSH command execution method
    public function executeCommand($command)
    {
        try {
            return $this->ssh->exec($command);
        } catch (RuntimeException $e) {
            return 'Error: '.$e->getMessage();
        }
    }

    // Fetch connected routers from the Mikrotik Router
    public function getInterface()
    {

        $routerLists = RouterList::where('action', 'connected')->get();

        foreach ($routerLists as $routerList) {
            try {
                $mikrotikSSHService = new MikrotikSSHService(
                    $routerList->ip_address,
                    $routerList->ssh_port,
                    $routerList->username,
                    $routerList->password
                );
                $interfaces = $mikrotikSSHService->executeCommand('/interface print where type="ether" or type="vlan"');
                flash()->success('Router '.$routerList->router_name.' is connected successfully!');
            } catch (\Exception $e) {
                flash()->error('Router '.$routerList->router_name.' is not connected!');
            }
        }
    }

    // Fetch PPP Secrets from the Mikrotik Router
    public function getPPPSecrets()
    {
        $command = '/ppp secret print without-paging terse';
        try {
            $result = $this->ssh->exec($command);
            \dd($result);
            // Split the result into lines
            $lines = explode("\n", trim($result));
            $secrets = [];
            $currentSecret = [];

            foreach ($lines as $line) {
                // Skip unnecessary lines
                if (strpos($line, 'Flags:') !== false || strpos($line, 'Columns:') !== false || strpos($line, '#') === 0) {
                    continue;
                }

                if (strpos($line, ';;;') !== false) {
                    $currentSecret['comment'] = trim(str_replace(';;;', '', $line));
                } elseif (strpos($line, 'pppoe') !== false) {
                    $line = trim($line);
                    $parts = preg_split('/\s+/', $line);

                    if (count($parts) > 1) {
                        $currentSecret['id'] = $parts[0];

                        if (strpos($parts[1], 'X') !== false) {
                            // Deactivated user with "X" flag
                            $currentSecret['active'] = 'Disable';
                            $currentSecret['name'] = isset($parts[2]) ? trim($parts[2]) : 'N/A';
                            $currentSecret['service'] = isset($parts[3]) ? $parts[3] : 'N/A';
                            $currentSecret = $this->fillPPPSecret($currentSecret, $parts, 4);

                        } else {
                            // Active user
                            $currentSecret['active'] = 'Enable';
                            $currentSecret['name'] = isset($parts[1]) ? trim($parts[1]) : 'N/A';
                            $currentSecret['service'] = isset($parts[2]) ? $parts[2] : 'N/A';
                            $currentSecret = $this->fillPPPSecret($currentSecret, $parts, 3);
                        }

                        $secrets[] = $currentSecret;
                    }
                    $currentSecret = []; // Reset for the next secret
                }
            }

            return $secrets;

        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    // Helper method to fill in PPP secret fields
    private function fillPPPSecret($currentSecret, $parts, $callerIndex)
    {
        if (isset($parts[$callerIndex]) && strpos($parts[$callerIndex], ':') !== false) {
            $currentSecret['caller_id'] = $parts[$callerIndex];
            $currentSecret['password'] = isset($parts[$callerIndex + 1]) ? $parts[$callerIndex + 1] : 'N/A';
            $currentSecret['profile'] = isset($parts[$callerIndex + 2]) ? $parts[$callerIndex + 2] : 'N/A';
        } else {
            $currentSecret['caller_id'] = 'N/A';
            $currentSecret['password'] = isset($parts[$callerIndex]) ? $parts[$callerIndex] : 'N/A';
            $currentSecret['profile'] = isset($parts[$callerIndex + 1]) ? $parts[$callerIndex + 1] : 'N/A';
        }

        return $currentSecret;
    }
}
