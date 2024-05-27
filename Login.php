<?php
require("connect.php");
// Add CORS headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);

        // Retrieve the hashed password from the database
        $sql = "SELECT * FROM credentials WHERE Username = '$username'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $hashed_password = $row['Password'];
            $email=$row['Email'];
            $picture=base64_encode($row['ProfilePicture']);
            if (password_verify($password, $hashed_password)) {
                
                echo json_encode(array("success" => true, "message" => "Login successful", "email"=>$email, "picture"=>$picture));
            } else {
                echo json_encode(array("success" => false, "message" => "Login failed. Please check your credentials."));
            }
        } else {
            echo json_encode(array("success" => false, "message" => "Login failed. Please check your credentials."));
        }
    } catch (Exception $e) 
    {
        echo json_encode(array("success" => false, "message" => "Error: " . $e->getMessage()));
    }

    $conn->close();
}
?>

