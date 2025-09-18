<?php

declare(strict_types=1);

// Ensure we have the env function available first
$projectRoot = __DIR__ . '/..';
$autoloadPath = $projectRoot . '/vendor/autoload.php';

if (file_exists($autoloadPath)) {
    require_once $autoloadPath;
}

// Make sure env function is loaded before we define our function
if (!function_exists('env')) {
    require_once $projectRoot . '/database/dbconnect.php';
}

// Load environment variables
if (class_exists(Dotenv\Dotenv::class)) {
    Dotenv\Dotenv::createMutable($projectRoot)->safeLoad();
}

/**
 * Send a notification to a Telegram chat when a new user joins.
 */
function newUserNotif(string $message): bool
{
    $token = env('NEW_USER_BOT_TOKEN');
    $chatId = env('TELEGRAM_CHAT_ID');

    if (!$token || !$chatId) {
        return false;
    }

    $endpoint = sprintf('https://api.telegram.org/bot%s/sendMessage', $token);
    $payload = http_build_query([
        'chat_id' => $chatId,
        'text' => $message,
        'parse_mode' => 'HTML',
        'disable_web_page_preview' => true,
    ]);

    $context = stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
            'content' => $payload,
            'timeout' => 5,
        ],
    ]);

    $response = @file_get_contents($endpoint, false, $context);

    if ($response === false) {
        return false;
    }

    $data = json_decode($response, true);

    return isset($data['ok']) ? (bool) $data['ok'] : false;
}
