<?php

require_once __DIR__ . '/dbconnect.php';
require_once __DIR__ . '/userSchema.php';
require_once __DIR__ . '/storeSchema.php';

/**
 * Execute all schema/migration routines for the application.
 */
function runSchemas(): void
{
    $pdo = getConnection();

    createUserSchema($pdo);
    createStoreSchema($pdo);
}

if (PHP_SAPI === 'cli' && basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'])) {
    runSchemas();
    echo "Schemas executed successfully." . PHP_EOL;
}
