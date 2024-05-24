<?php
require("connect.php");

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $username = mysqli_real_escape_string($conn, $_GET['username']);

    $sql = "SELECT ProfilePicture, imageType FROM credentials WHERE Username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $profilePicture = base64_encode($row['ProfilePicture']);
        $imageType = $row['imageType'];

        echo json_encode(["success" => true, "image" => $profilePicture, "type" => $imageType]);
    } else {
        echo json_encode(["success" => false, "message" => "User not found"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
}

$conn->close();
?>

