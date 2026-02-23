<?php
$jawsdb_url = getenv("JAWSDB_URL") ?: getenv("JAWSDB");
$railway_mysql = getenv("MYSQLHOST");

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
    die("No database configuration found. Set JAWSDB_URL or Railway MySQL variables.");
}

$conn = new mysqli($servername, $username, $password, $database, (int)$port);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
