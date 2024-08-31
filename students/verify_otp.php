<?php
session_start();
header('Content-Type: application/json');

$otp = $_POST['otp'];

if ($otp == $_SESSION['otp']) {
    unset($_SESSION['otp']); // Clear OTP from session
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid OTP']);
}
?>
