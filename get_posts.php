<?php
require("connect.php");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

try {
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        $sql = "SELECT Username, Content, CreatedAt, ImageId FROM posts ORDER BY CreatedAt DESC";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $posts = [];
            while ($row = $result->fetch_assoc()) {
                $imageUrl = null;
                if ($row["ImageId"]) {
                    $imageUrl = "https://skill-up-za-a416b38edeac.herokuapp.com/get_image.php?imageId=" . $row["ImageId"];
                }

                $posts[] = [
                    "username" => $row["Username"],
                    "content" => $row["Content"],
                    "createdAt" => $row["CreatedAt"],
                    "imageUrl" => $imageUrl
                ];
            }
            echo json_encode(["success" => true, "posts" => $posts]);
        } else {
            echo json_encode(["success" => false, "message" => "No posts found"]);
        }
    } else {
        throw new Exception("Invalid request method");
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}

$conn->close();
?>
