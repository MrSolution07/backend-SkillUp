
<?php

$servername = "sql312.infinityfree.com";
$username = "if0_36618440";
$password = "hFy137sbIctERGn"; 
$database = "if0_36618440_Users"; // Fill in the database name
$port = 3306; // Assuming default MySQL port

$conn = new mysqli($servername, $username, $password, $database, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}