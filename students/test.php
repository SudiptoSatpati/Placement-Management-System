=<?php
require_once '../vendor/autoload.php'; // Path to your Composer autoload file

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendOtpEmail($email, $otp) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'placement.cell.cse2021.25@gmail.com'; // Your Gmail address
        $mail->Password = 's1u2d3i4p5t6o7'; // Your Gmail password or App Password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Debugging
        $mail->SMTPDebug = 2; // Enable detailed debug output

        // Recipients
        $mail->setFrom('placement.cell.cse2021.25@gmail.com', 'Your Name');
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

sendOtpEmail('satpatisudipto@gmail.com', '123456');
?>
