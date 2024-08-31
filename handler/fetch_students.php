<?php
include('../includes/db_connect.php');

// Get the search query
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

// Prepare SQL query
$query = "SELECT Name, `Institute_Roll_No`, `BE_Average_Percentage` FROM students";
if (!empty($search)) {
    $query .= " WHERE Name LIKE '%$search%' OR `Institute_Roll_No` LIKE '%$search%'";
}

$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($row['Name']) . '</td>';
        echo '<td>' . htmlspecialchars($row['Institute_Roll_No']) . '</td>';
        echo '<td>' . htmlspecialchars($row['BE_Average_Percentage']) . '</td>';
        echo '<td><a href="update_student.php?id=' . urlencode($row['Institute_Roll_No']) . '" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i> Update</a></td>';
        echo '</tr>';
    }
} else {
    echo '<tr><td colspan="4" class="text-center">No student records found.</td></tr>';
}
?>
