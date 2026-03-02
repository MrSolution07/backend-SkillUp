<?php
require(__DIR__ . '/../config/cors.php');
require(__DIR__ . '/../config/database.php');

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $username = isset($_POST['username']) ? trim($_POST['username']) : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';

        if (empty($username) || empty($password)) {
            echo json_encode(array("success" => false, "message" => "Username and password are required."));
            $conn->close();
            exit;
        }

        $stmt = $conn->prepare("SELECT Username, Email, Password, ProfilePicture FROM credentials WHERE Username = ?");
        if (!$stmt) {
            echo json_encode(array("success" => false, "message" => "Database error."));
            $conn->close();
            exit;
        }
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $hashed_password = $row['Password'] ?? $row['password'] ?? null;
            if (!$hashed_password) {
                echo json_encode(array("success" => false, "message" => "Account error. Please reset your password."));
            } elseif (password_verify($password, $hashed_password)) {
                $email = $row['Email'] ?? $row['email'] ?? '';
                $pic = $row['ProfilePicture'] ?? $row['profilepicture'] ?? null;
                $picture = $pic !== null ? base64_encode($pic) : '';
                echo json_encode(array("success" => true, "message" => "Login successful", "email" => $email, "picture" => $picture));
            } else {
                echo json_encode(array("success" => false, "message" => "Login failed. Please check your credentials."));
            }
        } else {
            echo json_encode(array("success" => false, "message" => "Login failed. Please check your credentials."));
        }
        $stmt->close();
    } catch (Exception $e) {
        echo json_encode(array("success" => false, "message" => "Error: " . $e->getMessage()));
    }
    $conn->close();
}
?>
