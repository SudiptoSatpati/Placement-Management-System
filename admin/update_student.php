
<?php

// Include database connection
include('../includes/db_connect.php');

// Check if the ID parameter is set
if (!isset($_GET['id'])) {
    die('No student ID provided.');
}

$id = $conn->real_escape_string($_GET['id']);
$query = "SELECT * FROM students WHERE `Institute_Roll_No` = '$id'";
$result = $conn->query($query);

if ($result->num_rows === 0) {
    die('No student found.');
}

$student = $result->fetch_assoc();

// List of fields to be updated
$fields = [
    'Name', 'Email', 'Address', 'Phone_No', 'Institute_Roll_No', 'Institute_Registration_No',
    'Class_10th_Percentage', 'Class_10th_Year_of_Passing', 'Class_12th_Percentage', 
    'Class_12th_Year_of_Passing', 'Physics_Marks_12th', 
    'Chemistry_Marks_12th', 'Math_Marks_12th', 
    'Course_Type', 'Diploma_Sem1_Percentage', 'Diploma_Sem2_Percentage', 'Diploma_Sem3_Percentage','Diploma_Sem4_Percentage', 
    'Diploma_Sem5_Percentage','Diploma_Sem6_Percentage','Diploma_Overall_Percentage', 'Diploma_Sem1_SGPA', 
    'Diploma_Sem2_SGPA', 'Diploma_Sem3_SGPA', 'Diploma_Sem4_SGPA', 'Diploma_Sem5_SGPA', 
    'Diploma_Sem6_SGPA', 'Diploma_Overall_CGPA', 'BE_Sem1_Percentage', 'BE_Sem2_Percentage', 'BE_Sem3_Percentage', 
    'BE_Sem4_Percentage', 'BE_Sem5_Percentage', 'BE_Average_Percentage', 'BE_Sem1_SGPA', 'BE_Sem2_SGPA', 
    'BE_Sem3_SGPA', 'BE_Sem4_SGPA', 'BE_Sem5_SGPA', 'BE_Average_CGPA', 'No_of_Current_Backlogs', 
    'Current_Back_Papers', 'Gender', 'Date_of_Birth', 'Internship', 'Linked_In_Profile_URL', 
    'GitHub_Profile_URL', 'Upload_your_Resume'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Prepare update query
    $update_fields = [];
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            $value = $conn->real_escape_string(trim($_POST[$field])); // Use trim() to remove extra spaces
            $update_fields[] = "`" . str_replace("`", "``", $field) . "` = '$value'";
        } else {
            // Debugging: Show fields not set
            echo '<pre>Field not set: ' . htmlspecialchars($field) . '</pre>';
        }
    }

    if (!empty($update_fields)) {
        $update_query = "UPDATE students SET " . implode(', ', $update_fields) . " WHERE `Institute_Roll_No` = '$id'";

        // Debugging: Print the SQL query to ensure it's correct
        // echo '<pre>' . htmlspecialchars($update_query) . '</pre>';

        if ($conn->query($update_query) === TRUE) {
            $success_message = "Data updated successfully!";
            // Refresh the student data
            $result = $conn->query($query);
            $student = $result->fetch_assoc();
        } else {
            $error_message = "Error updating data: " . $conn->error;
        }
    } else {
        $error_message = "No data to update.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Student Details</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="../css/update_student.css" rel="stylesheet">
    <style>
        body {
            background-color: #e6e6fa;
        }
        
        .container {
            max-width: 900px;
            margin-top: 30px;
        }

        .title{
            text-align: center;
        }
        .card {
            border-radius: 0.5rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            max-height: 600px;
            overflow: scroll;
            background-color: transparent;
            filter: blur(0.8);
        }
        .btn-custom {
            background-color: #6f42c1;
            color: white;
        }
        .btn-custom:hover {
            background-color: #5a32a3;
        }
        .btn-copy {
            background-color: #28a745;
            color: white;
        }
        .btn-copy:hover {
            background-color: #218838;
        }
        .btn-back {
            background-color: #6c757d;
            color: white;
        }
        .btn-back:hover {
            background-color: #5a6268;
        }
        input[readonly] {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>
    <div class="container">
    <h2 class="card-title mb-4 title">Update Student Details</h2>
        <div class="card">
           
            <div class="card-body">
               
                
                <?php if (isset($success_message)): ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
                <?php elseif (isset($error_message)): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
                <?php endif; ?>

                <form id="studentForm" method="post">
                    <?php foreach ($student as $key => $value): ?>
                        <div class="form-group">
                            <label for="<?php echo htmlspecialchars($key); ?>"><?php echo htmlspecialchars($key); ?>:</label>
                            <input type="text" id="<?php echo htmlspecialchars($key); ?>" name="<?php echo htmlspecialchars($key); ?>" class="form-control" value="<?php echo htmlspecialchars($value); ?>" <?php echo in_array($key, $fields) ? 'readonly' : ''; ?>>
                        </div>
                    <?php endforeach; ?>

                    <!-- <button type="button" id="editButton" class="btn btn-custom">Edit</button>
                    <button type="submit" id="saveButton" class="btn btn-custom" style="display: none;">Save</button>
                    <a href="admin_dashboard.php" class="btn btn-back">Back</a>
                    <button type="button" id="copyButton" class="btn btn-copy">Copy Details</button> -->
                </form>
            </div>
        </div>
    </div>
    <div class="buttons-css">
    <button type="button" id="editButton" class="btn btn-custom">Edit</button>
                    <button type="submit" id="saveButton" class="btn btn-custom" style="display: none;">Save</button>
                    <a href="admin_dashboard.php" class="btn btn-back">Back</a>
                    <button type="button" id="copyButton" class="btn btn-copy">Copy Details</button>
                    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        document.getElementById('editButton').addEventListener('click', function() {
            const inputs = document.querySelectorAll('input');
            inputs.forEach(input =>{if (input.id !== 'Institute_Roll_No') {
            input.removeAttribute('readonly');}}) 
            this.style.display = 'none';
            document.getElementById('saveButton').style.display = 'inline-block';
        });

        document.getElementById('copyButton').addEventListener('click', function() {
            const fields = document.querySelectorAll('input');
            let details = '';
            fields.forEach(field => details += field.previousElementSibling.textContent + ': ' + field.value + '\n');
            navigator.clipboard.writeText(details).then(() => {
                alert('Details copied to clipboard');
            });
        });

        document.getElementById('saveButton').addEventListener('click', function() {
    document.getElementById('studentForm').submit();
});
    </script>
</body>
</html>
