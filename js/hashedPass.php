<?php
include '../includes/db_connect.php';

$default_password = 'student@123';
$hashed_password = password_hash($default_password, PASSWORD_DEFAULT);

// Update all students with the hashed password
$sql = "UPDATE studentusers SET password = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $hashed_password);

if ($stmt->execute()) {
    echo "All passwords updated successfully.";
} else {
    echo "Error updating passwords: " . $stmt->error;
}
?>
