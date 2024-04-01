<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>feedback_statistics</title>
    <!-- Add basic styling for better presentation -->
    <style>
        .pagination-link {
            cursor: pointer;
        }

        .pagination-link.active {
            font-weight: bold;
        }
    </style>
</head>

<body>

    <?php
    // Include database connection file
    include 'db_connect.php';

    // Constants for pagination
    $rowsPerPage = 1;

    // Fetch feedback statistics data from the database
    $queryCount = "SELECT COUNT(*) AS total FROM (
    SELECT 
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
    GROUP BY 
        r.class_id, r.subject_id, sl.code, sl.subject
) AS totalRows";
    $resultCount = $conn->query($queryCount);
    $totalRows = $resultCount->fetch_assoc()['total'];
    $totalPages = ceil($totalRows / $rowsPerPage);

    // Determine current page
    if (!isset($_GET['page']) || !is_numeric($_GET['page']) || $_GET['page'] < 1 || $_GET['page'] > $totalPages) {
        $currentPage = 1;
    } else {
        $currentPage = $_GET['page'];
    }

    // Calculate offset for pagination
    $offset = ($currentPage - 1) * $rowsPerPage;

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
GROUP BY 
    r.class_id, r.subject_id, sl.code, sl.subject
-- LIMIT $offset, $rowsPerPage";

    $result = $conn->query($query);

    // Check if there are any results
    if ($result && $result->num_rows > 0) {
    ?>
        <div class="modal-dialog modal-lg">
            <!-- <div class="modal-dialog modal-lg"> -->
            <div class="modal-content">
                <!-- Table to display feedback statistics -->
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
            </div>
        </div>

        <!-- Pagination links -->
        <div>
            <ul class="pagination" style="gap: 1.5vw;  padding: 0.5vw">
                <?php if ($totalPages > 1) : ?>
                    <?php if ($currentPage > 1) : ?>
                        <li>
                            <a href="?page=<?php echo $currentPage - 1; ?>" class="pagination-link" data-page="<?php echo $currentPage - 1; ?>">Previous</a>
                        </li>
                    <?php endif; ?>
                    <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                        <li>
                            <a href="?page=<?php echo $i; ?>" class="pagination-link <?php if ($i == $currentPage) echo 'active'; ?>" data-page="<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                    <?php if ($currentPage < $totalPages) : ?>
                        <li>
                            <a href="?page=<?php echo $currentPage + 1; ?>" class="pagination-link" data-page="<?php echo $currentPage + 1; ?>">Next</a>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
            </ul>
        </div>

    <?php
    } else {
        // No feedback statistics data found
        echo "No feedback statistics available.";
    }
    ?>

    <!-- Modal to display student feedback -->
    <div class="modal fade" id="studentsModal" tabindex="-1" role="dialog" aria-labelledby="studentsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="studentsModalLabel">Students not Evaluated</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="studentsTable"></div>
                </div>
            </div>
        </div>
    </div>


    <script>
        function showStudents(classId, subjectId) {
            // AJAX request to fetch students data
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    // Display students table in a modal
                    $('#studentsModal').html(this.responseText);
                    $('#studentsModal').modal('show');
                }
            };
            xhttp.open("GET", "fetch_students.php?class_id=" + classId + "&subject_id=" + subjectId, true);
            xhttp.send();
        }
        document.addEventListener("DOMContentLoaded", function() {
            // Function to handle pagination link clicks
            document.querySelectorAll('.pagination-link').forEach(function(element) {
                element.addEventListener('click', function(e) {
                    e.preventDefault(); // Prevent default anchor behavior

                    // Get the page number from the data attribute
                    var page = parseInt(this.getAttribute('data-page'));

                    // Get the class_id and subject_id from the URL
                    var urlParams = new URLSearchParams(window.location.search);
                    var classId = urlParams.get('class_id');
                    var subjectId = urlParams.get('subject_id');

                    // Call the function to fetch and display feedback statistics for the selected page
                    fetchFeedbackStatistics(classId, subjectId, page);
                });
            });

            // Function to fetch and display feedback statistics for the selected page
            function fetchFeedbackStatistics(classId, subjectId, page) {
                // AJAX request to fetch feedback statistics data
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        // Replace the content of the modal with the fetched data
                        document.getElementById('list').innerHTML = this.responseText;
                    }
                };
                // Construct the URL for fetching data
                var url = 'fetch_feedback_statistics.php?class_id=' + classId + '&subject_id=' + subjectId + '&page=' + page;
                xhttp.open("GET", url, true);
                xhttp.send();
            }
        });
    </script>

</body>

</html>