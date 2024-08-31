

<?php
session_start();
include '../includes/db_connect.php'; // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newPassword = $_POST['new_password'];
    $user = $_SESSION['username']; // Ensure this session variable is set and correct

    // Hash the new password
    $hashedNewPassword = password_hash($newPassword, PASSWORD_BCRYPT);

    // Prepare an update statement
    $stmt = $conn->prepare("UPDATE studentusers SET password = ? WHERE Institute_Roll_No = ?");
    if ($stmt) {
        $stmt->bind_param("ss", $hashedNewPassword, $user);
        if ($stmt->execute()) {
            echo 'success';
        } else {
            echo 'error';
        }
        $stmt->close();
    } else {
        echo 'error: ' . $conn->error;
    }
} else {
    echo 'invalid_request';
}

// Close the database connection
$conn->close();
?>

