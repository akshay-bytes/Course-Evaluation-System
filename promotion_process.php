<?php
// Include database connection file
include 'db_connect.php';

// Check if the form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if both current and new class IDs are provided
    if (isset($_POST['current_class'], $_POST['new_class'])) {
        // Sanitize and validate input
        $currentClass = mysqli_real_escape_string($conn, $_POST['current_class']);
        $newClass = mysqli_real_escape_string($conn, $_POST['new_class']);

        // Check if the current class and new class are different
        if ($currentClass != $newClass) {
            // Update the students' class IDs in the database
            $updateQuery = "UPDATE student_list SET class_id = $newClass WHERE class_id = $currentClass";
            if ($conn->query($updateQuery) === TRUE) {
                // Promotion successful
                echo "Students promoted successfully.";
            } else {
                // Error in SQL query
                echo "Error updating students' classes: " . $conn->error;
            }
        } else {
            // Same class selected for promotion
            echo "Please select a different class for promotion.";
        }
    } else {
        // Missing form data
        echo "Please provide both current and new class IDs.";
    }
} else {
    // Invalid request method
    echo "Invalid request method.";
}
