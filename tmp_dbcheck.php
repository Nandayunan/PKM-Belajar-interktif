<?php
function loadEnv($path) {
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $result = [];
    foreach ($lines as $line) {
        if (trim($line) === '' || str_starts_with(trim($line), '#')) {
            continue;
        }
        [$key, $value] = array_map('trim', explode('=', $line, 2) + ['', '']);
        if ($key === '') continue;
        $value = trim($value, " \t\n\r\0\x0B\"'");
        $result[$key] = $value;
    }
    return $result;
}
$env = loadEnv(__DIR__ . '/.env');
$dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', $env['DB_HOST'] ?? '127.0.0.1', $env['DB_PORT'] ?? '3306', $env['DB_DATABASE'] ?? 'laravel');
$pdo = new PDO($dsn, $env['DB_USERNAME'] ?? 'root', $env['DB_PASSWORD'] ?? '');
$stmt = $pdo->query("SHOW FULL COLUMNS FROM questions LIKE 'type'");
$row = $stmt->fetch(PDO::FETCH_ASSOC);
var_dump($row);
