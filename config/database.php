<?php
// Render: uses MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DATABASE, MYSQL_PORT
// InfinityFree/Local: uses .env with DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT

$servername = getenv('MYSQL_HOST') ?: getenv('MYSQLHOST');
$username   = getenv('MYSQL_USER') ?: getenv('MYSQLUSER');
$password   = getenv('MYSQL_PASSWORD') ?: getenv('MYSQLPASSWORD');
$database   = getenv('MYSQL_DATABASE') ?: getenv('MYSQLDATABASE');
$port       = getenv('MYSQL_PORT') ?: getenv('MYSQLPORT') ?: 3306;

// Fallback to .env file (InfinityFree, local development)
if (!$servername || !$username || !$database) {
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
        $servername = $servername ?: ($env['DB_HOST'] ?? null);
        $username   = $username   ?: ($env['DB_USERNAME'] ?? null);
        $password   = $password   ?: ($env['DB_PASSWORD'] ?? null);
        $database   = $database   ?: ($env['DB_DATABASE'] ?? null);
        $port       = $port       ?: ($env['DB_PORT'] ?? 3306);
    }
}

if (!$servername || !$username || !$database) {
    die("No database configuration found. Set MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DATABASE on Render, or add a .env file for local/InfinityFree.");
}

$conn = new mysqli($servername, $username, $password ?: '', $database, (int)$port);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
