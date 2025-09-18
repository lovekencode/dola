<?php

declare(strict_types=1);

header('Content-Type: application/json');
header('Cache-Control: no-store');
session_start();

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Méthode non autorisée']);
    exit;
}

// Simple CSRF mitigation: require AJAX header
if (strcasecmp($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '', 'XMLHttpRequest') !== 0) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Requête invalide']);
    exit;
}

$projectRoot = dirname(__DIR__, 2);
$autoloadPath = $projectRoot . '/vendor/autoload.php';

if (file_exists($autoloadPath)) {
    require_once $autoloadPath;
}

require_once $projectRoot . '/database/dbconnect.php';
require_once $projectRoot . '/database/storeSchema.php';

try {
    if (!($_SESSION['is_authenticated'] ?? false) || !isset($_SESSION['user']['id'])) {
        http_response_code(401);
        echo json_encode(['ok' => false, 'error' => 'Non autorisé']);
        exit;
    }

    $pdo = getConnection();
    createStoreSchema($pdo);

    $userId = (int) $_SESSION['user']['id'];

    $del = $pdo->prepare('DELETE FROM stores WHERE user_id = :uid LIMIT 1');
    $del->execute(['uid' => $userId]);

    echo json_encode(['ok' => true]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'Erreur serveur']);
}
