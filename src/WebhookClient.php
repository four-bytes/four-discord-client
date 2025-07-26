<?php

declare(strict_types=1);

namespace Four\Discord;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;

class WebhookClient
{
    private Client $httpClient;
    private string $webhookUrl;

    public function __construct(string $webhookUrl, ?Client $httpClient = null)
    {
        $this->webhookUrl = $webhookUrl;
        $this->httpClient = $httpClient ?? new Client([
            'timeout' => 10,
            'headers' => [
                'User-Agent' => 'four-discord-client/1.0',
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    public function sendMessage(string $content, ?string $username = null, ?string $avatarUrl = null): WebhookResponse
    {
        $payload = ['content' => $content];
        
        if ($username !== null) {
            $payload['username'] = $username;
        }
        
        if ($avatarUrl !== null) {
            $payload['avatar_url'] = $avatarUrl;
        }

        return $this->sendPayload($payload);
    }

    public function sendEmbed(array $embed, ?string $content = null, ?string $username = null, ?string $avatarUrl = null): WebhookResponse
    {
        $payload = ['embeds' => [$embed]];
        
        if ($content !== null) {
            $payload['content'] = $content;
        }
        
        if ($username !== null) {
            $payload['username'] = $username;
        }
        
        if ($avatarUrl !== null) {
            $payload['avatar_url'] = $avatarUrl;
        }

        return $this->sendPayload($payload);
    }

    private function sendPayload(array $payload): WebhookResponse
    {
        try {
            $response = $this->httpClient->post($this->webhookUrl, [
                'json' => $payload,
            ]);

            return new WebhookResponse(true, $response->getStatusCode(), null, $response);
        } catch (RequestException $e) {
            $statusCode = $e->getResponse() ? $e->getResponse()->getStatusCode() : 0;
            return new WebhookResponse(false, $statusCode, $e->getMessage(), $e->getResponse());
        }
    }

    public function getWebhookUrl(): string
    {
        return $this->webhookUrl;
    }
}