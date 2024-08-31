<?php
session_start();

include('../includes/db_connect.php');

if (!isset($_SESSION['username'])) {
    header('Location: student_login.php');
    exit();
}

// Fetch student details
$username = $_SESSION['username'];
$query = "SELECT Name, Email, Phone_No FROM students WHERE Institute_roll_no = '$username'";
$result = mysqli_query($conn, $query);
$student = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="../css/student_dashboard.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            min-height: 100vh;
            flex-direction: row;
            background-color: #f3f4f6;
        }

        #sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 300px;
            height: 100vh;
            background-color: #4b0082;
            color: white;
            overflow-y: auto;
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .profile-card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 15px;
            text-align: center;
            margin-bottom: 20px;
            color: #333;
            margin-top: 20px;
        }

        .profile-card h5 {
            margin: 10px 0;
            font-weight: bold;
            color: #4b0082;
        }

        .profile-card p {
            margin: 5px 0;
            font-size: 14px;
        }

        #main-content {
            margin-left: 250px;
            padding: 20px;
            width: calc(100% - 250px);
            overflow-y: auto;
        }

        .nav-item {
            margin-bottom: 10px;
        }

        .nav-link {
            font-size: 18px;
            color: white;
        }

        .nav-link i {
            margin-right: 10px;
        }

        .nav-link.active {
            background-color: #6f42c1;
            color: white;
            border-radius: 5px;
        }

        .nav-link:hover {
            background-color: #6f42c1;
            color: white;
            border-radius: 5px;
        }

        .logout-btn {
            background-color: #4b0082;
            border: none;
            color: white;
            text-align: left;
            padding: 10px;
            font-size: 16px;
            width: 100%;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: start;
            cursor: pointer;
            margin-top: auto;
        }

        .logout-btn i {
            margin-right: 10px;
        }

        .logout-btn:hover {
            background-color: #6f42c1;
            color: white;
            text-decoration: none;
        }
    </style>
</head>
<body>
<nav id="sidebar">
    <div>
    <h4 class="text-center">Student Dashboard</h4>
        <div class="profile-card">
            <h5><?php echo $student['Name']; ?></h5>
            <p><?php echo $student['Email']; ?></p>
            <p><?php echo $student['Phone_No']; ?></p>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link text-white" href="#" data-page="see_details.php">
                    <i class="bi bi-person"></i> See Details
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="#" data-page="change_contact.php">
                    <i class="bi bi-envelope"></i> Change Contact
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="#" data-page="change_password.php">
                    <i class="bi bi-key"></i> Change Password
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="#" data-page="initiate_change.php">
                    <i class="bi bi-pencil-square"></i> Initiate Data Change
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="#" data-page="form.php">
                <i class="bi bi-file-earmark-text"></i>Form
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="#" data-page="form.php">
                <i class="bi bi-inbox-fill"></i>Inbox

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
    $(document).ready(function () {
        // Load default content
        loadContent('see_details.php');

        // Handle sidebar clicks
        $('#sidebar .nav-link').on('click', function (e) {
            e.preventDefault();
            $('#sidebar .nav-link').removeClass('active');
            $(this).addClass('active');
            const page = $(this).data('page');
            loadContent(page);
        });

        // Logout button functionality
        $('#logoutButton').on('click', function (e) {
            e.preventDefault();
            $.ajax({
                url: '../includes/logout_student.php', // Create this PHP file to handle the logout
                method: 'POST',
                success: function () {
                    window.location.href = 'student_login.php';
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
