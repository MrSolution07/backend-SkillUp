<?php
require("connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['username']) && isset($_FILES['ProfilePicture'])) {
        try {
            $username = mysqli_real_escape_string($conn, $_POST['username']);
            
            // Check if the user exists
            $checkUserSql = "SELECT * FROM credentials WHERE Username = '$username'";
            $userResult = $conn->query($checkUserSql);
            
            if ($userResult->num_rows == 0) {
                echo json_encode(array("success" => false, "message" => "Username does not exist"));
                exit();
            }

            // Handle file upload
            if ($_FILES['ProfilePicture']['error'] == UPLOAD_ERR_OK) {
                $imageTmpName = $_FILES['ProfilePicture']['tmp_name'];
                $imageName = $_FILES['ProfilePicture']['name'];
                $imageSize = $_FILES['ProfilePicture']['size'];
                $imageType = $_FILES['ProfilePicture']['type'];
                
                $imageContent = addslashes(file_get_contents($imageTmpName));
                
                // Update the user's profile picture in the database
                $sql = "UPDATE credentials SET ProfilePicture = '$imageContent', imageName = '$imageName', imageType = '$imageType', imageSize = '$imageSize' WHERE Username = '$username'";
                
                if ($conn->query($sql) === TRUE) {
                    echo json_encode(array("success" => true, "message" => "Profile picture uploaded successfully"));
                } else {
                    echo json_encode(array("success" => false, "message" => "Error: " . $conn->error));
                }
            } else {
                echo json_encode(array("success" => false, "message" => "Error uploading file"));
            }
        } catch (Exception $e) {
            echo json_encode(array("success" => false, "message" => "Error: " . $e->getMessage()));
        }
    } else {
        echo json_encode(array("success" => false, "message" => "Missing required fields"));
    }
}

$conn->close();
?>
