<?php

declare(strict_types=1);

session_start();

$projectRoot = dirname(__DIR__);
$autoloadPath = $projectRoot . '/vendor/autoload.php';

if (!file_exists($autoloadPath)) {
    http_response_code(500);
    // echo 'Dependencies missing. Run "composer install" from the project root.';
    exit;
}

require_once $autoloadPath;
require_once __DIR__ . '/../database/dbconnect.php';
require_once __DIR__ . '/../database/userSchema.php';
require_once __DIR__ . '/../Notifications/TelegramNotifications.php';

if (class_exists(Dotenv\Dotenv::class)) {
    $dotenv = Dotenv\Dotenv::createMutable($projectRoot);
    $dotenv->safeLoad();
}

if (!class_exists(Google\Client::class)) {
    http_response_code(500);
    // echo 'Google API client library is not installed. Run "composer install" to continue.';
    exit;
}

$expectedState = $_SESSION['oauth2state'] ?? null;
if (!$expectedState || !isset($_GET['state']) || hash_equals($expectedState, (string) $_GET['state']) === false) {
    unset($_SESSION['oauth2state']);
    http_response_code(400);
    // echo 'Invalid OAuth state. Please try signing in again.';
    exit;
}
unset($_SESSION['oauth2state']);

if (!isset($_GET['code'])) {
    http_response_code(400);
    // echo 'Missing authorization code.';
    exit;
}

$clientId = env('GOOGLE_CLIENT_ID');
$clientSecret = env('GOOGLE_CLIENT_SECRET');
$redirectUri = env('GOOGLE_REDIRECT_URI') ?: sprintf('%s://%s/auth/callback.php',
    isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http',
    $_SERVER['HTTP_HOST'] ?? 'localhost'
);

$client = new Google\Client();
$client->setClientId($clientId);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);

$token = $client->fetchAccessTokenWithAuthCode((string) $_GET['code']);
if (isset($token['error'])) {
    http_response_code(400);
    // echo 'Failed to authenticate with Google: ' . htmlspecialchars((string) $token['error'], ENT_QUOTES, 'UTF-8');
    exit;
}
$client->setAccessToken($token);

$oauth2 = new Google\Service\Oauth2($client);
$userInfo = $oauth2->userinfo->get();

$googleId = $userInfo->getId();
$email = $userInfo->getEmail();
$firstName = $userInfo->getGivenName();
$lastName = $userInfo->getFamilyName();

if (!$googleId || !$email) {
    http_response_code(400);
    // echo 'Could not retrieve required profile information from Google.';
    exit;
}

$pdo = getConnection();
// createUserSchema($pdo);
// if (function_exists('createStoreSchema')) {
//     createStoreSchema($pdo);
// }

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare('SELECT * FROM users WHERE google_id = :gid LIMIT 1');
    $stmt->execute(['gid' => $googleId]);
    $user = $stmt->fetch();

    if (!$user) {
        $userId = bin2hex(random_bytes(16));
        $insert = $pdo->prepare('INSERT INTO users (user_id, google_id, first_name, last_name, email) VALUES (:uid, :gid, :first, :last, :email)');
        $insert->execute([
            'uid' => $userId,
            'gid' => $googleId,
            'first' => $firstName,
            'last' => $lastName,
            'email' => $email,
        ]);

        $user = [
            'id' => (int) $pdo->lastInsertId(),
            'user_id' => $userId,
            'google_id' => $googleId,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $countStmt = $pdo->query('SELECT COUNT(*) FROM users');
        $totalUsers = (int) ($countStmt->fetchColumn() ?? 1);

        $notifMessage = sprintf(
            "🎉 Nouveau utilisateur inscrit !\n\nNom : %s %s\nEmail : %s\n👥 Total utilisateurs : %d",
            $firstName ?? '',
            $lastName ?? '',
            $email,
            $totalUsers
        );
        if (function_exists('newUserNotif')) {
            newUserNotif($notifMessage);
        }
    } else {
        $update = $pdo->prepare('UPDATE users SET first_name = :first, last_name = :last, email = :email WHERE id = :id');
        $update->execute([
            'first' => $firstName,
            'last' => $lastName,
            'email' => $email,
            'id' => $user['id'],
        ]);

        $user['first_name'] = $firstName;
        $user['last_name'] = $lastName;
        $user['email'] = $email;
    }

    $pdo->commit();
} catch (Throwable $e) {
    $pdo->rollBack();
    http_response_code(500);
    // echo 'Could not save user profile: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
    exit;
}

$_SESSION['user'] = [
    'id' => (int) $user['id'],
    'user_id' => $user['user_id'] ?? null,
    'google_id' => $user['google_id'],
    'first_name' => $user['first_name'],
    'last_name' => $user['last_name'],
    'email' => $user['email'],
];

$_SESSION['is_authenticated'] = true;

header('Location: /dashboard/index.php');
exit;
