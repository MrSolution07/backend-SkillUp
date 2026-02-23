<?php
require(__DIR__ . '/../config/database.php');

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['username'])) {
        try {
            $username = mysqli_real_escape_string($conn, $data['username']);

            $checkStmt = $conn->prepare("SELECT * FROM business WHERE BusinessName = ?");
            $checkStmt->bind_param("s", $username);
            $checkStmt->execute();
            $result = $checkStmt->get_result();

            if ($result->num_rows > 0) {
                $deleteStmt = $conn->prepare("DELETE FROM business WHERE BusinessName = ?");
                $deleteStmt->bind_param("s", $username);

                if ($deleteStmt->execute() === TRUE) {
                    $response = array("success" => true, "message" => "Account deleted successfully");
                } else {
                    $response = array("success" => false, "message" => "Error deleting account: " . $conn->error);
                }

                $deleteStmt->close();
            } else {
                $response = array("success" => false, "message" => "Username not found");
            }

            $checkStmt->close();
        } catch (Exception $ex) {
            $response = array("success" => false, "message" => "Error: " . $ex->getMessage());
        }
    } else {
        $response = array("success" => false, "message" => "Username not provided");
    }

    $conn->close();
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
