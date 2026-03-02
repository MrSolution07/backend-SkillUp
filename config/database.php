<?php
$env = [];
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        if (strpos($line, '=') === false) continue;
        list($key, $value) = explode('=', $line, 2);
        $env[trim($key)] = trim($value);
    }
}

$servername = $env['DB_HOST'] ?? null;
$username   = $env['DB_USERNAME'] ?? null;
$password   = $env['DB_PASSWORD'] ?? null;
$database   = $env['DB_DATABASE'] ?? null;
$port       = $env['DB_PORT'] ?? 3306;

if (!$servername || !$username || !$database) {
    die("No database configuration found. Check your .env file.");
}

$conn = new mysqli($servername, $username, $password, $database, (int)$port);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
