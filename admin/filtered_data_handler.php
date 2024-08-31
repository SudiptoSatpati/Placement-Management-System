<?php
$conn = new mysqli('localhost', 'root', '', 'placement_db', 3307);

if ($conn->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$filterBy1 = $_GET['filterBy1'];
$filterValue1 = $_GET['filterValue1'];
$filterBy2 = $_GET['filterBy2'];
$filterValue2 = $_GET['filterValue2'];

$query = "SELECT Institute_Roll_No, Name, BE_Average_Percentage, Class_12th_Percentage, Class_10th_Percentage, No_of_Current_Backlogs, Internship FROM students WHERE 1=1";

if ($filterBy1 && $filterValue1) {
    // For numeric values, assume a range-based filter
    if (in_array($filterBy1, ['BE_Average_Percentage', 'Class_12th_Percentage', 'Class_10th_Percentage', 'Diploma_Overall_Percentage', 'No_of_Current_Backlogs'])) {
        $query .= " AND $filterBy1 >= $filterValue1";
    } else {
        $query .= " AND $filterBy1 = '$filterValue1'";
    }
}

if ($filterBy2 && $filterValue2) {
    // For numeric values, assume a range-based filter
    if (in_array($filterBy2, ['BE_Average_Percentage', 'Class_12th_Percentage', 'Class_10th_Percentage', 'Diploma_Overall_Percentage', 'No_of_Current_Backlogs'])) {
        $query .= " AND $filterBy2 >= $filterValue2";
    } else {
        $query .= " AND $filterBy2 = '$filterValue2'";
    }
}


// if ($filterBy && $filterValue) {
//     $query .= " AND $filterBy = '$filterValue'";
// }

// if ($filterBy2 && $filterValue2) {
//     $query .= " AND $filterBy2 = '$filterValue2'";
// }

$result = $conn->query($query);

if ($result->num_rows > 0) {
    echo "<table class='table'>";
    echo "<thead>";
    echo "<tr>";
    echo "<th>Roll No.</th>";
    echo "<th>Name</th>";
    echo "<th>BE Average %</th>";
    echo "<th>12th Average %</th>";
    echo "<th>10th Average %</th>";
    echo "<th>Backlogs</th>";
    echo "<th>Internship</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['Institute_Roll_No'] . "</td>";
        echo "<td>" . $row['Name'] . "</td>";
        echo "<td>" . $row['BE_Average_Percentage'] . "</td>";
        echo "<td>" . $row['Class_12th_Percentage'] . "</td>";
        echo "<td>" . $row['Class_10th_Percentage'] . "</td>";
        echo "<td>" . $row['No_of_Current_Backlogs'] . "</td>";
        echo "<td>" . $row['Internship'] . "</td>";
        echo "</tr>";
    }

    echo "</tbody>";
    echo "</table>";
} else {
    echo "No results found.";
}

$conn->close();
?>
