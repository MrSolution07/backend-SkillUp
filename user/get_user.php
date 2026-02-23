<?php
require(__DIR__ . '/../config/database.php');

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

$input = json_decode(file_get_contents('php://input'), true);

if (isset($input['username'])) {
    $username = $input['username'];

    $sql = "SELECT Username, Email, Mobile_Number, ProfilePicture FROM credentials WHERE Username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        echo json_encode($user);
    } else {
        echo json_encode(["message" => "User not found"]);
    }
} else {
    echo json_encode(["message" => "Invalid input"]);
}

$conn->close();
?>
