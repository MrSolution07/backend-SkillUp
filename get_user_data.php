<?php
require("connect.php");

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];

    $query = "SELECT Username, Email, Mobile Number, ProfilePicture FROM credentials WHERE Username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $username);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $userData = $result->fetch_assoc();
            echo json_encode($userData);
        } else {
            echo json_encode(array('message' => 'User not found.'));
        }
    } else {
        echo json_encode(array('message' => 'Error fetching user data.'));
    }
    $stmt->close();
}
$conn->close();
?>
