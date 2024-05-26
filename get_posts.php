<?php
require("connect.php");

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $sql = "SELECT Username, Content, CreatedAt, ImageContent, ImageType FROM post ORDER BY CreatedAt DESC";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $posts = [];
        while ($row = $result->fetch_assoc()) {
            $imageData = null;
            if ($row["ImageContent"]) {
                $imageData = base64_encode($row["ImageContent"]);
            }

            $posts[] = [
                "username" => $row["Username"],
                "content" => $row["Content"],
                "createdAt" => $row["CreatedAt"],
                "image" => $imageData,
                "imageType" => $row["ImageType"]
            ];
        }
        echo json_encode(["success" => true, "posts" => $posts]);
    } else {
        echo json_encode(["success" => false, "message" => "No posts found"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
}

$conn->close();
?>

