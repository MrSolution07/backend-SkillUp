<?php
require("connect.php");

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $username = mysqli_real_escape_string($conn, $_GET['username']);

    $sql = "SELECT courseName, courseHeading, coursePrice, courseOfferPrice, courseRating, courseReviews, courseImg FROM courses WHERE Username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $courses = [];
        while ($row = $result->fetch_assoc()) {
            $courses[] = [
                "title" => $row["courseName"],
                "heading" => $row["courseHeading"],
                "price" => $row["coursePrice"],
                "offerPrice" => $row["courseOfferPrice"],
                "rating" => $row["courseRating"],
                "reviews" => $row["courseReviews"],
                "img3" => $row["courseImg"],
                //"description" => $row["courseDescription"]
            ];
        }
        echo json_encode(["success" => true, "courses" => $courses]);
    } else {
        echo json_encode(["success" => false, "message" => "No courses found"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
}

$conn->close();
?>
