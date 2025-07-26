# Four Discord Client

A synchronous Discord webhook client for PHP using GuzzleHTTP.

## Features

- 🚀 **Synchronous** - No async/await complexity
- 📝 **Simple API** - Easy to use webhook messaging
- 🎨 **Rich Embeds** - Support for Discord embed messages
- 🔄 **Rate Limiting** - Built-in rate limit header parsing
- ⚡ **Lightweight** - Minimal dependencies

## Installation

```bash
composer require four-bytes/four-discord-client
```

## Quick Start

```php
<?php

use Four\Discord\WebhookClient;

$client = new WebhookClient('https://discord.com/api/webhooks/YOUR_WEBHOOK_ID/YOUR_WEBHOOK_TOKEN');

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

## Discord Webhook Setup

1. Go to your Discord server settings
2. Navigate to **Integrations** → **Webhooks**
3. Click **Create Webhook**
4. Configure name and channel
5. Copy the **Webhook URL**

## API Reference

### WebhookClient

#### `__construct(string $webhookUrl, ?Client $httpClient = null)`

Create a new webhook client.

#### `sendMessage(string $content, ?string $username = null, ?string $avatarUrl = null): WebhookResponse`

Send a simple text message.

#### `sendEmbed(array $embed, ?string $content = null, ?string $username = null, ?string $avatarUrl = null): WebhookResponse`

Send an embed message.

### WebhookResponse

#### `isSuccess(): bool`

Check if the request was successful.

#### `getStatusCode(): int`

Get HTTP status code.

#### `getErrorMessage(): ?string`

Get error message if request failed.

#### `getRateLimitRemaining(): ?int`

Get remaining rate limit count.

#### `getRateLimitReset(): ?int`

Get rate limit reset timestamp.

## Requirements

- PHP 8.0+
- GuzzleHTTP 7.0+

## License

MIT

## Author

4 Bytes - info@4bytes.de