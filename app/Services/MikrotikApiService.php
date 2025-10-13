<?php

namespace App\Services;

use RouterOS\Client;
use RouterOS\Config;
use RouterOS\Query;
use Exception;

class MikrotikApiService
{
    protected $client;

    public function __construct($host, $port, $username, $password)
    {
        try {
            $config = new Config([
                'host' => $host,
                'user' => $username,
                'pass' => $password,
                'port' => $port ?? 8728, // default MikroTik API port
                'timeout' => 5,          // prevent hanging
                'attempts' => 1,         // single retry
            ]);

            $this->client = new Client($config);
        } catch (Exception $e) {
            throw new Exception('Failed to connect to MikroTik: ' . $e->getMessage());
        }
    }

    /**
     * Execute a MikroTik API command
     *
     * @param string $command  The RouterOS command (e.g., '/system/resource/print')
     * @param array  $params   Optional command parameters
     * @return array|string
     */
    public function executeCommand(string $command, array $params = [])
    {
        try {
            $query = new Query($command);

            // If parameters are provided, add them to the query
            foreach ($params as $key => $value) {
                $query->equal($key, $value);
            }

            $response = $this->client->query($query)->read();

            return $response;
        } catch (Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }
}
