# Four Discord Client

A PHP library for sending messages to Discord webhooks using PSR-18.

## Features

- **PSR-18 Compatible** — Works with any PSR-18 HTTP client
- **Synchronous** — No async/await complexity
- **Simple API** — Easy to use webhook messaging
- **Rich Embeds** — Support for Discord embed messages
- **Rate Limiting** — Built-in rate limit header parsing
- **Lightweight** — Minimal dependencies

## Installation

```bash
composer require four-bytes/four-discord-client
```

This package automatically resolves PSR-18 clients via `php-http/discovery`.

## Quick Start

```php
<?php

use Four\Discord\WebhookClient;

// Factory method (recommended) — auto-discovers PSR-18 client
$client = WebhookClient::create('https://discord.com/api/webhooks/YOUR_WEBHOOK_ID/YOUR_WEBHOOK_TOKEN');

// Send simple message
$response = $client->sendMessage(
    content: 'Hello Discord!',
    username: 'My Bot',
    avatarUrl: 'https://example.com/avatar.png'
);

if ($response->isSuccess()) {
    echo "Message sent!";
} else {
    echo "Error: " . $response->getErrorMessage();
}
```

## Usage

### Basic Messages

```php
$response = $client->sendMessage(
    content: 'Your countdown has 5 minutes remaining!',
    username: 'Countdown Bot',           // Optional
    avatarUrl: 'https://example.com/bot.png'  // Optional
);
```

### Embed Messages

```php
$embed = [
    'title' => 'Countdown Alert',
    'description' => 'Important event starting soon!',
    'color' => 0xff6b35,
    'timestamp' => date('c'),
    'fields' => [
        [
            'name' => 'Time Remaining',
            'value' => '5 minutes',
            'inline' => true
        ]
    ]
];

$response = $client->sendEmbed(
    embed: $embed,
    content: '@everyone Reminder!'  // Optional
);
```

### Error Handling

```php
$response = $client->sendMessage('Hello!');

if (!$response->isSuccess()) {
    echo "Status Code: " . $response->getStatusCode() . "\n";
    echo "Error: " . $response->getErrorMessage() . "\n";
    
    // Check rate limiting
    if ($response->getRateLimitRemaining() !== null) {
        echo "Rate limit remaining: " . $response->getRateLimitRemaining() . "\n";
        echo "Rate limit resets at: " . date('c', $response->getRateLimitReset()) . "\n";
    }
}
```

### Rate Limit Headers

The response object provides access to Discord's rate limit headers:

```php
$response = $client->sendMessage('Hello!');

// Get rate limit info
$remaining = $response->getRateLimitRemaining();  // Requests left
$reset = $response->getRateLimitReset();          // Unix timestamp when limit resets
```

## Discord Webhook Setup

1. Go to your Discord server settings
2. Navigate to **Integrations** → **Webhooks**
3. Click **New Webhook**
4. Configure name and channel
5. Copy the **Webhook URL**

## API Reference

### WebhookClient

#### Factory Method

```php
public static function create(string $webhookUrl): self
```

Create a client with auto-discovered PSR-18 client. Recommended for most use cases.

#### Constructor

```php
public function __construct(
    string $url,
    ?ClientInterface $psrClient = null,
    ?RequestFactoryInterface $requestFactory = null,
    ?StreamFactoryInterface $streamFactory = null
)
```

Create a client with dependency injection. Use this if you need to provide a specific PSR-18 implementation.

#### sendMessage

```php
public function sendMessage(
    string $content,
    ?string $username = null,
    ?string $avatarUrl = null
): WebhookResponse
```

Send a simple text message.

#### sendEmbed

```php
public function sendEmbed(
    array $embed,
    ?string $content = null,
    ?string $username = null,
    ?string $avatarUrl = null
): WebhookResponse
```

Send an embed message.

#### getWebhookUrl

```php
public function getWebhookUrl(): string
```

Get the configured webhook URL.

### WebhookResponse

#### isSuccess

```php
public function isSuccess(): bool
```

Check if the request was successful (2xx status code).

#### getStatusCode

```php
public function getStatusCode(): int
```

Get HTTP status code.

#### getErrorMessage

```php
public function getErrorMessage(): ?string
```

Get error message if request failed.

#### getResponseBody

```php
public function getResponseBody(): ?string
```

Get raw response body.

#### getRateLimitRemaining

```php
public function getRateLimitRemaining(): ?int
```

Get remaining rate limit count from `X-RateLimit-Remaining` header.

#### getRateLimitReset

```php
public function getRateLimitReset(): ?int
```

Get rate limit reset timestamp from `X-RateLimit-Reset` header.

## Requirements

- PHP 8.1+
- `four-bytes/four-http-client ^4.0`
- PSR-18 HTTP client (auto-discovered via `php-http/discovery`)
- PSR-17 request and stream factories

## License

MIT
