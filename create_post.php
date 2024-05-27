<?php
require("connect.php");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

try {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST["username"];
        $content = $_POST["content"];
        $imageId = null;

        if (isset($_FILES["postImage"])) {
            $imageContent = file_get_contents($_FILES["postImage"]["tmp_name"]);
            $imageType = $_FILES["postImage"]["type"];

            // Insert image data into the images table
            $stmt = $conn->prepare("INSERT INTO images (ImageContent, ImageType) VALUES (?, ?)");
            $stmt->bind_param("bs", $imageContent, $imageType);
            $stmt->send_long_data(0, $imageContent);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                $imageId = $stmt->insert_id;
            } else {
                throw new Exception("Failed to insert image");
            }

            $stmt->close();
        }

        // Insert post data into the posts table
        $stmt = $conn->prepare("INSERT INTO posts (Username, Content, ImageId) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $username, $content, $imageId);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo json_encode(["success" => true, "message" => "Post created successfully"]);
        } else {
            throw new Exception("Failed to create post");
        }

        $stmt->close();
    } else {
        throw new Exception("Invalid request method");
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}

$conn->close();
?>
