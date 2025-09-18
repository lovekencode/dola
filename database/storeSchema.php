<?php

declare(strict_types=1);

/**
 * Create the stores table if it does not already exist.
 */
function createStoreSchema(PDO $pdo): void
{
    $sql = <<<SQL
CREATE TABLE IF NOT EXISTS stores (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    username VARCHAR(80) NOT NULL,
    name VARCHAR(150) NOT NULL,
    description TEXT DEFAULT NULL,
    product_ids TEXT DEFAULT NULL,
    sales_count INT UNSIGNED NOT NULL DEFAULT 0,
    is_pro_store TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uniq_store_username (username),
    CONSTRAINT fk_store_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL;

    $pdo->exec($sql);

    // Ensure product_ids exists for existing installations
    try {
        $check = $pdo->prepare("SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'stores' AND COLUMN_NAME = 'product_ids'");
        $check->execute();
        $exists = (int) ($check->fetchColumn() ?? 0) > 0;
        if (!$exists) {
            $pdo->exec("ALTER TABLE stores ADD COLUMN product_ids TEXT DEFAULT NULL AFTER description");
        }
    } catch (Throwable $e) {
        // Ignore if information_schema not accessible; best effort
    }
}
