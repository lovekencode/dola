<?php

declare(strict_types=1);

session_start();

$projectRoot = dirname(__DIR__, 1);
$autoloadPath = $projectRoot . '/../vendor/autoload.php';

if (file_exists($autoloadPath)) {
    require_once $autoloadPath;
}

if (!function_exists('env')) {
    require_once $projectRoot . '/../database/dbconnect.php';
}

header('Content-Type: application/json');

$user = $_SESSION['user'] ?? null;
$isAuthenticated = $_SESSION['is_authenticated'] ?? false;

if (!$isAuthenticated || !$user) {
    http_response_code(401);
    echo json_encode(['available' => false, 'message' => 'Non authentifié']);
    exit;
}

$username = trim((string) ($_GET['username'] ?? ''));

if ($username === '') {
    echo json_encode(['available' => false, 'message' => 'Username requis']);
    exit;
}

$pdo = getConnection();

try {
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM stores WHERE username = :username');
    $stmt->execute(['username' => $username]);
    $exists = (int) $stmt->fetchColumn() > 0;
    echo json_encode([
        'available' => !$exists,
        'message' => $exists ? 'Username déjà utilisé' : 'Username disponible',
    ]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['available' => false, 'message' => 'Erreur serveur']);
}

