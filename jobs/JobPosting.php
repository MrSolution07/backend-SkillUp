<?php
require('/../confing/cors');
require(__DIR__ . '/../config/database.php');

header("Access-Control-Allow-Origin: *");
header("Access-Control-A`llow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $jobName = mysqli_real_escape_string($conn, $_POST['jobName']);
        $jobDescription = mysqli_real_escape_string($conn, $_POST['jobDescription']);
        $businessName = mysqli_real_escape_string($conn, $_POST['businessName']);
        
        $checkBusinessSql = "SELECT * FROM business WHERE BusinessName = '$businessName'";
        $businessResult = $conn->query($checkBusinessSql);

        if ($businessResult->num_rows == 0) {
            echo json_encode(array("success" => false, "message" => "Business name does not exist"));
            exit();
        }

        if (isset($_FILES['jobImage']) && $_FILES['jobImage']['error'] == UPLOAD_ERR_OK) {
            $imageTmpName = $_FILES['jobImage']['tmp_name'];
            $imageName = $_FILES['jobImage']['name'];
            $imageSize = $_FILES['jobImage']['size'];
            $imageType = $_FILES['jobImage']['type'];
            
            $imageContent = addslashes(file_get_contents($imageTmpName));
            
            $sql = "INSERT INTO jobs (jobName, jobDescription, jobImage, imageName, imageType, imageSize, BusinessName) 
                    VALUES ('$jobName', '$jobDescription', '$imageContent', '$imageName', '$imageType', '$imageSize', '$businessName')";
        } else 
	{
            $sql = "INSERT INTO jobs (jobName, jobDescription, BusinessName) VALUES ('$jobName', '$jobDescription', '$businessName')";
        }
	
        if ($conn->query($sql) === TRUE) {
            echo json_encode(array("success" => true, "message" => "Job listing created successfully"));
        } else {
            echo json_encode(array("success" => false, "message" => "Error: " . $conn->error));
        }
    } catch (Exception $e) {
        echo json_encode(array("success" => false, "message" => "Error: " . $e->getMessage()));
    }
}

$conn->close();
?>
