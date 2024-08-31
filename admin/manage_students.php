<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Student Details</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #e6e6fa;
        }
        .table-container {
            max-height: 650px; /* Set the desired height for the scrollable area */
        }
        .table-scroll {
            height: 100%;
            overflow-y: scroll; /* Enable vertical scrolling */
            -ms-overflow-style: none; /* IE and Edge */
            scrollbar-width: none; /* Firefox */
        }
        .table-scroll::-webkit-scrollbar {
            display: none; /* Chrome, Safari, and Opera */
        }
        table {
            background-color: transparent;
            border-radius: 0.5rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
            width: 100%;
            border-collapse: separate; /* Prevent border overlap issues */
            filter: blur(0.8);
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
        .search-container {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 20px;
        }
        .search-input {
            max-width: 300px;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h2 class="mb-4">Manage Student Details</h2>
        <div class="search-container">
            <input type="text" id="searchInput" class="form-control search-input" placeholder="Search by Name or Roll No.">
        </div>
        <div class="table-container">
            <div class="table-scroll">
                <table class="table table-striped" id="studentTable">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Institute Roll No.</th>
                            <th>BE Average %</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="studentTableBody">
                        <!-- Table rows will be dynamically populated here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            // Function to load student data via AJAX
            function loadStudents(query = '') {
                $.ajax({
                    url: '../handler/fetch_students.php',
                    method: 'GET',
                    data: { search: query },
                    success: function(data) {
                        $('#studentTableBody').html(data);
                    }
                });
            }

            // Load students on page load
            loadStudents();

            // Search input event listener
            $('#searchInput').on('input', function() {
                const query = $(this).val();
                loadStudents(query);
            });
        });
    </script>
</body>
</html>
