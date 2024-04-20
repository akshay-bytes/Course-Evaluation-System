<?php
// Include database connection file
include 'db_connect.php';

// Check if subject or class ID is provided
$subject_id = isset($_GET['subject_id']) ? $_GET['subject_id'] : '';
$class_id = isset($_GET['class_id']) ? $_GET['class_id'] : '';
$faculty_id = isset($_GET['faculty_id']) ? $_GET['faculty_id'] : '';

// Prepare the SQL query with parameters for subject or class ID
$query = "SELECT 
    q.id AS question_id,
    q.question AS question_text,
    SUM(CASE WHEN ea.rate = 5 THEN 1 ELSE 0 END) AS strongly_agree_count,
    SUM(CASE WHEN ea.rate = 4 THEN 1 ELSE 0 END) AS agree_count,
    SUM(CASE WHEN ea.rate = 3 THEN 1 ELSE 0 END) AS uncertain_count,
    SUM(CASE WHEN ea.rate = 2 THEN 1 ELSE 0 END) AS disagree_count,
    SUM(CASE WHEN ea.rate = 1 THEN 1 ELSE 0 END) AS strongly_disagree_count
FROM evaluation_answers ea
JOIN question_list q ON ea.question_id = q.id";

// Add WHERE clause to filter data based on subject or class ID
if (!empty($subject_id)) {
    $query .= " WHERE q.subject_id = '$subject_id'";
} elseif (!empty($class_id)) {
    $query .= " WHERE q.class_id = '$class_id'";
}

$query .= " GROUP BY q.id, q.question";

$result = $conn->query($query);

// Check if query was successful
if ($result && $result->num_rows > 0) {
    $data = array(); // Initialize an empty array to store all question data

    // Fetch each row from the result set
    while ($row = $result->fetch_assoc()) {
        // Store the row data into the $data array
        $data[] = array(
            'question_id' => $row['question_id'],
            'question_text' => $row['question_text'],
            'strongly_agree' => $row['strongly_agree_count'],
            'agree' => $row['agree_count'],
            'uncertain' => $row['uncertain_count'],
            'disagree' => $row['disagree_count'],
            'strongly_disagree' => $row['strongly_disagree_count']
        );
    }

    // Output data as JSON
    echo json_encode($data);
} else {
    // No data found
    echo json_encode(array('error' => 'No evaluation data found.'));
}
