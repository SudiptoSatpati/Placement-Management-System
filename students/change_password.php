<?php
session_start();
include '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];
    
    // Fetch the stored password hash
    $username = $_SESSION['username'];
    $stmt = $conn->prepare("SELECT password FROM studentUsers WHERE Institute_Roll_No = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    if ($row && password_verify($currentPassword, $row['password'])) {
        if ($newPassword === $confirmPassword) {
            // Hash the new password
            $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
            
            // Update the password in the database
            $stmt = $conn->prepare("UPDATE studentUsers SET password = ? WHERE Institute_Roll_No = ?");
            $stmt->bind_param('ss', $hashedPassword, $username);
            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Password updated successfully.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update password.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'New passwords do not match.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Current password is incorrect.']);
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .card {
            background: linear-gradient(to right, #b3a4c1, #e8d0f1); /* Light purple to purple gradient */
            border-radius: 10px;
            padding: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            margin: auto;
        }
        .btn-green {
            background-color: #34d399; /* Green color */
            color: white;
            border: none;
        }
        .btn-green:hover {
            background-color: #10b981; /* Darker green for hover */
        }

        .otp-verification-container {
    text-align: center;
    margin: 20px auto;
    width: 300px;
}

.timer-container {
    margin-top: 10px;
}

#resend-otp-container {
    margin-top: 10px;
}

    </style>
</head>
<body>
    <!-- Main Container to Center the Content -->
<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <!-- Change Password Form -->
    <div class=" fixed bg-gradient-to-r from-purple-400 to-purple-600 rounded-lg shadow-lg p-6 w-96">
        <h2 class="text-xl font-semibold mb-4 text-white text-center">Change Password</h2>
        <form id="changePasswordForm">
            <div class="mb-4">
                <label for="current_password" class="block text-white">Current Password</label>
                <input type="password" id="current_password" name="current_password" class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm" required>
            </div>
            <div class="mb-4">
                <label for="new_password" class="block text-white">New Password</label>
                <input type="password" id="new_password" name="new_password" class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm" required>
            </div>
            <div class="mb-4">
                <label for="confirm_password" class="block text-white">Confirm New Password</label>
                <input type="password" id="confirm_password" name="confirm_password" class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm" required>
            </div>
            <div class="mb-4">
                <button type="submit" class="w-full bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded ">Change Password</button>
            </div>
            <div class="mb-4">
                <a href="#" class="block text-white" id="forgotPasswordLink">Forgot Password?</a>
            </div>
        </form>
        <div id="message" class="mt-4 text-center"></div>
    </div>

    <!-- Success Popup -->
    <div id="successPopup" class="fixed inset-0 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm mx-auto">
            <p class="text-lg font-semibold">Password updated successfully.</p>
            <button id="closePopup" class="mt-4 bg-green-500 hover:bg-green-700 text-white py-2 px-4 rounded">Close</button>
        </div>
    </div>

    <!-- Email verification Modal -->
    <div id="forgotPasswordModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm w-full">
        <h2 class="text-lg font-semibold mb-4">Forgot Password</h2>
        <form id="forgotPasswordForm" method="post">
            <div class="mb-4">
                <label for="email" class="block text-gray-700">Email</label>
                <input type="email" id="email" name="email" class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm" required>
                <div id="emailStatus" class="mt-2"></div>
            </div>
            <div class="mb-4">
                <button type="submit" name="forgot_password" class="btn-green px-4 py-2 rounded-md" id="sendOtpButton" disabled>Send OTP</button>
            </div>
            <button type="button" id="closeForgotPasswordModal" class="text-red-500 clsbtn">Cancel</button>
        </form>
    </div>

    <!-- OTP Verification Modal -->
<div id="otpModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm w-full">
        <h2 class="text-lg font-semibold mb-4">Enter OTP</h2>
        <div class="flex justify-center mb-4">
        <input type="text" id="otp1" maxlength="1"
                    class="otp-box text-center mx-1 px-1 py-1 border rounded w-8 border-2 border-gray-600 rounded w-8 bg-white" />
                <input type="text" id="otp2" maxlength="1"
                    class="otp-box text-center mx-1 px-1 py-1 border rounded w-8 border-2 border-gray-600 rounded w-8 bg-white" />
                <input type="text" id="otp3" maxlength="1"
                    class="otp-box text-center mx-1 px-1 py-1 border rounded w-8 border-2 border-gray-600 rounded w-8 bg-white" />
                <input type="text" id="otp4" maxlength="1"
                    class="otp-box text-center mx-1 px-1 py-1 border rounded w-8 border-2 border-gray-600 rounded w-8 bg-white" />
                <input type="text" id="otp5" maxlength="1"
                    class="otp-box text-center mx-1 px-1 py-1 border rounded w-8 border-2 border-gray-600 rounded w-8 bg-white" />
                <input type="text" id="otp6" maxlength="1"
                    class="otp-box text-center mx-1 px-1 py-1 border rounded w-8 border-2 border-gray-600 rounded w-8 bg-white" />
        </div>
        <div class="timer-container">
        <span id="timer">00:60</span>
    </div>
    <div id="resend-otp-container" style="display:none;">
        <a href="#" id="resend-otp-link">Resend OTP</a>
    </div>
        <button id="closeOtpModal" class="mt-4 text-red-500 clsbtn">Close</button>
        <div id="otpStatus" class="mt-4 text-center text-red-500"></div>
    </div>
</div>

<!-- New Password Modal -->
<div id="newPasswordModalB" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm w-full">
        <h2 class="text-lg font-semibold mb-4">Set New Password</h2>
        <form id="newPasswordFormB">
            <div class="mb-4">
                <label for="new_passwordB" class="block text-gray-700">New Password</label>
                <input type="password" id="new_passwordB" name="new_passwordB" class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm" required>
            </div>
            <div class="mb-4">
                <label for="confirm_passwordB" class="block text-gray-700">Confirm New Password</label>
                <input type="password" id="confirm_passwordB" name="confirm_passwordB" class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm" required>
            </div>
            <div class="mb-4">
                <button type="submit" id="changePasswordButtonB" class="w-full bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Change Password</button>
            </div>
            <div id="passwordMessageB" class="text-center"></div>
        </form>
        <button type="button" id="closeNewPasswordModalB" class="text-red-500">Close</button>
    </div>
</div>

<div id="loader" class="fixed inset-0 flex items-center justify-center bg-gray-700 bg-opacity-50 hidden z-50">
    <div class="animate-spin rounded-full h-32 w-32 border-t-4 border-b-4 border-purple-500"></div>
</div>


</div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {

            function showLoader() {
        $('#loader').removeClass('hidden');
    }

    function hideLoader() {
        $('#loader').addClass('hidden');
    }




            $('#changePasswordForm').submit(function(e) {
                e.preventDefault();

                showLoader(); // Show the loader
                
                var currentPassword = $('#current_password').val();
                var newPassword = $('#new_password').val();
                var confirmPassword = $('#confirm_password').val();

                $.post('change_password.php', {
                    current_password: currentPassword,
                    new_password: newPassword,
                    confirm_password: confirmPassword
                }, function(data) {
                    hideLoader(); // Hide the loader
                    var response = JSON.parse(data);
                    if (response.success) {
                        $('#message').text(response.message).removeClass('text-red-500').addClass('text-green-500');
                        $('#successPopup').removeClass('hidden');
                        $('#changePasswordForm').trigger('reset'); // Clear the form fields
                    } else {
                        $('#message').text(response.message).removeClass('text-green-500').addClass('text-red-500');
                    }
                }).fail(function(){
                    hideLoader();
                    alert("Failed to process request. Please try again");
                });
            });

            $('#closePopup').click(function() {
                $('#successPopup').addClass('hidden');
                $('#message').text('');
            });


            $('#forgotPasswordLink').on('click', function(e) {
                e.preventDefault();
                $('#forgotPasswordModal').removeClass('hidden');
            });

            $('.clsbtn').on('click', function() {
                $('#forgotPasswordModal').addClass('hidden');
            });
            //Email Verificationn

            $('#email').on('blur', function() {
                showLoader();
        const emailInput = $(this).val();
        const emailField = $(this);
        const emailStatus = $('#emailStatus');
        const sendOtpButton = $('#sendOtpButton');

        $.ajax({
    url: '../handler/email_verification.php',
    method: 'POST',
    data: { email: emailInput },
    success: function(response) {
        hideLoader();
        if (response.trim() === 'exists') {  // Use trim() to remove any whitespace
            $('#email').css('border', '2px solid green');

            emailStatus.text('Email found').css('color', 'green');
            $('#sendOtpButton').prop('disabled', false);
            $('#sendOtpButton').css('background-color','green');
            
        } else {
            emailField.css('border-color', 'red');
            emailStatus.text('Email not found').css('color', 'red');
            sendOtpButton.prop('disabled', true);
            $('#sendOtpButton').css('background-color','grey');
        }
    }
});
    });

    // Send OTP
    $('#sendOtpButton').on('click', function(e) {
    e.preventDefault();
    showLoader();
    const emailInput = $('#email').val();
    
    $.ajax({
        url: '../handler/send_otp.php',
        method: 'POST',
        data: { email: emailInput },
        dataType: 'json', // Expect JSON response
        success: function(response) {
            hideLoader();
            if (response.status === 'otp_sent') {
                $('#otpModal').removeClass('hidden');
                const sessionOtp = response.otp; // Get the OTP from the response
                console.log("OTP sent: " + sessionOtp);
            } else {
                alert('Failed to send OTP. Please try again.');
            }
        },
        error:function(){
            hideLoader();
            alert("Failed to send the otp. Please try again");
        }
    });
});




    ///////////////////////////////

    $('.otp-box').on('input', function() {
        const currentBox = $(this);
        const nextBox = currentBox.next('.otp-box');
        const prevBox = currentBox.prev('.otp-box');

        if (currentBox.val().length === 1 && nextBox.length) {
            nextBox.focus();
        } else if (currentBox.val().length === 0 && prevBox.length) {
            prevBox.focus();
        }
    });

    const otpInputs = $('.otp-box');
let otpEntered = '';

otpInputs.on('input', function() {
    otpEntered = '';
    otpInputs.each(function() {
        otpEntered += $(this).val();
    });

    console.log("OTP Entered: " + otpEntered);

    if (otpEntered.length === 6) {
        showLoader();
        $.ajax({
            url: 'verify_otp.php',
            method: 'POST',
            data: { otp: otpEntered },
            success: function(response) {
                hideLoader();
                if (response.success) {
                    otpInputs.each(function(index) {
                        $(this).css('border-color', 'green');
                    });

                    setTimeout(function() {
                        $('#otpModal').hide();
                        $('#newPasswordModalB').show();
                    }, 500);
                } else {
                    otpInputs.each(function(index) {
                        $(this).css('border-color', 'red');
                    });

                    setTimeout(function() {
                        otpInputs.each(function(index) {
                            $(this).css('border-color', '');
                        });
                    }, 1000);
                    alert("Incorrect. Please try again.");
                }
            },
            error: function() {
                hideLoader();
                alert("Failed to process request. Please try again.");
            }
        });
    }
});



    $('#changePasswordButtonB').on('click', function(e) {
        e.preventDefault(); // Prevent form submission

        const newPassword = $('#new_passwordB').val();
        const confirmPassword = $('#confirm_passwordB').val();
        const messageContainer = $('#passwordMessageB');

        // Validate form data
        if (newPassword === '' || confirmPassword === '') {
            messageContainer.text('All fields are required').css('color', 'red');
            return;
        }

        if (newPassword !== confirmPassword) {
            messageContainer.text('New passwords do not match').css('color', 'red');
            return;
        }

        showLoader();

        // console.log(<?php echo $_SESSION['username'] ;?>);

        // Submit the new password
        $.ajax({
            url: '../handler/reset_password.php',
            method: 'POST',
            data: {
                new_password: newPassword
            },
            success: function(response) {
                hideLoader();
                console.log(response);
                if (response.trim() === 'success') {
                    messageContainer.text('Password updated successfully').css('color', 'green');
                    setTimeout(function() {
                        $('#newPasswordModalB').hide();
                        location.reload(); // Reload the page after 1 second
                    }, 1000);
                } else {
                    messageContainer.text('Failed to update password').css('color', 'red');
                }
            },
            error: function() {
                hideLoader();
                messageContainer.text('An error occurred').css('color', 'red');
            }
        });
    });

    $('#closeNewPasswordModalB').on('click', function() {
        $('#newPasswordModalB').hide();
    });
    
     // Set the timer duration
     var timeLeft = 60;
    var timerElement = $('#timer');
    var resendOtpContainer = $('#resend-otp-container');

    // Function to start the timer
    function startTimer() {
        var countdown = setInterval(function() {
            if (timeLeft <= 0) {
                clearInterval(countdown);
                timerElement.hide();
                resendOtpContainer.show();
            } else {
                timerElement.text('00:' + (timeLeft < 10 ? '0' + timeLeft : timeLeft));
                timeLeft--;
            }
        }, 1000);
    }

    // Start the timer on page load
    startTimer();

    // Handle the Resend OTP link click
    $('#resend-otp-link').on('click', function(event) {
    event.preventDefault();

    showLoader();

    $.ajax({
        url: '../handler/get_email.php', // Adjust the path if needed
        method: 'GET',
        dataType: 'json', // Expect JSON response
        success: function(response) {
            if (response.status === 'success') {
                const emailInput = response.email;

                // Proceed with sending the OTP
                $.ajax({
                    url: '../handler/send_otp.php',
                    method: 'POST',
                    data: { email: emailInput },
                    dataType: 'json', // Expect JSON response
                    success: function(response) {
                        hideLoader();
                        if (response.status === 'otp_sent') {
                            $('#otpModal').removeClass('hidden');
                            const sessionOtp = response.otp; // Get the OTP from the response
                            console.log("OTP sent: " + sessionOtp);
                        } else {
                            alert('Failed to send OTP. Please try again.');
                        }
                    },
                    error: function() {
                        hideLoader();
                        alert("Failed to send the OTP. Please try again.");
                    }
                }).fail(function (){
                    alert("Unable to process request. Please try again.");
                });

                // Reset the timer and hide the resend link again
                timeLeft = 60;
                timerElement.show();
                resendOtpContainer.hide();
                startTimer();

            } else {
                hideLoader();
                alert(response.message);
            }
        },
        error: function() {
            hideLoader();
            alert("Failed to fetch the email. Please try again.");
        }
    });
});

})
    </script>
</body>
</html>