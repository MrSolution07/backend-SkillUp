<?php
require("connect.php");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    try {
        
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);
        $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);

        // Hash the password
        $hashed_pwd = password_hash($password, PASSWORD_DEFAULT);

        
        $stmt = $conn->prepare("INSERT INTO credentials (`Username`, `Email`, `Password`, `Mobile_Number`) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $email, $hashed_pwd, $mobile); // Use the hashed password

        
        if ($stmt->execute() === TRUE) {
            $response = array("success" => true, "message" => "User registered successfully");
        } else {
            $response = array("success" => false, "message" => "Error: " . $conn->error);
        }

        $stmt->close();
    } catch (Exception $ex) {
        $response = array("success" => false, "message" => "Error: " . $ex->getMessage());
    }
} else {
    $response = array("success" => false, "message" => "Error: Username cannot be null");
}

$conn->close();

echo json_encode($response);
?>
