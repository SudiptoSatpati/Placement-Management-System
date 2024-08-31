<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Initiate Change</title>
    <!-- Include Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .selected {
            background-color: #48bb78 !important; /* Green for selected */
            color: white !important; /* Ensure text is visible */
        }

        .attribute-box {
            cursor: pointer;
            transition: background-color 0.3s, color 0.3s;
            background: linear-gradient(135deg, #d1c4e9, #9575cd); /* Purple gradient */
        }

        .initiate-change-container {
            max-width: 800px;
            margin: 0 auto; /* Center the container */
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .attribute-box-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 16px;
        }
    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

<div class="initiate-change-container">
    <!-- Attribute Selection Boxes -->
    <div class="attribute-box-container text-center mx-auto">
        <div class="attribute-box p-4 text-white rounded-lg" data-attribute="name">Name</div>
        <div class="attribute-box p-4 text-white rounded-lg" data-attribute="email">Email</div>
        <div class="attribute-box p-4 text-white rounded-lg" data-attribute="phone">Phone</div>
        <div class="attribute-box p-4 text-white rounded-lg" data-attribute="age">Age</div>
        <div class="attribute-box p-4 text-white rounded-lg" data-attribute="gender">Gender</div>
        <div class="attribute-box p-4 text-white rounded-lg" data-attribute="course_type">Course Type</div>
        <div class="attribute-box p-4 text-white rounded-lg" data-attribute="be_avg_percentage">BE Average Percentage</div>
        <div class="attribute-box p-4 text-white rounded-lg" data-attribute="12th_avg_percentage">12th Average Percentage</div>
        <div class="attribute-box p-4 text-white rounded-lg" data-attribute="10th_avg_percentage">10th Average Percentage</div>
        <div class="attribute-box p-4 text-white rounded-lg" data-attribute="diploma_avg_percentage">Diploma Average Percentage</div>
        <div class="attribute-box p-4 text-white rounded-lg" data-attribute="active_backlogs">No of Active Backlogs</div>
        <div class="attribute-box p-4 text-white rounded-lg" data-attribute="internship">Internship</div>
    </div>

    <!-- Selected Attributes and Input Boxes -->
    <div class="selected-attributes mt-6">
        <!-- Selected attributes will appear here dynamically -->
    </div>

    <!-- Initiate Button -->
    <div class="initiate-button-container mt-8 text-center">
        <button id="initiate-change" class="bg-purple-500 text-white px-6 py-2 rounded-lg hover:bg-purple-700">Initiate Change</button>
    </div>
</div>

<!-- Include jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $('.attribute-box').on('click', function() {
            var $this = $(this);
            var attribute = $this.data('attribute');

            // Toggle selection
            $this.toggleClass('selected');

            if ($this.hasClass('selected')) {
                // Add selected attribute with input box
                var selectedAttribute = `
                    <div class="selected-attribute flex items-center mb-4" id="selected-${attribute}">
                        <span class="font-semibold">${attribute.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())}:</span>
                        <input type="text" class="ml-4 p-2 border border-gray-300 rounded-lg flex-grow" placeholder="Enter new ${attribute.replace(/_/g, ' ')}" name="${attribute}">
                    </div>`;
                $('.selected-attributes').append(selectedAttribute);
            } else {
                // Remove deselected attribute
                $('#selected-' + attribute).remove();
            }
        });

        $('#initiate-change').on('click', function() {
            // Logic to handle the initiation of the change will be implemented next
            alert('Initiate change clicked!');
        });
    });
</script>

</body>
</html>
