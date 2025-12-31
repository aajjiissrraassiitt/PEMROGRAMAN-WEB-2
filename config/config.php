<?php
// config/config.php
declare(strict_types=1);

$cfg = [
    'db_host' => '127.0.0.1',
    'db_name' => 'absensi_app',
    'db_user' => 'root',
    'db_pass' => '',
    'uploads_absensi' => __DIR__ . '/../uploads/absensi/',
    'uploads_profil' => __DIR__ . '/../uploads/profil/',
];

try {
    $dsn = "mysql:host={$cfg['db_host']};dbname={$cfg['db_name']};charset=utf8mb4";
    $pdo = new PDO($dsn, $cfg['db_user'], $cfg['db_pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}