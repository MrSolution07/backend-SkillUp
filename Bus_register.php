<?php
require("connect.php");
// Add CORS headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    try {
        
        $username = mysqli_real_escape_string($conn, $_POST['businessName']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);
        $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);

        $hashed_pwd = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO business (`BusinessName`, `Email`, `Password`, `Mobile_number`) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $email, $hashed_pwd, $mobile);

        if ($stmt->execute() === TRUE) {
            $response = array("success" => true, "message" => "USER registered successfully");
            
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

