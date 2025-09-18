<?php

declare(strict_types=1);

session_start();

$projectRoot = dirname(__DIR__);
$autoloadPath = $projectRoot . '/vendor/autoload.php';

if (!file_exists($autoloadPath)) {
    http_response_code(500);
 
    exit;
}

require_once $autoloadPath;

if (class_exists(Dotenv\Dotenv::class)) {
    $dotenv = Dotenv\Dotenv::createMutable($projectRoot);
    $dotenv->safeLoad();
}

// if (isset($_GET['debug']) && $_GET['debug'] === 'env') {
//     header('Content-Type: text/plain; charset=utf-8');
   
//     exit;
// }

if (!class_exists(Google\Client::class)) {
    http_response_code(500);

    exit;
}

$clientId = env('GOOGLE_CLIENT_ID');
$clientSecret = env('GOOGLE_CLIENT_SECRET');
$redirectUri = env('GOOGLE_REDIRECT_URI') ?: sprintf('%s://%s/auth/callback.php',
    isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http',
    $_SERVER['HTTP_HOST'] ?? 'localhost'
);

if (!$clientId || !$clientSecret) {
    http_response_code(500);
    exit;
}

$client = new Google\Client();
$client->setClientId($clientId);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->setAccessType('offline');
$client->setPrompt('select_account consent');
$client->setIncludeGrantedScopes(true);
$client->addScope(['email', 'profile']);

$state = bin2hex(random_bytes(16));
$_SESSION['oauth2state'] = $state;
$client->setState($state);

$authUrl = $client->createAuthUrl();

header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
exit;
