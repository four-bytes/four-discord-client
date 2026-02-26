<?php

declare(strict_types=1);

namespace Four\Discord\Tests\Unit;

use Four\Discord\WebhookClient;
use Four\Discord\WebhookResponse;
use Http\Mock\Client as MockClient;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use PHPUnit\Framework\TestCase;

class WebhookClientTest extends TestCase
{
    private MockClient $mockHttpClient;
    private Psr17Factory $psr17Factory;
    private WebhookClient $client;

    protected function setUp(): void
    {
        $this->mockHttpClient = new MockClient();
        $this->psr17Factory = new Psr17Factory();
        $this->client = new WebhookClient(
            'https://discord.com/api/webhooks/123/token',
            $this->mockHttpClient,
            $this->psr17Factory,
            $this->psr17Factory,
        );
    }

    public function testSendMessageSuccess(): void
    {
        $this->mockHttpClient->addResponse(new Response(204));
        $result = $this->client->sendMessage('Hello World');
        $this->assertInstanceOf(WebhookResponse::class, $result);
        $this->assertTrue($result->isSuccess());
        $this->assertSame(204, $result->getStatusCode());
    }

    public function testSendMessageWithUsername(): void
    {
        $this->mockHttpClient->addResponse(new Response(204));
        $result = $this->client->sendMessage('Hello', 'Bot', 'https://example.com/avatar.png');
        $this->assertTrue($result->isSuccess());
    }

    public function testSendEmbedSuccess(): void
    {
        $this->mockHttpClient->addResponse(new Response(204));
        $embed = ['title' => 'Test', 'description' => 'Test embed'];
        $result = $this->client->sendEmbed($embed);
        $this->assertTrue($result->isSuccess());
    }

    public function testSendMessageFailure(): void
    {
        $this->mockHttpClient->addResponse(new Response(429));
        $result = $this->client->sendMessage('Hello');
        $this->assertFalse($result->isSuccess());
        $this->assertSame(429, $result->getStatusCode());
    }

    public function testGetWebhookUrl(): void
    {
        $this->assertSame(
            'https://discord.com/api/webhooks/123/token',
            $this->client->getWebhookUrl()
        );
    }
}
