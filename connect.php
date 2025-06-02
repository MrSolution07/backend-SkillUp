<!-- 

// JawsDB connection URL provided by your hosting provider
$jawsdb_url = getenv("JAWSDB_URL") ?: getenv("JAWSDB");

// Parse the JawsDB connection URL
$url_parts = parse_url($jawsdb_url);

// Extract connection parameters from the URL
$servername = $url_parts['host'];
$username = $url_parts['user'];
$password = $url_parts['pass'];
$database = ltrim($url_parts['path'], '/');

// Optional: Extract port if specified in the URL
$port = isset($url_parts['port']) ? $url_parts['port'] : 3306;

// Create a new MySQLi connection
$conn = new mysqli($servername, $username, $password, $database, $port);

// Check for connection errors
if ($conn->connect_error) {
    // If there's an error, terminate the script and display an error message
    die("Connection failed: " . $conn->connect_error);
}

// If the connection is successful, continue with your database operations...
 -->



<?php

$database_url = getenv("DATABASE_URL");

if (!$database_url) {
    die("DATABASE_URL is not set in environment variables.");
}


$url_parts = parse_url($database_url);

$servername = $url_parts['host'];
$username   = $url_parts['user'];
$password   = $url_parts['pass'];
$database   = ltrim($url_parts['path'], '/');
$port       = isset($url_parts['port']) ? $url_parts['port'] : 3306;


$conn = new mysqli($servername, $username, $password, $database, $port);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Continue with your database operations...

?>