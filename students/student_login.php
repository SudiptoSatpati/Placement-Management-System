<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/student_login.css" rel="stylesheet">
    <style>
        body {
            background: #f0f4f8;
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md p-6 bg-white rounded-lg shadow-md">
        <h2 class="text-2xl font-semibold text-center mb-6">Student Login</h2>
        <form id="loginForm">
            <div class="mb-4">
                <label for="username" class="block text-gray-700">Username</label>
                <input type="text" id="username" name="username" class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-gray-700">Password</label>
                <input type="password" id="password" name="password" class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
            </div>
            <button type="submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Login</button>
        </form>
        <div id="otpSection" class="hidden mt-4">
            <h3 class="text-xl font-semibold mb-4">Enter OTP</h3>
            <form id="otpForm">
                <div class="mb-4">
                    <label for="otp" class="block text-gray-700">OTP</label>
                    <input type="text" id="otp" name="otp" class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                </div>
                <button type="submit" class="w-full bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Submit OTP</button>
            </form>
        </div>
        <div id="message" class="mt-4 text-center text-red-500"></div>
    </div>

    <!-- Loader HTML -->
    <div id="loader" class="hidden fixed inset-0 flex justify-center items-center bg-gray-500 bg-opacity-50 z-50">
        <div class="spinner-border text-primary" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#loginForm').submit(function(e) {
            e.preventDefault();
            console.log("Login form submitted");

            $('#loader').removeClass('hidden'); // Show the loader
            var username = $('#username').val();
            var password = $('#password').val();

            console.log("Username: " + username);
            console.log("Password: " + password);

            $.post('login.php', {username: username, password: password}, function(data) {
                console.log("Response from login.php:", data);
                $('#loader').addClass('hidden'); // Hide the loader

                if (data.success) {
                    console.log("Login successful, showing OTP section.");
                    $('#loginForm').hide();
                    $('#otpSection').show();
                } else {
                    console.log("Login failed:", data.message);
                    $('#message').text(data.message);
                }
            }, 'json').fail(function(xhr, status, error) {
                console.log("Error occurred during AJAX request:", error);
                $('#loader').addClass('hidden'); // Hide the loader in case of error
                $('#message').text("An error occurred. Please try again.");
            });
        });

        $('#otpForm').submit(function(e) {
            e.preventDefault();
            console.log("OTP form submitted");

            var otp = $('#otp').val();
            console.log("Entered OTP: " + otp);

            $.post('verify_otp.php', {otp: otp}, function(data) {
                console.log("Response from verify_otp.php:", data);

                if (data.success) {
                    console.log("OTP verified, redirecting to dashboard.");
                    window.location.href = 'student_dashboard.php';
                } else {
                    console.log("OTP verification failed:", data.message);
                    $('#message').text(data.message);
                }
            }, 'json').fail(function(xhr, status, error) {
                console.log("Error occurred during OTP verification:", error);
                $('#message').text("An error occurred. Please try again.");
            });
        });
    });
    </script>
</body>
</html>
