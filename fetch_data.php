<?php
// Include database connection file
include 'db_connect.php';

// Fetch data based on selected criteria
$class_id = $_POST['class_id'];

// Construct SQL query based on selected criteria
$sql = "SELECT s.*, CONCAT(s.firstname, ' ', s.lastname) AS name, c.curriculum, c.level, c.section 
FROM student_list s 
LEFT JOIN class_list c ON s.class_id = c.id";

if (!empty($class_id) && $class_id !== 'All') {
    $sql .= !empty($academic_year) && $academic_year !== 'All' ? " AND c.id = '$class_id'" : " WHERE c.id = '$class_id'";
}

$sql .= " ORDER BY CONCAT(s.firstname, ' ', s.lastname) ASC";

$result = $conn->query($sql);

if ($result) {
    // Fetch data and store in array
    $data = array();
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    // Return fetched data as JSON
    echo json_encode($data);
} else {
    // Handle query error
    echo json_encode(array('error' => 'Error executing query: ' . $conn->error));
}

// Close database connection
$conn->close();
