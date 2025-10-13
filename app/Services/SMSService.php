<?php

namespace App\Services;

use GuzzleHttp\Client;

class SMSService
{
    protected $client;

    protected $apiKey;

    protected $apiUrl;

    protected $senderId;

    public function __construct()
    {
        $this->client = new Client;
        $this->apiKey = config('services.smsservice.api_key');
        $this->senderId = config('services.smsservice.sender_id');
        $this->baseUrl = config('services.smsservice.base_url');
    }

    public function profile($uid)
    {
        try {
            $response = $this->client->get("$this->baseUrl"."$uid", [
                'headers' => [
                    'Authorization' => "Bearer {$this->apiKey}",
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
        }
    }

    public function sendSMS($recipient, $message)
    {
        try {
            $response = $this->client->post("$this->baseUrl"."sms/send", [
                'headers' => [
                    'Authorization' => "Bearer {$this->apiKey}",
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'json' => [
                    'recipient' => $recipient,
                    'sender_id' => $this->senderId,
                    'type' => 'plain',
                    'message' => $message,
                ],
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
        }
    }

    public function customizeMessage(array $data, string $template)
    {
        foreach ($data as $key => $value) {
            $template = str_replace('{'.$key.'}', $value, $template);
        }

        return $template;
    }
}
