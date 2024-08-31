<?php
require_once '../vendor/autoload.php'; // Path to your Composer autoload file

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendOtpEmail($email, $otp) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'mail provider';
        $mail->SMTPAuth = true;
        $mail->Username = 'youremail'; // Your Gmail address
        $mail->Password = 'yur password'; // Your Gmail password or App Password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Debugging
        $mail->SMTPDebug = 2; // Enable detailed debug output

        // Recipients
        $mail->setFrom('your email', 'Your Name');
        $mail->addAddress($email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Your OTP Code';
        $mail->Body    = "Your OTP code is: $otp";

        $mail->send();
        echo json_encode(['success' => true, 'message' => 'OTP sent successfully.']);
    } catch (Exception $e) {
        error_log("Failed to send OTP to $email: " . $mail->ErrorInfo);
        echo json_encode(['success' => false, 'message' => 'Failed to send OTP.']);
    }
}

sendOtpEmail('sample gmail', '123456');
?>
