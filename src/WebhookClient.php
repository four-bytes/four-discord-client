<?php

declare(strict_types=1);

namespace Four\Discord;

use Four\Http\Configuration\ClientConfig;
use Four\Http\Factory\HttpClientFactory;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

class WebhookClient
{
    public function __construct(
        private readonly string $webhookUrl,
        private readonly ClientInterface $httpClient,
        private readonly RequestFactoryInterface $requestFactory,
        private readonly StreamFactoryInterface $streamFactory,
    ) {}

    public static function create(string $webhookUrl): self
    {
        $config = new ClientConfig(
            baseUri: '',
            defaultHeaders: [
                'User-Agent' => 'four-discord-client/2.0',
            ],
            timeout: 10,
        );

        $factory = new HttpClientFactory();
        $psrClient = $factory->create($config);

        $psr17Factory = new Psr17Factory();

        return new self($webhookUrl, $psrClient, $psr17Factory, $psr17Factory);
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

    /** @param array<string, mixed> $embed */
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

    /** @param array<string, mixed> $payload */
    private function sendPayload(array $payload): WebhookResponse
    {
        $body = json_encode($payload, JSON_THROW_ON_ERROR);
        $stream = $this->streamFactory->createStream($body);

        $request = $this->requestFactory->createRequest('POST', $this->webhookUrl)
            ->withHeader('Content-Type', 'application/json')
            ->withHeader('User-Agent', 'four-discord-client/2.0')
            ->withBody($stream);

        try {
            $response = $this->httpClient->sendRequest($request);
            $statusCode = $response->getStatusCode();
            $success = $statusCode >= 200 && $statusCode < 300;
            return new WebhookResponse($success, $statusCode, null, $response);
        } catch (ClientExceptionInterface $e) {
            return new WebhookResponse(false, 0, $e->getMessage(), null);
        }
    }

    public function getWebhookUrl(): string
    {
        return $this->webhookUrl;
    }
}
