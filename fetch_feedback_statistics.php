<?php
// Include database connection file
include 'db_connect.php';

// Constants for pagination
$rowsPerPage = 10;

// Retrieve parameters from the URL
$classId = isset($_GET['class_id']) ? $_GET['class_id'] : '';
$subjectId = isset($_GET['subject_id']) ? $_GET['subject_id'] : '';
$page = isset($_GET['page']) ? $_GET['page'] : 1;

// Calculate offset for pagination
$offset = ($page - 1) * $rowsPerPage;

// Fetch feedback statistics data with pagination
$query = "SELECT 
    r.class_id AS class_id,
    r.subject_id AS subject_id,
    sl.code AS subject_code,
    sl.subject AS subject_name,
    COUNT(DISTINCT s.id) AS total_students,
    COUNT(DISTINCT CASE WHEN e.student_id IS NOT NULL THEN e.student_id END) AS students_evaluated_feedback,
    COUNT(DISTINCT CASE WHEN e.student_id IS NULL THEN s.id END) AS students_not_evaluated_feedback
FROM 
    student_list s
JOIN 
    restriction_list r ON s.class_id = r.class_id
LEFT JOIN 
    evaluation_list e ON s.id = e.student_id AND r.subject_id = e.subject_id
LEFT JOIN
    subject_list sl ON r.subject_id = sl.id
WHERE
    r.class_id = $classId AND r.subject_id = $subjectId
GROUP BY 
    r.class_id, r.subject_id, sl.code, sl.subject
-- LIMIT $offset, $rowsPerPage";

$result = $conn->query($query);

// Check if there are any results
if ($result && $result->num_rows > 0) {
    // Start generating HTML content
    ob_start();
?>
    <table class="table table-hover table-bordered" id="list">
        <thead>
            <tr>
                <th>Course Code</th>
                <th>Course Name</th>
                <th>Total Students</th>
                <th>Students with Feedback</th>
                <th>Students without Feedback</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetching and displaying each row
            while ($row = $result->fetch_assoc()) {
            ?>
                <tr>
                    <td><?php echo $row['subject_code']; ?></td>
                    <td><?php echo $row['subject_name']; ?></td>
                    <td><?php echo $row['total_students']; ?></td>
                    <td><?php echo $row['students_evaluated_feedback']; ?></td>
                    <td><?php echo $row['students_not_evaluated_feedback']; ?></td>
                    <td>
                        <button class=" btn btn-primary" onclick="showStudents(<?php echo $row['class_id']; ?>, <?php echo $row['subject_id']; ?>)">View Students</button>
                    </td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
<?php
    // End generating HTML content
    $html_content = ob_get_clean();
    echo $html_content;
} else {
    // No feedback statistics data found
    echo "No feedback statistics available.";
}
?>