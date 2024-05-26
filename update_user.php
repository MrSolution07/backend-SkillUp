<?php
require('connect.php');

$input = json_decode(file_get_contents('php://input'), true);

if (isset($input['username'])) {
    $username = $input['username'];
    $email = $input['email'];
    $mobileNumber = $input['mobileNumber'];

    $sql = "UPDATE credentials SET Email='$email', Mobile_Number ='$mobileNumber' WHERE Username='$username'";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["message" => "Record updated successfully"]);
    } else {
        echo json_encode(["message" => "Error updating record: " . $conn->error]);
    }
} else {
    echo json_encode(["message" => "Invalid input"]);
}

$conn->close();
?>
