<?php
require("connect.php");

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// Check if the required POST parameters are set
if (isset($_POST['username']) && isset($_POST['currentPassword']) && isset($_POST['newPassword'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];

    // Prepare the SQL query to fetch the current password hash from the database
    $query = "SELECT Password FROM credentials WHERE Username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $username);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $userData = $result->fetch_assoc();
            // Verify the provided current password with the stored hash
            if (password_verify($currentPassword, $userData['Password'])) {
                // Hash the new password
                $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
                // Prepare the SQL query to update the password in the database
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
