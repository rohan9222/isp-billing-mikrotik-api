<?php

namespace App\Services;

use App\Models\RouterList;
use Exception;
use phpseclib3\Exception\RuntimeException;
use phpseclib3\Net\SSH2;

class MikrotikSSHService
{
    protected $ssh;

    public function __construct($host, $port, $username, $password)
    {
        $this->ssh = new SSH2($host, $port);

        if (! $this->ssh->login($username, $password)) {
            throw new Exception('Login failed for '.$host);
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
                // return $interfaces;
            } catch (Exception $e) {
                flash()->error('Router '.$routerList->router_name.' is not connected!');
            }
        }
    }

    public function getPPPSecrets()
    {
        $command = '/ppp secret print without-paging terse';
        try {
            $result = $this->ssh->exec($command);
            dd($result);
            $lines = explode("\n", trim($result));
            $secrets = [];

            foreach ($lines as $line) {
                // Adjust the regex to handle optional fields and capture the comment correctly
                if (preg_match('/(?:comment=([^=]+?)\s+)?name=(\S+)(?:\s+service=(\S*))?(?:\s+caller-id=(\S*))?(?:\s+password=(\S+))?(?:\s+profile=(\S*))?/', $line, $matches)) {
                    $secrets[] = [
                        'comment' => $matches[1] ?? 'N/A',
                        'name' => $matches[2] ?? 'N/A',
                        'service' => $matches[3] ?? 'N/A',
                        'caller_id' => $matches[4] ?? '$callerID',
                        'password' => $matches[5] ?? 'N/A',
                        'profile' => $matches[6] ?? 'N/A',
                        'active' => strpos($line, 'X') !== false ? 'disable' : 'active',
                    ];
                } else {
                    // Debug: Check if the regex is failing
                    // dd('No match for: ' . $line); // Uncomment this to debug failing lines
                }
            }

            return $secrets;
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    // SHH command execution
    /**
     * Execute any MikroTik SSH command and always return an array.
     */
    public function executeCommandParsable($command)
    {
        $output = trim($this->ssh->exec($command));

        if (empty($output)) {
            return ['message' => 'No output received'];
        }

        // Detect format automatically
        if ($this->isColonFormat($output)) {
            return $this->parseColonFormat($output);
        }

        if ($this->isTerseFormat($output)) {
            return $this->parseTerseFormat($output);
        }

        if ($this->isListFormat($output)) {
            return $this->parseListFormat($output);
        }

        // Default fallback (raw text)
        return ['raw_output' => explode("\n", $output)];
    }

    /**
     * Detect colon format (e.g. key: value)
     */
    protected function isColonFormat($output)
    {
        return preg_match('/^[\s\-a-zA-Z0-9]+:\s*.+$/m', $output);
    }

    protected function isTerseFormat($output)
    {
        // Detect terse format if any line contains a key=value pair (excluding command echoes)
        return preg_match('/^.*[^\s]=[^\s].*$/m', $output);
    }

    /**
     * Detect simple list format (e.g. one entry per line, no colons)
     */
    protected function isListFormat($output)
    {
        $lines = array_filter(explode("\n", $output));

        return count($lines) > 1 && ! preg_match('/[:=]/', $lines[0]);
    }

    /**
     * Parse key:value format
     */
    protected function parseColonFormat($output)
    {
        $lines = explode("\n", $output);
        $data = [];

        foreach ($lines as $line) {
            if (strpos($line, ':') !== false) {
                [$key, $value] = array_map('trim', explode(':', $line, 2));
                $data[$key] = $value;
            }
        }

        return [$data];
    }

    /**
     * Parse terse format (key=value pairs)
     */
    protected function parseTerseFormat($output)
    {
        $lines = preg_split('/\r\n|\r|\n/', trim($output));
        $entries = [];

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '') {
                continue;
            }

            $entry = [];

            // id + name + active status
            if (preg_match('/^\s*(\d+)\s*(?:"([^"]+)"|([^\s]+))?/', $line, $m)) {
                $entry['id'] = $m[1];
                $entry['name'] = $m[2] ?? $m[3] ?? null;
                $entry['status'] = strpos($line, 'X') !== false ? 'disable' : 'active';
            }

            // New regex - captures values with spaces as well
            preg_match_all('/([^\s=]+)=("([^"]*)"|[^\s"].*?(?=\s+[^\s=]+=|$))/', $line, $matches, PREG_SET_ORDER);

            foreach ($matches as $kv) {
                $k = $kv[1];
                $v = isset($kv[3]) && $kv[3] !== '' ? $kv[3] : trim($kv[2], '"');
                $entry[$k] = trim($v);
            }

            $entries[] = $entry;
        }

        return $entries;
    }

    /**
     * Parse list format (one value per line)
     */
    protected function parseListFormat($output)
    {
        return array_values(array_filter(array_map('trim', explode("\n", $output))));
    }
}
