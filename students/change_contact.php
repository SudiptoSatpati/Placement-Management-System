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

<div class="flex justify-center mt-8">
    <div class="bg-gradient-to-r from-purple-400 to-purple-600 rounded-lg shadow-lg p-6 w-96">
        <h3 class="text-xl font-semibold mb-4 text-white text-center">Change Your Details</h3>
        <form id="changeDetailsForm">
            <div class="mb-4">
                <label for="attribute" class="block text-white">Select Attribute</label>
                <select id="attribute" name="attribute" class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm">
                    <option value="Email">Email</option>
                    <option value="Phone_No">Phone Number</option>
                    <option value="GitHub_Profile_URL">GitHub Profile URL</option>
                    <option value="Linked_In_Profile_URL">LinkedIn Profile URL</option>
                    <option value="Upload_your_Resume">Resume</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="value" class="block text-white">Enter New Value</label>
                <input type="text" id="value" name="value" class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm" required>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-white">Password</label>
                <input type="password" id="password" name="password" class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm" required>
            </div>
            <button type="submit" class="w-full bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Commit</button>
        </form>
        <div id="message" class="mt-4 text-center text-red-500"></div>
    </div>
</div>


<script>
$(document).ready(function() {
    $('#changeDetailsForm').submit(function(e) {
        e.preventDefault();
        var attribute = $('#attribute').val();
        var value = $('#value').val();
        var password = $('#password').val();

        $.post('update_details_process.php', {attribute: attribute, value: value, password: password}, function(data) {
            if (data.success) {
                $('#message').text('Details updated successfully.')
                    .removeClass('text-red-500')
                    .addClass('text-green-500');
                
                // Clear the form fields
                $('#value').val('');
                $('#password').val('');

                // Reload the page after a short delay
                setTimeout(function() {
                    location.reload();
                }, 1000);
            } else {
                $('#message').text(data.message)
                    .removeClass('text-green-500')
                    .addClass('text-red-500');
            }
        }, 'json');
    });
});

</script>

