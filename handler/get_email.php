<?php
session_start();
require '../includes/db_connect.php';

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    $sql = "SELECT Email FROM students WHERE Institute_Roll_No = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($email);
    
    if ($stmt->fetch()) {
        echo json_encode(['status' => 'success', 'email' => $email]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Email not found']);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Username cookie is not set']);
}

$conn->close();
?>
