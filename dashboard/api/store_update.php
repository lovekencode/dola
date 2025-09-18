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
    createUserSchema($pdo);
    createStoreSchema($pdo);

    $userId = (int) $_SESSION['user']['id'];

    // Load current store
    $stmt = $pdo->prepare('SELECT id, username FROM stores WHERE user_id = :uid LIMIT 1');
    $stmt->execute(['uid' => $userId]);
    $store = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$store) {
        echo json_encode(['ok' => false, 'error' => 'Aucune boutique trouvée']);
        exit;
    }

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

    if ($username !== strtolower((string) $store['username'])) {
        $dup = $pdo->prepare('SELECT COUNT(*) FROM stores WHERE username = :u');
        $dup->execute(['u' => $username]);
        if ((int) ($dup->fetchColumn() ?? 0) > 0) {
            echo json_encode(['ok' => false, 'error' => 'Ce nom d\'utilisateur est déjà pris']);
            exit;
        }
    }

    $upd = $pdo->prepare('UPDATE stores SET name = :name, username = :uname, description = :desc WHERE user_id = :uid LIMIT 1');
    $upd->execute([
        'name' => $name,
        'uname' => $username,
        'desc' => $description,
        'uid' => $userId,
    ]);

    echo json_encode([
        'ok' => true,
        'store' => [
            'id' => (int) $store['id'],
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
