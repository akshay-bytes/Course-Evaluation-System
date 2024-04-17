<?php
// Include database connection file
include 'db_connect.php';

// Check if class_id and subject_id are set and numeric
if (isset($_GET['class_id'], $_GET['subject_id']) && is_numeric($_GET['class_id']) && is_numeric($_GET['subject_id'])) {
    // Sanitize inputs to prevent SQL injection
    $class_id = mysqli_real_escape_string($conn, $_GET['class_id']);
    $subject_id = mysqli_real_escape_string($conn, $_GET['subject_id']);

    // Prepare SQL statement using prepared statement
    $stmt = $conn->prepare("SELECT DISTINCT
    s.school_id,
    s.firstname,
    s.lastname,
    c.curriculum,
    c.level,
    c.section AS sec,
    subj.subject
FROM 
    student_list s
JOIN 
    class_list c ON s.class_id = c.id
JOIN 
    subject_list subj ON subj.id = ?
LEFT JOIN 
    evaluation_list e ON s.id = e.student_id AND e.subject_id = ?
WHERE
    s.class_id = ?
    AND e.student_id IS NULL");

    // Fetch students data based on class and section
    // $query = "SELECT * FROM students WHERE evaluated = 0 AND class_id = $classId ORDER BY class_id, section";

    // Bind parameters
    $stmt->bind_param("iii", $subject_id, $subject_id, $class_id);

    // Execute query
    $stmt->execute();

    // Get result
    $result = $stmt->get_result();

    $students = array();

    if ($result->num_rows > 0) {
        // Fetching and appending each row of student data to the array
        while ($row = $result->fetch_assoc()) {
            $students[] = $row;
        }
    }

    // Close statement and database connection
    $stmt->close();
    $conn->close();

    // Return students data as JSON
    echo json_encode($students);
} else {
    echo "Invalid input parameters.";
}
