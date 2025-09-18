<?php

declare(strict_types=1);

header('Content-Type: application/json');
header('Cache-Control: no-store');

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'GET') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Méthode non autorisée']);
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
    $pdo = getConnection();
    // Ensure stores table exists
    if (function_exists('createStoreSchema')) {
        createStoreSchema($pdo);
    }

    $username = isset($_GET['username']) ? trim((string) $_GET['username']) : '';
    $exclude  = isset($_GET['exclude']) ? strtolower(trim((string) $_GET['exclude'])) : '';

    if ($username === '') {
        echo json_encode(['ok' => true, 'available' => false, 'message' => 'Nom d\'utilisateur requis']);
        exit;
    }

    // Normalize username: lowercase, only letters, numbers, hyphens
    $normalized = strtolower($username);

    if (strlen($normalized) > 20) {
        echo json_encode(['ok' => true, 'available' => false, 'message' => 'Le nom d\'utilisateur doit faire 20 caractères maximum']);
        exit;
    }

    if (!preg_match('/^[a-z0-9-]+$/', $normalized)) {
        echo json_encode(['ok' => true, 'available' => false, 'message' => 'Utilisez uniquement des lettres, chiffres et tirets (a-z, 0-9, -)']);
        exit;
    }

    if ($exclude && $exclude === $normalized) {
        $exists = false; // editing and not changing username
    } else {
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM stores WHERE username = :u');
        $stmt->execute(['u' => $normalized]);
        $exists = (int) ($stmt->fetchColumn() ?? 0) > 0;
    }

    echo json_encode([
        'ok' => true,
        'available' => !$exists,
        'username' => $normalized,
        'message' => $exists ? 'Ce nom d\'utilisateur est déjà pris' : 'Ce nom d\'utilisateur est disponible',
    ]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'Erreur serveur']);
}
