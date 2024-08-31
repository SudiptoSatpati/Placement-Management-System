

<?php
// Database connection
include '../includes/db_connect.php';


if (isset($_POST['email'])) {
    $email = $conn->real_escape_string($_POST['email']);
    $query = "SELECT * FROM students WHERE Email = '$email'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        echo 'exists';
    } else {
        echo 'not_exists';
    }
}
?>


