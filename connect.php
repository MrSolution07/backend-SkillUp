<?php

// Get JAWSDB_URL from environment variable
$url = getenv("JAWSDB_URL");

// Parse the database URL
$url_parts = parse_url($url);

// Extract connection details
$host = $url_parts['host'];
$username = $url_parts['user'];
$password = $url_parts['pass'];
$database = ltrim($url_parts['path'], '/');
$port = isset($url_parts['port']) ? $url_parts['port'] : 3306; // Default MySQL port

// Create a new mysqli connection
$conn = new mysqli($host, $username, $password, $database, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

echo "Connected successfully";

// Perform your database operations here...

// Close connection
$conn->close();

?>
