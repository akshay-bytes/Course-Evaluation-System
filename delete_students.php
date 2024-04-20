<?php
include 'db_connect.php';

if (isset($_POST['class_id'])) {
    $class_id = mysqli_real_escape_string($conn, $_POST['class_id']);
    // Delete all students associated with the selected class
    $delete_query = $conn->query("DELETE FROM student_list WHERE class_id = '$class_id'");
    if ($delete_query) {
        echo 1; // Success response
    } else {
        echo 0; // Error response
    }
} else {
    echo "Class ID not provided.";
}
