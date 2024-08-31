<?php
session_start();
include('../includes/db_connect.php');

// Check if the admin is logged in
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../css/admin_dashboard.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            min-height: 100vh;
            flex-direction: row;
            background-color: #e6e6fa;
        }
        #sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            height: 100vh;
            background-color: #6f42c1;
            color: white;
            overflow-y: auto;
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        #main-content {
            margin-left: 250px;
            padding: 20px;
            width: calc(100% - 250px);
            overflow-y: auto;
        }
        table {
            background-color: #ffffff;
            border-radius: 0.5rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
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
        .logout-btn {
            background-color: #6f42c1;
            border: none;
            color: white;
            text-align: left;
            padding: 10px;
            font-size: 16px;
            width: 100%;
            position: relative;
            bottom: 0;
            display: flex;
            align-items: center;
            justify-content: start;
        }
        .logout-btn i {
            margin-right: 10px;
        }
        .logout-btn:hover {
            background-color: #5a32a3;
            text-decoration: none;
            color: white;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <nav id="sidebar">
        <div>
            <h4 class="text-center">Admin Dashboard</h4>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link text-white" href="#" data-page="manage_students.php">
                        <i class="bi bi-person"></i> Manage Student Details
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="#" data-page="manage_placements.php">
                        <i class="bi bi-briefcase"></i> Manage Placement Details
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="#" data-page="manage_notifications.php">
                        <i class="bi bi-bell"></i> Manage Notifications
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="#" data-page="view_messages.php">
                        <i class="bi bi-envelope"></i> Messages
                    </a>
                </li>
                <li class="nav-item" class="logout-btn">
                    <a class="nav-link text-white" href="#" data-page="try2.php" >
                        <i class="bi bi-file-earmark-spreadsheet"></i> Export to Excel
                    </a>
                </li>
            </ul>
        </div>
        <div>
            <div id="logoutButton" class="logout-btn">
                <i class="bi bi-box-arrow-left"></i> Logout
    </div>
        </div>
    </nav>

    <main id="main-content">
        <!-- Content will be loaded here dynamically -->
    </main>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            // Load default content
            loadContent('manage_students.php');

            // Handle sidebar clicks
            $('#sidebar .nav-link').on('click', function(e) {
                e.preventDefault();
                const page = $(this).data('page');
                loadContent(page);
            });

            // Handle logout button click
    $('#logoutButton').on('click', function(e) {
        e.preventDefault();
        $.ajax({
            url: '../includes/logout.php', // Create this PHP file to handle the logout
            method: 'POST',
            success: function() {
                window.location.href = 'admin_login.php';
            }
        });
    });

            // Function to load content via AJAX
            function loadContent(page) {
                $('#main-content').load(page);
            }
        });
    </script>
</body>
</html>
