<?php
require("connect.php");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["imageId"])) {
    $imageId = $_GET["imageId"];

    $sql = "SELECT ImageContent, ImageType FROM images WHERE ImageId = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $imageId);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($imageContent, $imageType);
    $stmt->fetch();

    if ($stmt->num_rows > 0) {
        header("Content-Type: " . $imageType);
        echo $imageContent;
    } else {
        http_response_code(404);
        echo "Image not found";
    }

    $stmt->close();
} else {
    http_response_code(400);
    echo "Invalid request";
}

$conn->close();
?>
