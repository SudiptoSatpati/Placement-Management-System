<?php
require '../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include '../includes/db_connect.php';

session_start();

if (isset($_POST['email'])) {
    $email = $conn->real_escape_string($_POST['email']);
    $otp = rand(100000, 999999);

    // Store OTP in session
    $_SESSION['otp'] = $otp;

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'placement.cell.cse2021.25@gmail.com';
        $mail->Password = 'hjjo vrhe hqkg jfiw';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('placement.cell.cse2021.25@gmail.com', 'Placement CSE');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Your OTP Code';
        $mail->Body    = "Your OTP for resetting your password is $otp. <b>Do not share this with anyone.</b>";

        $mail->send();
        echo json_encode(['status' => 'otp_sent', 'otp' => $otp]);
    } catch (Exception $e) {
        echo json_encode(['status' => 'otp_failed']);
    }
}
?>
