<?php
/**
 * Database Configuration
 * 
 * Contains database connection parameters.
 * For production, use environment variables.
 */

return [
    'host'     => getenv('DB_HOST') ?: 'localhost',
    'database' => getenv('DB_NAME') ?: 'technoli_ssck_oba_qr',
    'username' => getenv('DB_USER') ?: 'root',
    'password' => getenv('DB_PASS') ?: '',
    'charset'  => 'utf8mb4',
    'port'     => getenv('DB_PORT') ?: 3306,
];