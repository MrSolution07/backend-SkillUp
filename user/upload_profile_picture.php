<?php
require(__DIR__ . '/../config/cors.php');
require(__DIR__ . '/../config/database.php');

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if (isset($_POST['username']) && isset($_FILES['profilePicture'])) {
    try {
        $username = mysqli_real_escape_string($conn, $_POST['username']);

        $checkUserSql = "SELECT * FROM credentials WHERE Username = '$username'";
        $userResult = $conn->query($checkUserSql);

        if ($userResult->num_rows == 0) {
            echo json_encode(array("success" => false, "message" => "Username does not exist"));
            exit();
        }

        if ($_FILES['profilePicture']['error'] == UPLOAD_ERR_OK) {
            $imageTmpName = $_FILES['profilePicture']['tmp_name'];
            $imageName = $_FILES['profilePicture']['name'];
            $imageSize = $_FILES['profilePicture']['size'];
            $imageType = $_FILES['profilePicture']['type'];

            $imageContent = file_get_contents($imageTmpName);

            $sql = "UPDATE credentials SET ProfilePicture = ?, ImageName = ?, ImageType = ?, ImageSize = ? WHERE Username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('sssis', $imageContent, $imageName, $imageType, $imageSize, $username);

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

$conn->close();
?>
