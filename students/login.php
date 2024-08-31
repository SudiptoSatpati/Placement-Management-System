<?php
require_once '../vendor/autoload.php'; // Path to your Composer autoload file
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
session_start();
header('Content-Type: application/json');

include '../includes/db_connect.php'; // Include your database connection

$username = $_POST['username'];
$password = $_POST['password'];

// Prepare and execute query to fetch the hashed password
$sql = "SELECT password FROM studentUsers WHERE Institute_Roll_No = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $hashed_password = $row['password'];

    // Verify the entered password with the hashed password
    if (password_verify($password, $hashed_password)) {
        // Generate OTP and store it in session
        $otp = rand(100000, 999999);
        $_SESSION['otp'] = $otp;
        $_SESSION['username'] = $username;

        // Fetch email address from students table
        $sql = "SELECT Email FROM students WHERE Institute_Roll_No = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        $email = $row['Email'];

        // Send OTP via email
        if (sendOtpEmail($email, $otp)) {
            echo json_encode(['success' => true, 'message' => 'OTP sent successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to send OTP.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid username or password']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid username or password']);
}

function sendOtpEmail($email, $otp) {
    $mail = new PHPMailer(true);

    try {
        // SMTP settings
        $mail->isSMTP();
        $mail->Host = 'provider ';
        $mail->SMTPAuth = true;
        $mail->Username = 'your email'; // Your Gmail address
        $mail->Password = 'your password'; // Your Gmail app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Secure transfer
        $mail->Port = 587;

        // Email settings
        $mail->setFrom('your email', 'Placement CSE');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'OTP for Login ';
        $mail->Body    = "Your OTP for login into PlacementCell Cse is: {$otp} <br>Do not share with anyone.";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Failed to send OTP to $email: " . $mail->ErrorInfo);
        return false;
    }
}
?>
