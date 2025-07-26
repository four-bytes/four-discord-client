<?php

require_once '../vendor/autoload.php';

use Four\Discord\WebhookClient;

// Replace with your Discord webhook URL
$webhookUrl = 'https://discord.com/api/webhooks/YOUR_WEBHOOK_ID/YOUR_WEBHOOK_TOKEN';

// Create webhook client
$client = new WebhookClient($webhookUrl);

// Send simple message
$response = $client->sendMessage(
    content: 'Hello from four-discord-client!',
    username: 'Countdown Bot',
    avatarUrl: 'https://example.com/avatar.png'
);

if ($response->isSuccess()) {
    echo "Message sent successfully!\n";
    echo "Status: {$response->getStatusCode()}\n";
} else {
    echo "Failed to send message: {$response->getErrorMessage()}\n";
    echo "Status: {$response->getStatusCode()}\n";
}

// Send embed message
$embed = [
    'title' => 'Countdown Alert',
    'description' => 'Your countdown has reached 5 minutes!',
    'color' => 0xff6b35,
    'timestamp' => date('c'),
    'fields' => [
        [
            'name' => 'Time Remaining',
            'value' => '5 minutes',
            'inline' => true
        ],
        [
            'name' => 'Event',
            'value' => 'Important Meeting',
            'inline' => true
        ]
    ]
];

$response = $client->sendEmbed(
    embed: $embed,
    content: '@everyone Countdown reminder!',
    username: 'Countdown Bot'
);

if ($response->isSuccess()) {
    echo "Embed sent successfully!\n";
} else {
    echo "Failed to send embed: {$response->getErrorMessage()}\n";
}