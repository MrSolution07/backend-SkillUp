<?php
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        if (strpos($line, '=') === false) continue;
        list($key, $value) = explode('=', $line, 2);
        putenv(trim($key) . '=' . trim($value));
    }
}

$railway_mysql = getenv("MYSQLHOST");
$jawsdb_url   = getenv("JAWSDB_URL") ?: getenv("JAWSDB");

if ($railway_mysql) {
    $servername = getenv("MYSQLHOST");
    $username   = getenv("MYSQLUSER");
    $password   = getenv("MYSQLPASSWORD");
    $database   = getenv("MYSQLDATABASE");
    $port       = getenv("MYSQLPORT") ?: 3306;
} elseif ($jawsdb_url) {
    $url_parts  = parse_url($jawsdb_url);
    $servername = $url_parts['host'];
    $username   = $url_parts['user'];
    $password   = $url_parts['pass'];
    $database   = ltrim($url_parts['path'], '/');
    $port       = $url_parts['port'] ?? 3306;
} else {
    $servername = getenv("DB_HOST");
    $username   = getenv("DB_USERNAME");
    $password   = getenv("DB_PASSWORD");
    $database   = getenv("DB_DATABASE");
    $port       = getenv("DB_PORT") ?: 3306;
}

if (!$servername || !$username || !$database) {
    die("No database configuration found. Check your .env file or set environment variables.");
}

$conn = new mysqli($servername, $username, $password, $database, (int)$port);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
