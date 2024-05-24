
require("connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['username']) && isset($data['courseName'])) {
        $username = mysqli_real_escape_string($conn, $data['username']);
        $courseName = mysqli_real_escape_string($conn, $data['courseName']);

        try {
            // Assuming there's a user_id or similar foreign key in the courses table
            // that references the users table to maintain the relationship.
            $insertStmt = $conn->prepare("INSERT INTO courses (username, course) VALUES (?, ?)");
            $insertStmt->bind_param("ss", $username, $courseName);

            if ($insertStmt->execute() === TRUE) {
                $response = array("success" => true, "message" => "Course added successfully");
            } else {
                $response = array("success" => false, "message" => "Error adding course: " . $conn->error);
            }

            $insertStmt->close();
        } catch (Exception $ex) {
            $response = array("success" => false, "message" => "Error: " . $ex->getMessage());
        }
    } else {
        $response = array("success" => false, "message" => "Username or course name not provided");
    }

    $conn->close();
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>



<?php
require("connect.php");

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['username']) && isset($data['courseName']) && isset($data['courseHeading']) && isset($data['coursePrice']) && isset($data['courseOfferPrice']) && isset($data['courseRating']) && isset($data['courseReviews']) && isset($data['courseImg'])) {
        
        $username = mysqli_real_escape_string($conn, $data['username']);
        $courseName = mysqli_real_escape_string($conn, $data['courseName']);
        $courseHeading = mysqli_real_escape_string($conn, $data['courseHeading']);
        $coursePrice = mysqli_real_escape_string($conn, $data['coursePrice']);
        $courseOfferPrice = mysqli_real_escape_string($conn, $data['courseOfferPrice']);
        $courseRating = mysqli_real_escape_string($conn, $data['courseRating']);
        $courseReviews = mysqli_real_escape_string($conn, $data['courseReviews']);
        $courseImg = mysqli_real_escape_string($conn, $data['courseImg']);
        //$courseDescription = mysqli_real_escape_string($conn, $data['courseDescription']);

        $sql = "INSERT INTO courses (Username, courseName, courseHeading, coursePrice, courseOfferPrice, courseRating, courseReviews, courseImg) VALUES ('$username', '$courseName', '$courseHeading', '$coursePrice', '$courseOfferPrice', '$courseRating', '$courseReviews', '$courseImg')";

        if ($conn->query($sql) === TRUE) {
            echo json_encode(array("success" => true));
        } else {
            echo json_encode(array("success" => false, "message" => "Error: " . $conn->error));
        }
    } else {
        echo json_encode(array("success" => false, "message" => "Missing required fields"));
    }
}

$conn->close();
?>
