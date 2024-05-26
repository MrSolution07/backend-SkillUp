
<?php

$servername = "10-5-6-196.proxysql-cluster-passive.proxysql.svc.cluster.local";
$username = "b0280930e1e73e";
$password = "7cf3a3dd72b3087"; 
$database = "heroku_19e1b467e1a92b8"; // Fill in the database name
$port = 3306; // Assuming default MySQL port

$conn = new mysqli($servername, $username, $password, $database, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}