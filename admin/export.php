<?php

require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "placement_db";
$port = 3307; // Use your MySQL port

$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get filters from URL
$filterBy1 = isset($_GET['filterBy1']) ? $_GET['filterBy1'] : '';
$filterValue1 = isset($_GET['filterValue1']) ? $_GET['filterValue1'] : '';
$filterBy2 = isset($_GET['filterBy2']) ? $_GET['filterBy2'] : '';
$filterValue2 = isset($_GET['filterValue2']) ? $_GET['filterValue2'] : '';

// Build the query
$query = "SELECT Name, Email, Address, Institute_Roll_No, Institute_Registration_No, 
          Class_10th_Percentage, Class_10th_Year_of_Passing, Class_12th_Percentage, 
          Class_12th_Year_of_Passing, Physics_Marks_12th, Chemistry_Marks_12th, 
          Math_Marks_12th, Course_Type, Diploma_Sem1_Percentage, Diploma_Sem2_Percentage, 
          Diploma_Sem3_Percentage, Diploma_Sem4_Percentage, Diploma_Sem5_Percentage, 
          Diploma_Sem6_Percentage, Diploma_Overall_Percentage, Diploma_Sem1_SGPA, 
          Diploma_Sem2_SGPA, Diploma_Sem3_SGPA, Diploma_Sem4_SGPA, Diploma_Sem5_SGPA, 
          Diploma_Sem6_SGPA, Diploma_Overall_CGPA, BE_Sem1_Percentage, BE_Sem2_Percentage, 
          BE_Sem3_Percentage, BE_Sem4_Percentage, BE_Sem5_Percentage, BE_Average_Percentage, 
          BE_Sem1_SGPA, BE_Sem2_SGPA, BE_Sem3_SGPA, BE_Sem4_SGPA, BE_Sem5_SGPA, BE_Average_CGPA, 
          No_of_Current_Backlogs, Current_Back_Papers, Internship, Gender, Upload_your_Resume, 
          TIMESTAMPDIFF(YEAR, Date_of_Birth, CURDATE()) AS Age 
          FROM students WHERE 1=1";

// Apply first filter
if ($filterBy1 && $filterValue1) {
    if (in_array($filterBy1, [
        'Class_10th_Percentage', 'Class_12th_Percentage', 'Diploma_Sem1_Percentage', 
        'Diploma_Sem2_Percentage', 'Diploma_Sem3_Percentage', 'Diploma_Sem4_Percentage', 
        'Diploma_Sem5_Percentage', 'Diploma_Sem6_Percentage', 'Diploma_Overall_Percentage', 
        'BE_Sem1_Percentage', 'BE_Sem2_Percentage', 'BE_Sem3_Percentage', 'BE_Sem4_Percentage', 
        'BE_Sem5_Percentage', 'BE_Average_Percentage', 'No_of_Current_Backlogs', 'Age'
    ])) {
        $query .= " AND $filterBy1 >= $filterValue1";
    } else {
        $query .= " AND $filterBy1 = '$filterValue1'";
    }
}

// Apply second filter
if ($filterBy2 && $filterValue2) {
    if (in_array($filterBy2, [
        'Class_10th_Percentage', 'Class_12th_Percentage', 'Diploma_Sem1_Percentage', 
        'Diploma_Sem2_Percentage', 'Diploma_Sem3_Percentage', 'Diploma_Sem4_Percentage', 
        'Diploma_Sem5_Percentage', 'Diploma_Sem6_Percentage', 'Diploma_Overall_Percentage', 
        'BE_Sem1_Percentage', 'BE_Sem2_Percentage', 'BE_Sem3_Percentage', 'BE_Sem4_Percentage', 
        'BE_Sem5_Percentage', 'BE_Average_Percentage', 'No_of_Current_Backlogs', 'Age'
    ])) {
        $query .= " AND $filterBy2 >= $filterValue2";
    } else {
        $query .= " AND $filterBy2 = '$filterValue2'";
    }
}

// Execute the query
$result = $conn->query($query);

if (!$result) {
    die("Query failed: " . $conn->error);
}

function generateExcel($data, $columns) {
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Set header
    $columnIndex = 'A';
    foreach ($columns as $col) {
        $sheet->setCellValue($columnIndex . '1', $col);
        $columnIndex++;
    }

    // Set data
    $rowIndex = 2;
    while ($row = $data->fetch_assoc()) {
        $columnIndex = 'A';
        foreach ($columns as $col) {
            $sheet->setCellValue($columnIndex . $rowIndex, $row[$col]);
            $columnIndex++;
        }
        $rowIndex++;
    }

    // Generate and output the Excel file
    $writer = new Xlsx($spreadsheet);
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="export.xlsx"');
    header('Cache-Control: max-age=0');
    $writer->save('php://output');
    exit();
}

$columns = [
    'Name', 'Email', 'Address', 'Institute_Roll_No', 'Institute_Registration_No', 
    'Class_10th_Percentage', 'Class_10th_Year_of_Passing', 'Class_12th_Percentage', 
    'Class_12th_Year_of_Passing', 'Physics_Marks_12th', 'Chemistry_Marks_12th', 
    'Math_Marks_12th', 'Course_Type', 'Diploma_Sem1_Percentage', 'Diploma_Sem2_Percentage', 
    'Diploma_Sem3_Percentage', 'Diploma_Sem4_Percentage', 'Diploma_Sem5_Percentage', 
    'Diploma_Sem6_Percentage', 'Diploma_Overall_Percentage', 'Diploma_Sem1_SGPA', 
    'Diploma_Sem2_SGPA', 'Diploma_Sem3_SGPA', 'Diploma_Sem4_SGPA', 'Diploma_Sem5_SGPA', 
    'Diploma_Sem6_SGPA', 'Diploma_Overall_CGPA', 'BE_Sem1_Percentage', 'BE_Sem2_Percentage', 
    'BE_Sem3_Percentage', 'BE_Sem4_Percentage', 'BE_Sem5_Percentage', 'BE_Average_Percentage', 
    'BE_Sem1_SGPA', 'BE_Sem2_SGPA', 'BE_Sem3_SGPA', 'BE_Sem4_SGPA', 'BE_Sem5_SGPA', 
    'BE_Average_CGPA', 'No_of_Current_Backlogs', 'Current_Back_Papers', 'Internship', 
    'Age', 'Gender', 'Upload_your_Resume'
];
generateExcel($result, $columns);

$conn->close();
exit();
?>
