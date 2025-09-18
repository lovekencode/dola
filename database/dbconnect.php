<?php

if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
}

if (!function_exists('env')) {
    function env(string $key, mixed $default = null): mixed
    {
        $value = $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key);
        if ($value === false || $value === null || $value === '') {
            return $default;
        }
        return $value;
    }
}

/**
 * Get a shared PDO connection instance.
 */
if (!function_exists('getConnection')) {
    function getConnection(): PDO
    {
        static $pdo;
        static $envLoaded = false;

        if ($pdo instanceof PDO) {
            return $pdo;
        }

        if (!$envLoaded && class_exists('Dotenv\\Dotenv')) {
            $dotenv = Dotenv\Dotenv::createMutable(__DIR__ . '/..');
            $dotenv->safeLoad();
            $envLoaded = true;
        }

        $dsn = env('DB_DSN');

        if (!$dsn) {
            $host = env('DB_HOST', 'localhost');
            $port = env('DB_PORT', '3306');
            $dbname = env('DB_NAME', 'dola');
            $charset = env('DB_CHARSET', 'utf8mb4');
            $socket = env('DB_SOCKET');

            if ($socket) {
                $dsn = sprintf('mysql:unix_socket=%s;dbname=%s;charset=%s', $socket, $dbname, $charset);
            } else {
                $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=%s', $host, $port, $dbname, $charset);
            }
        }

        $username = env('DB_USER', 'root');
        $password = env('DB_PASSWORD', '');

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        $pdo = new PDO($dsn, $username, $password, $options);

        return $pdo;
    }
}
