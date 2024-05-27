<?php
require("connect.php");
// Add CORS headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Disable error reporting
// error_reporting(0);

$sql = "SELECT jobName, jobDescription, jobImage, BusinessName FROM jobs";
$result = $conn->query($sql);

$jobListings = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $jobListing = array(
            "jobName" => $row["jobName"],
            "jobDescription" => $row["jobDescription"],
            "jobImage" => base64_encode($row["jobImage"]),
            "businessName" => $row["BusinessName"]
        );
        array_push($jobListings, $jobListing);
    }
} else {
    echo json_encode(array("success" => false, "message" => "No job listings found"));
    exit();
}

// Return job listings as JSON
header('Content-Type: application/json');
echo json_encode(array("success" => true, "jobListings" => $jobListings));

$conn->close();
?>

