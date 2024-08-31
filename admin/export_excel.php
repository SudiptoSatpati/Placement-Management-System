<?php
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Database connection
include('../includes/db_connect.php');

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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Student Details</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #e6e6fa;
        }
        .table-container {
            max-height: 650px;
        }
        .table-scroll {
            height: 100%;
            overflow-y: scroll;
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        .table-scroll::-webkit-scrollbar {
            display: none;
        }
        table {
            background-color: transparent;
            border-radius: 0.5rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
            width: 100%;
            border-collapse: separate;
        }
        thead th {
            background-color: #6f42c1;
            color: white;
            text-align: center;
        }
        tbody td {
            text-align: center;
        }
        .btn-warning {
            background-color: #e0a800;
            border-color: #d39e00;
        }
        .btn-warning:hover {
            background-color: #d39e00;
            border-color: #c69500;
        }
        .filter-sort-container {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 10px;
        }
        .filter-sort-container form {
            display: flex;
            align-items: center;
        }
        .filter-sort-container select,
        .filter-sort-container input {
            margin-left: 10px;
        }
        .export-buttons {
            position: fixed;
            top: 10px;
            right: 10px;
        }
        .export-buttons .btn {
            background-color: #28a745;
            color: white;
            border: none;
            margin-bottom: 5px;
        }
        .export-buttons .btn:hover {
            background-color: #218838;
        }
        .btn-select {
            background-color: #007bff;
            color: white;
        }
        .btn-select:hover {
            background-color: #0056b3;
        }
        .modal-content {
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h2 class="mb-4">Export to Excel</h2>
        <div class="filter-sort-container">
            <form id="filterSortForm" method="GET">
                <label for="sortBy">Sort By:</label>
                <select id="sortBy" name="sortBy" class="form-control">
                    <option value="Institute_Roll_No">Roll No.</option>
                    <option value="Name">Name</option>
                    <option value="BE_Average_Percentage">BE Average %</option>
                </select>

                <label for="filterBy">Filter By:</label>
                <select id="filterBy" name="filterBy" class="form-control">
                    <option value="">Select</option>
                    <option value="BE_Average_Percentage">BE Average %</option>
                    <option value="Class_12th_Percentage">12th Average %</option>
                    <option value="Class_10th_Percentage">10th Average %</option>
                    <option value="Course_Type">Course Type</option>
                    <option value="Diploma_Overall_Percentage">Diploma Avg %</option>
                    <option value="No_of_Current_Backlogs">No of Active Backlogs</option>
                    <option value="Internship">Internship</option>
                    <option value="Gender">Gender</option>
                    <option value="Age">Age</option>
                </select>
                <input type="text" id="filterValue" name="filterValue" class="form-control" placeholder="Filter value">
                <button type="submit" class="btn btn-primary ml-2">Apply</button>
            </form>
        </div>

        <div class="table-container table-scroll">
            <table class="table table-hover" id="studentTable" style="border: 2px solid #8D5EB7;">
                <thead>
                    <tr >
                        <th style="color: #8D5EB7;">Select</th>
                        <th style="color: #8D5EB7;">Roll No.</th>
                        <th style="color: #8D5EB7;">Name</th>
                        <th style="color: #8D5EB7;">BE Average %</th>
                        <th style="color: #8D5EB7;">12th Average %</th>
                        <th style="color: #8D5EB7;">10th Average %</th>
                        <th style="color: #8D5EB7;">Backlogs</th>
                        <th style="color: #8D5EB7;">Internship</th>
                    </tr>
                </thead>
                <tbody style="background-color: #8D5EB7; color: white">
                    <!-- Data will be loaded here dynamically -->
                </tbody>
            </table>
        </div>

        <div class="export-buttons">
            <button id="exportCurrent" class="btn btn-lg">Export Current Data</button>
            <button id="advancedFiltersBtn" class="btn btn-lg btn-select" data-bs-toggle="modal" data-bs-target="#advancedFiltersModal">Advanced Filters</button>

        </div>

        <!-- Modal for specific export -->
        <!-- Modal for Advanced Filters -->
<div class="modal fade" id="advancedFiltersModal" tabindex="-1" aria-labelledby="advancedFiltersModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="advancedFiltersForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="advancedFiltersModalLabel">Advanced Filters</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="filterBy">Filter By:</label>
                        <select id="filterBy" name="filterBy[]" class="form-control" multiple>
                            <option value="BE_Average_Percentage">BE Average %</option>
                            <option value="Class_12th_Percentage">12th Average %</option>
                            <option value="Class_10th_Percentage">10th Average %</option>
                            <option value="Course_Type">Course Type</option>
                            <option value="Diploma_Overall_Percentage">Diploma Avg %</option>
                            <option value="No_of_Current_Backlogs">No of Active Backlogs</option>
                            <option value="Internship">Internship</option>
                            <option value="Gender">Gender</option>
                            <option value="Age">Age</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="filterValues">Filter Values (comma-separated):</label>
                        <input type="text" id="filterValues" name="filterValues" class="form-control" placeholder="e.g. 70,80">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                </div>
            </form>
        </div>
    </div>
</div>

    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            function loadTable() {
                $.ajax({
                    url: 'data_handler.php',
                    type: 'GET',
                    data: $('#filterSortForm').serialize(),
                    success: function(response) {
                        $('#studentTable tbody').html(response);
                    }
                });
            }

            $('#filterSortForm').on('submit', function(e) {
                e.preventDefault();
                loadTable();
            });

            $('#exportCurrent').on('click', function() {
                window.location.href = 'data_handler.php?export=current&' + $('#filterSortForm').serialize();
            });

            $('#specificExportForm').on('submit', function(e) {
                e.preventDefault();
                var selectedIds = [];
                $('button[data-selected="true"]').each(function() {
                    selectedIds.push($(this).data('roll'));
                });
                if (selectedIds.length > 0) {
                    $('#selectedIds').val(JSON.stringify(selectedIds));
                    $.ajax({
                        url: 'data_handler.php',
                        type: 'POST',
                        data: $('#specificExportForm').serialize(),
                        success: function(response) {
                            var blob = new Blob([response], {type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'});
                            var link = document.createElement('a');
                            link.href = URL.createObjectURL(blob);
                            link.download = 'specific_data.xlsx';
                            link.click();
                        }
                    });
                } else {
                    alert('No attributes selected.');
                }
            });

            loadTable();
        });
    </script>
</body>
</html>