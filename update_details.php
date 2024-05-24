<?php
require("connect.php");

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['mobileNumber'])) {
        // Update user data
        $username = $_POST['username'];
        $email = $_POST['email'];
        $mobileNumber = $_POST['mobileNumber'];
        
        $query = "UPDATE credentials SET Email = ?, Mobile Number = ? WHERE Username = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('sss', $email, $mobileNumber, $username);
        
        if ($stmt->execute()) {
            echo json_encode(array('success' => true, 'message' => 'User data updated successfully.'));
        } else {
            echo json_encode(array('success' => false, 'message' => 'Error updating user data.'));
        }
        $stmt->close();
    } else {
        echo json_encode(array("success" => false, "message" => "Missing required fields"));
    }
}

$conn->close();
?>

