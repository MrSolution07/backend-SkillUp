<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Enable error logging (for debugging purposes)
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

require("connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($conn->connect_error) {
        error_log("Database connection failed: " . $conn->connect_error);
        die(json_encode(['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]));
    }

    $postData = json_decode(file_get_contents("php://input"), true);

    if (!isset($postData['username']) || !isset($postData['currentPassword']) || !isset($postData['newPassword'])) {
        error_log('Invalid input: ' . json_encode($postData));
        echo json_encode(['success' => false, 'message' => 'Invalid input']);
        exit;
    }

    $username = $postData['username'];
    $currentPassword = $postData['currentPassword'];
    $newPassword = $postData['newPassword'];

    $sql = "SELECT password FROM credentials WHERE Username = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        error_log("Prepare statement failed: " . $conn->error);
        echo json_encode(['success' => false, 'message' => 'Prepare statement failed: ' . $conn->error]);
        exit;
    }
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        error_log('User not found: ' . $username);
        echo json_encode(['success' => false, 'message' => 'User not found']);
        exit;
    }

    $row = $result->fetch_assoc();
    $hashedPassword = $row['password'];

    // Verify the current password
    if (!password_verify($currentPassword, $hashedPassword)) {
        error_log('Current password is incorrect for user: ' . $username);
        echo json_encode(['success' => false, 'message' => 'Current password is incorrect']);
        exit;
    }

    $newHashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
    $sql = "UPDATE credentials SET password = ? WHERE Username = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        error_log("Prepare statement failed: " . $conn->error);
        echo json_encode(['success' => false, 'message' => 'Prepare statement failed: ' . $conn->error]);
        exit;
    }
    $stmt->bind_param("ss", $newHashedPassword, $username);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Password updated successfully']);
    } else {
        error_log("Error updating password: " . $stmt->error);
        echo json_encode(['success' => false, 'message' => 'Error updating password: ' . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>