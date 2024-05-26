<?php

$url = "mysql://b0280930e1e73e:8864fad9@eu-cluster-west-01.k8s.cleardb.net/heroku_19e1b467e1a92b8";
$url_parts = parse_url($url);

$servername = $url_parts['host'];
$username = $url_parts['user'];
$password = $url_parts['pass'];
$database = ltrim($url_parts['path'], '/');

$port = 3306; // Assuming default MySQL port

$conn = new mysqli($servername, $username, $password, $database, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

?>
