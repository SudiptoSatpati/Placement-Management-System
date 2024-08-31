<?php
session_start();
include '../includes/db_connect.php';

// Ensure the user is logged in and the username is set in the session
if (!isset($_SESSION['username'])) {
    die("You must be logged in to access this page.");
}

$username = $_SESSION['username'];

// Check database connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Verify password
$sql = "SELECT password FROM studentusers WHERE Institute_Roll_No = ?";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Error preparing statement: " . $conn->error);
}

$stmt->bind_param('s', $username);

if (!$stmt->execute()) {
    die("Error executing statement: " . $stmt->error);
}

$result = $stmt->get_result();

if ($result === false) {
    die("Error getting result: " . $stmt->error);
}

$row = $result->fetch_assoc();

if (!$row) {
    die("No matching user found.");
}

// Debug: Check fetched password
if (!$row['password']) {
    die("Password not found for the user.");
}

// Proceed with password verification
$input_password = $_POST['password'];  // Assuming you are sending the password from an AJAX request

if (password_verify($input_password, $row['password'])) {
    // Password is correct, proceed to update details

    $attribute = $_POST['attribute'];
    $new_value = $_POST['value'];

    $allowed_attributes = [
        'Email' => 'Email',
        'Phone_No' => 'Phone_No',
        'GitHub_Profile_URL' => 'GitHub_Profile_URL',
        'Linked_In_Profile_URL' => 'Linked_In_Profile_URL',
        'Upload_your_Resume' => 'Upload_your_Resume'
    ];

    if (array_key_exists($attribute, $allowed_attributes)) {
        $sql_update = "UPDATE students SET {$allowed_attributes[$attribute]} = ? WHERE Institute_Roll_No = ?";
        $stmt_update = $conn->prepare($sql_update);

        if ($stmt_update === false) {
            die("Error preparing update statement: " . $conn->error);
        }

        $stmt_update->bind_param('ss', $new_value, $username);

        if ($stmt_update->execute()) {
            echo json_encode(['success' => true, 'message' => 'Details updated successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update details: ' . $stmt_update->error]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid attribute.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Incorrect password.']);
}
?>
