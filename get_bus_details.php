<?php
require('connect.php');
// Set CORS headers
header("Access-Control-Allow-Origin: *"); // Allow requests from this origin
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

$input = json_decode(file_get_contents('php://input'), true);

if (isset($input['username'])) {
    $username = $input['username'];

    $sql = "SELECT BusinessName, Email, Mobile_Number, Password FROM business WHERE BusinessName='$username'";
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
