<?php
require("connect.php");

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $imageContent = null;
    $imageType = null;

    if (isset($_FILES['postImage']) && $_FILES['postImage']['error'] == UPLOAD_ERR_OK) {
        $imageTmpName = $_FILES['postImage']['tmp_name'];
        $imageType = $_FILES['postImage']['type'];
        $imageContent = addslashes(file_get_contents($imageTmpName));
    }

    // Insert the post into the database
    $sql = "INSERT INTO posts (Username, Content, ImageContent, ImageType) VALUES ('$username', '$content', '$imageContent', '$imageType')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(array("success" => true, "message" => "Post created successfully"));
    } else {
        echo json_encode(array("success" => false, "message" => "Error: " . $conn->error));
    }
} else {
    echo json_encode(array("success" => false, "message" => "Invalid request method"));
}

$conn->close();
?>
