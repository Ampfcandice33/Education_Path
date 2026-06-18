<?php
require_once __DIR__ . '/../helpers/env.php';
load_env(__DIR__ . '/../../.env');

$host = getenv('DB_HOST') ?: 'localhost';
$dbname = getenv('DB_NAME') ?: 'edupath';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') ?: '';
$charset = getenv('DB_CHARSET') ?: 'utf8mb4';

$dsn = "mysql:host={$host};dbname={$dbname};charset={$charset}";

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $e) {
    if ((getenv('APP_DEBUG') ?: 'false') === 'true') {
        die('Database connection failed: ' . htmlspecialchars($e->getMessage()));
    }
    die('Database connection failed. Please contact the administrator.');
}
