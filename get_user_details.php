<?php
require('connect.php');

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Set CORS headers
header("Access-Control-Allow-Origin: *"); // Allow requests from this origin
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER["REQUEST_METHOD"] == "OPTIONS") {
    // Handle preflight request
    header("HTTP/1.1 204 No Content");
    exit(0);
}

// Get the input data
$input = json_decode(file_get_contents('php://input'), true);

if (isset($input['username'])) {
    $username = mysqli_real_escape_string($conn, $input['username']);

    $sql = "SELECT Username, Email, Mobile_Number, Password FROM credentials WHERE Username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $userDetails = $result->fetch_assoc();
        echo json_encode($userDetails);
    } else {
        echo json_encode(["message" => "No user found"]);
    }
} else {
    echo json_encode(["message" => "Invalid input"]);
}

$conn->close();
?>
