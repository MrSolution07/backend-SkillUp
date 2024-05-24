<?php

require("connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['username'])) { // Check $data array instead of $_POST
        try {
            $username = mysqli_real_escape_string($conn, $data['username']);

            // Check if the username exists
            $checkStmt = $conn->prepare("SELECT * FROM credentials WHERE Username = ?");
            $checkStmt->bind_param("s", $username);
            $checkStmt->execute();
            $result = $checkStmt->get_result();

            if ($result->num_rows > 0) {
                // Delete the user account
                $deleteStmt = $conn->prepare("DELETE FROM credentials WHERE Username = ?");
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
