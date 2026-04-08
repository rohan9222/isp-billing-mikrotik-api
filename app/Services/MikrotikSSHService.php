<?php

namespace App\Services;

use Exception;
use phpseclib3\Net\SSH2;

class MikrotikSSHService
{
    protected SSH2 $ssh;

    public function __construct(string $host, int $port, string $username, string $password)
    {
        $this->ssh = new SSH2($host, $port);

        if (! $this->ssh->login($username, $password)) {
            throw new Exception("SSH login failed for {$host}");
        }
    }

    /**
     * Execute a raw SSH command and return the raw output string.
     */
    public function executeCommand(string $command): string
    {
        try {
            return (string) $this->ssh->exec($command);
        } catch (\RuntimeException $e) {
            return 'Error: '.$e->getMessage();
        }
    }

    /**
     * Execute a RouterOS command that requires an interactive PTY session.
     *
     * Some RouterOS commands (notably /system backup save) are silently ignored
     * when sent via SSH exec without a pseudo-terminal. This method allocates a
     * PTY, waits for the interactive shell prompt (consuming the login banner),
     * sends the command, waits for the router to finish, then reads the response.
     * 
     * @throws \Exception on SSH failure
     */
    public function executePtyCommand(string $command, int $waitSeconds = 5): string
    {
        $this->ssh->enablePTY();

        // Wait for the RouterOS prompt, consuming the full login banner.
        // Without a regex here, banner log lines bleed into the command output.
        $this->ssh->read('/\[.+\@.+\]\s*>/');

        // Send the command
        $this->ssh->write($command."\n");

        // Give RouterOS time to execute (e.g. write backup file to flash)
        sleep($waitSeconds);

        // Read until the next RouterOS prompt — this is the command's own output
        $output = $this->ssh->read('/\[.+\@.+\]\s*>/');

        $this->ssh->write("/quit\n");

        return $output ?? '';
    }

    /**
     * Execute any MikroTik SSH command and always return a structured array.
     * Auto-detects colon, terse (key=value), or list format.
     */
    public function executeCommandParsable(string $command): array
    {
        $output = trim($this->ssh->exec($command));

        if (empty($output)) {
            return [];
        }

        if ($this->isColonFormat($output)) {
            return $this->parseColonFormat($output);
        }

        if ($this->isTerseFormat($output)) {
            return $this->parseTerseFormat($output);
        }

        return $this->parseListFormat($output);
    }

    // ─── Format Detectors ─────────────────────────────────────────────────────

    protected function isColonFormat(string $output): bool
    {
        return (bool) preg_match('/^\s*[a-zA-Z][a-zA-Z0-9\-]*:\s*.+$/m', $output);
    }

    protected function isTerseFormat(string $output): bool
    {
        return (bool) preg_match('/^\s*\d+\s+.*[^\s]=[^\s].*$/m', $output);
    }



    // ─── Format Parsers ───────────────────────────────────────────────────────

    protected function parseColonFormat(string $output): array
    {
        $data = [];
        foreach (explode("\n", $output) as $line) {
            if (str_contains($line, ':')) {
                [$key, $value] = array_map('trim', explode(':', $line, 2));
                $data[$key] = $value;
            }
        }

        return [$data];
    }

    protected function parseTerseFormat(string $output): array
    {
        $entries = [];
        foreach (preg_split('/\r\n|\r|\n/', trim($output)) as $line) {
            $line = trim($line);
            if ($line === '') {
                continue;
            }

            $entry = [];

            if (preg_match('/^\s*(\d+)\s*(?:"([^"]+)"|([^\s]+))?/', $line, $m)) {
                $entry['id'] = $m[1];
                $entry['name'] = $m[2] ?? $m[3] ?? null;
                $entry['status'] = str_contains($line, 'X') ? 'disable' : 'active';
            }

            preg_match_all('/([^\s=]+)=("([^"]*)"|[^\s"].*?(?=\s+[^\s=]+=|$))/', $line, $matches, PREG_SET_ORDER);
            foreach ($matches as $kv) {
                $entry[$kv[1]] = isset($kv[3]) && $kv[3] !== '' ? $kv[3] : trim($kv[2], '"');
            }

            $entries[] = $entry;
        }

        return $entries;
    }

    protected function parseListFormat(string $output): array
    {
        return array_values(array_filter(array_map('trim', explode("\n", $output))));
    }
}
