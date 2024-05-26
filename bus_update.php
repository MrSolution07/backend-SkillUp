<?php
require("connect.php");

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['mobileNumber'])) {
        // Update user data
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $mobileNumber = mysqli_real_escape_string($conn, $_POST['mobileNumber']);
        
        $query = "UPDATE business SET Email = ?, `Mobile_Number` = ? WHERE BusinessName = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('sss', $email, $mobileNumber, $username);
        
        if ($stmt->execute()) {
            echo json_encode(array('success' => true, 'message' => 'User data updated successfully.'));
        } else {
            echo json_encode(array('success' => false, 'message' => 'Error updating user data.'));
        }
        $stmt->close();
    }

    if (isset($_POST['username']) && isset($_FILES['ProfilePicture'])) {
        // Handle profile picture upload
        try {
            $username = mysqli_real_escape_string($conn, $_POST['username']);
            
            // Check if the user exists
            $checkUserSql = "SELECT * FROM business WHERE BusinessName = '$username'";
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
                
                // Update the user's profile picture in the database
                $sql = "UPDATE business SET ProfilePicture = ?, imageName = ?, imageType = ?, imageSize = ? WHERE BusinessName = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('sssss', $imageContent, $imageName, $imageType, $imageSize, $username);
                
                $imageContent = file_get_contents($imageTmpName);
                if ($stmt->execute()) {
                    echo json_encode(array("success" => true, "message" => "Profile picture uploaded successfully"));
                } else {
                    echo json_encode(array("success" => false, "message" => "Error: " . $stmt->error));
                }
                $stmt->close();
            } else {
                echo json_encode(array("success" => false, "message" => "Error uploading file"));
            }
        } catch (Exception $e) {
            echo json_encode(array("success" => false, "message" => "Error: " . $e->getMessage()));
        }
    } else {
        echo json_encode(array("success" => false, "message" => "Missing required fields"));
    }

    // Password update logic
    if (isset($_POST['username']) && isset($_POST['currentPassword']) && isset($_POST['newPassword'])) {
        $username = $_POST['username'];
        $currentPassword = $_POST['currentPassword'];
        $newPassword = $_POST['newPassword'];

        $query = "SELECT Password FROM credentials WHERE Username = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('s', $username);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $userData = $result->fetch_assoc();
                if (password_verify($currentPassword, $userData['Password'])) {
                    $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
                    $updatePasswordQuery = "UPDATE credentials SET Password = ? WHERE Username = ?";
                    $updatePasswordStmt = $conn->prepare($updatePasswordQuery);
                    $updatePasswordStmt->bind_param('ss', $newPasswordHash, $username);

                    if ($updatePasswordStmt->execute()) {
                        echo json_encode(array('success' => true, 'message' => 'Password updated successfully.'));
                    } else {
                        echo json_encode(array('success' => false, 'message' => 'Error updating password.'));
                    }
                    $updatePasswordStmt->close();
                } else {
                    echo json_encode(array('success' => false, 'message' => 'Current password is incorrect.'));
                }
            } else {
                echo json_encode(array('success' => false, 'message' => 'User not found.'));
            }
        } else {
            echo json_encode(array('success' => false, 'message' => 'Error fetching user data.'));
        }
        $stmt->close();
    }
    $conn->close();
}
?>



