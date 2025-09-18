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
require_once $projectRoot . '/database/userSchema.php';
require_once $projectRoot . '/database/storeSchema.php';

try {
    if (!($_SESSION['is_authenticated'] ?? false) || !isset($_SESSION['user']['id'])) {
        http_response_code(401);
        echo json_encode(['ok' => false, 'error' => 'Non autorisé']);
        exit;
    }

    $pdo = getConnection();
    // Ensure tables exist
    createUserSchema($pdo);
    createStoreSchema($pdo);

    $userId = (int) $_SESSION['user']['id']; // stores.user_id references users.id

    // Check if user already has a store
    $check = $pdo->prepare('SELECT id FROM stores WHERE user_id = :uid LIMIT 1');
    $check->execute(['uid' => $userId]);
    if ($check->fetch()) {
        echo json_encode(['ok' => false, 'error' => 'Vous avez déjà une boutique']);
        exit;
    }

    // Read and validate input
    $name = isset($_POST['name']) ? trim((string) $_POST['name']) : '';
    $username = isset($_POST['username']) ? strtolower(trim((string) $_POST['username'])) : '';
    $description = isset($_POST['description']) ? trim((string) $_POST['description']) : null;

    if ($name === '' || $username === '') {
        echo json_encode(['ok' => false, 'error' => 'Nom et nom d\'utilisateur sont requis']);
        exit;
    }

    if (mb_strlen($name) > 25) {
        echo json_encode(['ok' => false, 'error' => 'Le nom doit faire 25 caractères maximum']);
        exit;
    }

    if (strlen($username) > 20) {
        echo json_encode(['ok' => false, 'error' => 'Le nom d\'utilisateur doit faire 20 caractères maximum']);
        exit;
    }

    if (!preg_match('/^[a-z0-9-]+$/', $username)) {
        echo json_encode(['ok' => false, 'error' => 'Utilisez uniquement des lettres, chiffres et tirets (a-z, 0-9, -)']);
        exit;
    }

    // Uniqueness
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM stores WHERE username = :u');
    $stmt->execute(['u' => $username]);
    if ((int) ($stmt->fetchColumn() ?? 0) > 0) {
        echo json_encode(['ok' => false, 'error' => 'Ce nom d\'utilisateur est déjà pris']);
        exit;
    }

    $insert = $pdo->prepare('INSERT INTO stores (user_id, username, name, description) VALUES (:uid, :uname, :name, :desc)');
    $insert->execute([
        'uid' => $userId,
        'uname' => $username,
        'name' => $name,
        'desc' => $description,
    ]);

    $storeId = (int) $pdo->lastInsertId();

    echo json_encode([
        'ok' => true,
        'store' => [
            'id' => $storeId,
            'user_id' => $userId,
            'username' => $username,
            'name' => $name,
            'description' => $description,
        ],
    ]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'Erreur serveur']);
}
