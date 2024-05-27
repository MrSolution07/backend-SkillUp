<?php
require('connect.php');
//can't update user
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

$input = json_decode(file_get_contents('php://input'), true);

if (isset($input['username']) && isset($input['email']) && isset($input['mobileNumber'])) {
    $username = $input['username'];
    $email = $input['email'];
    $mobileNumber = $input['mobileNumber'];

    if (!empty($username) && !empty($email) && !empty($mobileNumber)) {
        $sql = "UPDATE credentials SET Email='$email', Mobile_Number='$mobileNumber' WHERE Username='$username'";

        if ($conn->query($sql) === TRUE) {
            echo json_encode(["message" => "Record updated successfully"]);
        } else {
            echo json_encode(["message" => "Error updating record: " . $conn->error]);
        }
    } else {
        echo json_encode(["message" => "All fields are required"]);
    }
} else {
    echo json_encode(["message" => "Invalid input"]);
}

$conn->close();
?>