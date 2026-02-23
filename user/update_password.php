<?php
require(__DIR__ . '/../config/database.php');

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

if (isset($_POST['username']) && isset($_POST['currentPassword']) && isset($_POST['newPassword'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];

    $query = "SELECT Password FROM credentials WHERE Username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $username);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $userData = $result->fetch_assoc();
            if (password_verify($currentPassword, $userData['Password'])) {
                $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
                $updatePasswordQuery = "UPDATE credentials SET Password = ? WHERE Username = ?";
                $updatePasswordStmt = $conn->prepare($updatePasswordQuery);
                $updatePasswordStmt->bind_param('ss', $newPasswordHash, $username);

                if ($updatePasswordStmt->execute()) {
                    echo json_encode(array('success' => true, 'message' => 'Password updated successfully.'));
                } else {
                    echo json_encode(array('success' => false, 'message' => 'Error updating password.'));
                }
                $updatePasswordStmt->close();
            } else {
                echo json_encode(array('success' => false, 'message' => 'Current password is incorrect.'));
            }
        } else {
            echo json_encode(array('success' => false, 'message' => 'User not found.'));
        }
    } else {
        echo json_encode(array('success' => false, 'message' => 'Error fetching user data.'));
    }
    $stmt->close();
} else {
    echo json_encode(array('success' => false, 'message' => 'Missing required fields.'));
}

$conn->close();
?>
