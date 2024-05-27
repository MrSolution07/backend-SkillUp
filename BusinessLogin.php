<?php
require("connect.php");
// Add CORS headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $businessName = mysqli_real_escape_string($conn, $_POST['businessName']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);
        
        // Retrieve the hashed password from the database
        $sql = "SELECT Password FROM business WHERE BusinessName = '$businessName'";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $hashedPassword = $row['Password'];

            // Verify the password
            if (password_verify($password, $hashedPassword)) {
                echo json_encode(array("success" => true));
            } else {
                echo json_encode(array("success" => false, "message" => "Incorrect username or password"));
            }
        } else {
            echo json_encode(array("success" => false, "message" => "Incorrect username or password"));
        }
    } catch (Exception $e) {
        echo json_encode(array("success" => false, "message" => "Error: " . $e->getMessage()));
    }
}

$conn->close();
?>
