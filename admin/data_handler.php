<?php
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Include database connection
include('../includes/db_connect.php');

// Function to fetch data from the database
function fetchData($conn, $sortBy = 'Institute_Roll_No', $filterBy = '', $filterValue = '') {
    $query = "SELECT * FROM students";

    if ($filterBy && $filterValue) {
        // For numeric values, we assume a range-based filter
        if (in_array($filterBy, ['BE_Average_Percentage', 'Class_12th_Percentage', 'Class_10th_Percentage', 'Diploma_Overall_Percentage', 'No_of_Current_Backlogs'])) {
            $query .= " WHERE $filterBy >= $filterValue";
        } else {
            $query .= " WHERE $filterBy = '$filterValue'";
        }
    }

    $query .= " ORDER BY $sortBy";

    $result = $conn->query($query);

    if ($result === false) {
        echo "Error: " . $conn->error;
        return [];
    }

    return $result;
}

// Function to generate Excel file
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

// Handle GET request for data
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $sortBy = isset($_GET['sortBy']) ? $_GET['sortBy'] : 'Institute_Roll_No';
    $filterBy = isset($_GET['filterBy']) ? $_GET['filterBy'] : '';
    $filterValue = isset($_GET['filterValue']) ? $_GET['filterValue'] : '';

    $data = fetchData($conn, $sortBy, $filterBy, $filterValue);

    if (isset($_GET['export']) && $_GET['export'] == 'current') {
        $columns = ['Name','Email', 'Address', 'Institute_Roll_No', 'Institute_Registration_No', 'Class_10th_Percentage', 'Class_10th_Year_of_Passing', 'Class_12th_Percentage', 'Class_12th_Year_of_Passing', 'Physics_Marks_12th', 'Chemistry_Marks_12th', 'Math_Marks_12th', 'Course_Type', 'Diploma_Sem1_Percentage', 'Diploma_Sem2_Percentage', 'Diploma_Sem3_Percentage', 'Diploma_Sem4_Percentage', 'Diploma_Sem5_Percentage', 'Diploma_Sem6_Percentage', 'Diploma_Overall_Percentage', 'Diploma_Sem1_SGPA', 'Diploma_Sem2_SGPA', 'Diploma_Sem3_SGPA', 'Diploma_Sem4_SGPA', 'Diploma_Sem5_SGPA', 'Diploma_Sem6_SGPA', 'Diploma_Overall_CGPA', 'BE_Sem1_Percentage', 'BE_Sem2_Percentage', 'BE_Sem3_Percentage', 'BE_Sem4_Percentage', 'BE_Sem5_Percentage','BE_Average_Percentage', 'BE_Sem1_SGPA', 'BE_Sem2_SGPA', 'BE_Sem3_SGPA', 'BE_Sem4_SGPA', 'BE_Sem5_SGPA', 'BE_Average_CGPA', 'No_of_Current_Backlogs', 'Current_Back_Papers', 'Internship', 'Date_of_Birth', 'Gender','Upload_your_Resume'];
        generateExcel($data, $columns);
    } else {
        // Generate table rows
        while ($row = $data->fetch_assoc()) {
            echo "<tr>";
            echo "<td><input type='checkbox' name='select_row' value='{$row['Institute_Roll_No']}'></td>";
            echo "<td>{$row['Institute_Roll_No']}</td>";
            echo "<td>{$row['Name']}</td>";
            echo "<td>{$row['BE_Average_Percentage']}</td>";
            echo "<td>{$row['Class_12th_Percentage']}</td>";
            echo "<td>{$row['Class_10th_Percentage']}</td>";
            echo "<td>{$row['No_of_Current_Backlogs']}</td>";
            echo "<td>{$row['Internship']}</td>";
            echo "</tr>";
        }
    }
}

// Handle POST request for specific export
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['export_specific'])) {
        $selectedColumns = $_POST['selected_columns'] ?? [];
        if (!empty($selectedColumns)) {
            $selectedColumns = array_map('trim', $selectedColumns);
            $data = fetchData($conn, $_POST['sortBy'], $_POST['filterBy'], $_POST['filterValue']);
            generateExcel($data, $selectedColumns);
        } else {
            echo "No columns selected.";
        }
    }
}
?>
