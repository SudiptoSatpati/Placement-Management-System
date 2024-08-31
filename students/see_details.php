<?php
session_start();
include '../includes/db_connect.php';

$username = $_SESSION['username'];
$sql = "SELECT * FROM students WHERE Institute_Roll_No = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Details</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f3f4f6;
            font-family: Arial, sans-serif;
        }

        .container {
            margin-top: 20px;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            margin-top: 20px;
            background-color: #ffffff;
            border-radius: 0.5rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #6f42c1;
            color: white;
            width: 40%;
        }

        td {
            background-color: #e6e6fa;
            color: #4b0082;
            width: 60%;
        }

        input[type="checkbox"] {
            width: 20px;
            height: 20px;
        }

        .btn-commit {
            background-color: #6f42c1;
            color: white;
            padding: 10px 20px;
            font-size: 18px;
            border: none;
            border-radius: 5px;
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
        }

        .btn-commit:hover {
            background-color: #5a32a3;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <h3 class="text-xl font-semibold mb-4">Your Details</h3>
        <table>
            <tr>
                <th>Name</th>
                <td><?= htmlspecialchars($row['Name']) ?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?= htmlspecialchars($row['Email']) ?></td>
            </tr>
            <tr>
                <th>Address</th>
                <td><?= htmlspecialchars($row['Address']) ?></td>
            </tr>
            <tr>
                <th>Phone Number</th>
                <td><?= htmlspecialchars($row['Phone_No']) ?></td>
            </tr>
            <tr>
                <th>Institute Roll No</th>
                <td><?= htmlspecialchars($row['Institute_Roll_No']) ?></td>
            </tr>
            <tr>
                <th>Institute Registration No</th>
                <td><?= htmlspecialchars($row['Institute_Registration_No']) ?></td>
            </tr>
            <tr>
                <th>Class 10th Percentage</th>
                <td><?= htmlspecialchars($row['Class_10th_Percentage']) ?></td>
            </tr>
            <tr>
                <th>Class 10th Year of Passing</th>
                <td><?= htmlspecialchars($row['Class_10th_Year_of_Passing']) ?></td>
            </tr>
            <tr>
                <th>Class 12th Percentage</th>
                <td><?= htmlspecialchars($row['Class_12th_Percentage']) ?></td>
            </tr>
            <tr>
                <th>Class 12th Year of Passing</th>
                <td><?= htmlspecialchars($row['Class_12th_Year_of_Passing']) ?></td>
            </tr>
            <tr>
                <th>Physics Marks 12th</th>
                <td><?= htmlspecialchars($row['Physics_Marks_12th']) ?></td>
            </tr>
            <tr>
                <th>Chemistry Marks 12th</th>
                <td><?= htmlspecialchars($row['Chemistry_Marks_12th']) ?></td>
            </tr>
            <tr>
                <th>Math Marks 12th</th>
                <td><?= htmlspecialchars($row['Math_Marks_12th']) ?></td>
            </tr>
            <tr>
                <th>Course Type</th>
                <td><?= htmlspecialchars($row['Course_Type']) ?></td>
            </tr>
            <tr>
                <th>Diploma Sem 1 Percentage</th>
                <td><?= htmlspecialchars($row['Diploma_Sem1_Percentage']) ?></td>
            </tr>
            <tr>
                <th>Diploma Sem 2 Percentage</th>
                <td><?= htmlspecialchars($row['Diploma_Sem2_Percentage']) ?></td>
            </tr>
            <tr>
                <th>Diploma Sem 3 Percentage</th>
                <td><?= htmlspecialchars($row['Diploma_Sem3_Percentage']) ?></td>
            </tr>
            <tr>
                <th>Diploma Sem 4 Percentage</th>
                <td><?= htmlspecialchars($row['Diploma_Sem4_Percentage']) ?></td>
            </tr>
            <tr>
                <th>Diploma Sem 5 Percentage</th>
                <td><?= htmlspecialchars($row['Diploma_Sem5_Percentage']) ?></td>
            </tr>
            <tr>
                <th>Diploma Sem 6 Percentage</th>
                <td><?= htmlspecialchars($row['Diploma_Sem6_Percentage']) ?></td>
            </tr>
            <tr>
                <th>Diploma Overall Percentage</th>
                <td><?= htmlspecialchars($row['Diploma_Overall_Percentage']) ?></td>
            </tr>
            <tr>
                <th>Diploma Sem 1 SGPA</th>
                <td><?= htmlspecialchars($row['Diploma_Sem1_SGPA']) ?></td>
            </tr>
            <tr>
                <th>Diploma Sem 2 SGPA</th>
                <td><?= htmlspecialchars($row['Diploma_Sem2_SGPA']) ?></td>
            </tr>
            <tr>
                <th>Diploma Sem 3 SGPA</th>
                <td><?= htmlspecialchars($row['Diploma_Sem3_SGPA']) ?></td>
            </tr>
            <tr>
                <th>Diploma Sem 4 SGPA</th>
                <td><?= htmlspecialchars($row['Diploma_Sem4_SGPA']) ?></td>
            </tr>
            <tr>
                <th>Diploma Sem 5 SGPA</th>
                <td><?= htmlspecialchars($row['Diploma_Sem5_SGPA']) ?></td>
            </tr>
            <tr>
                <th>Diploma Sem 6 SGPA</th>
                <td><?= htmlspecialchars($row['Diploma_Sem6_SGPA']) ?></td>
            </tr>
            <tr>
                <th>Diploma Overall CGPA</th>
                <td><?= htmlspecialchars($row['Diploma_Overall_CGPA']) ?></td>
            </tr>
            <tr>
                <th>BE Sem 1 Percentage</th>
                <td><?= htmlspecialchars($row['BE_Sem1_Percentage']) ?></td>
            </tr>
            <tr>
                <th>BE Sem 2 Percentage</th>
                <td><?= htmlspecialchars($row['BE_Sem2_Percentage']) ?></td>
            </tr>
            <tr>
                <th>BE Sem 3 Percentage</th>
                <td><?= htmlspecialchars($row['BE_Sem3_Percentage']) ?></td>
            </tr>
            <tr>
                <th>BE Sem 4 Percentage</th>
                <td><?= htmlspecialchars($row['BE_Sem4_Percentage']) ?></td>
            </tr>
            <tr>
                <th>BE Sem 5 Percentage</th>
                <td><?= htmlspecialchars($row['BE_Sem5_Percentage']) ?></td>
            </tr>
            <tr>
                <th>BE Average Percentage</th>
                <td><?= htmlspecialchars($row['BE_Average_Percentage']) ?></td>
            </tr>
            <tr>
                <th>BE Sem 1 SGPA</th>
                <td><?= htmlspecialchars($row['BE_Sem1_SGPA']) ?></td>
            </tr>
            <tr>
                <th>BE Sem 2 SGPA</th>
                <td><?= htmlspecialchars($row['BE_Sem2_SGPA']) ?></td>
            </tr>
            <tr>
                <th>BE Sem 3 SGPA</th>
                <td><?= htmlspecialchars($row['BE_Sem3_SGPA']) ?></td>
            </tr>
            <tr>
                <th>BE Sem 4 SGPA</th>
                <td><?= htmlspecialchars($row['BE_Sem4_SGPA']) ?></td>
            </tr>
            <tr>
                <th>BE Sem 5 SGPA</th>
                <td><?= htmlspecialchars($row['BE_Sem5_SGPA']) ?></td>
            </tr>
           
            <tr>
                <th>BE Average SGPA</th>
                <td><?= htmlspecialchars($row['BE_Average_CGPA']) ?></td>
            </tr>
            <tr>
                <th>Number of Active Backlogs</th>
                <td><?= htmlspecialchars($row['No_of_Current_Backlogs']) ?></td>
            </tr>
            <tr>
                <th>Active Back Papers</th>
                <td><?= htmlspecialchars($row['Current_Back_Papers']) ?></td>
            </tr>
            <tr>
                <th>Internship</th>
                <td><?= htmlspecialchars($row['Internship']) ?></td>
            </tr>
            <tr>
                <th>Gender</th>
                <td><?= htmlspecialchars($row['Gender']) ?></td>
            </tr>
            <tr>
                <th>Date of Birth</th>
                <td><?= htmlspecialchars($row['Date_of_Birth']) ?></td>
            </tr>
            <tr>
                <th>Linked In Profile</th>
                <td><?= htmlspecialchars($row['Linked_In_Profile_URL']) ?></td>
            </tr>
            <tr>
                <th>Github Profile</th>
                <td><?= htmlspecialchars($row['GitHub_Profile_URL']) ?></td>
            </tr>
            <tr>
                <th>Resume</th>
                <td><?= htmlspecialchars($row['Upload_your_Resume']) ?></td>
            </tr>
        </table>
        <button class="btn-commit">Commit Changes</button>
    </div>
</body>
</html>
