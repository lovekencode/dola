<?php

declare(strict_types=1);

header('Content-Type: text/plain; charset=utf-8');

$projectRoot = dirname(__DIR__);
$autoloadPath = $projectRoot . '/vendor/autoload.php';

if (!file_exists($autoloadPath)) {
    echo "vendor/autoload.php introuvable. Exécutez 'composer install' à la racine (" . $projectRoot . ") pour installer les dépendances." . PHP_EOL;
    exit(1);
}

require_once $autoloadPath;
require_once __DIR__ . '/dbconnect.php';

if (class_exists(Dotenv\Dotenv::class)) {
    Dotenv\Dotenv::createMutable($projectRoot)->safeLoad();
}

$envKeys = [
    'DB_HOST',
    'DB_PORT',
    'DB_NAME',
    'DB_USER',
    'DB_PASSWORD',
    'DB_SOCKET',
    'DB_DSN',
    'GOOGLE_CLIENT_ID',
    'GOOGLE_CLIENT_SECRET',
    'GOOGLE_REDIRECT_URI',
];

echo "Variables d'environnement :" . PHP_EOL;
foreach ($envKeys as $key) {
    $value = env($key);
    if (in_array($key, ['DB_PASSWORD', 'GOOGLE_CLIENT_SECRET'], true)) {
        $value = $value !== null ? '[définie]' : '[non définie]';
    } elseif ($value === null) {
        $value = '[non définie]';
    }
    echo sprintf("- %s = %s", $key, $value ?? '[non définie]') . PHP_EOL;
}

echo PHP_EOL;

echo 'Test de connexion PDO : ';
try {
    $pdo = getConnection();
    $pdo->query('SELECT 1');
    echo 'OK' . PHP_EOL;
} catch (Throwable $e) {
    echo 'ECHEC (' . $e->getMessage() . ')' . PHP_EOL;
}
