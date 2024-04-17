<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Feedback Statistics</title>

    <!-- Add basic styling for better presentation -->
    <style>
        .pagination {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .pagination-link {
            cursor: pointer;
            padding: 8px 12px;
            margin: 0 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
            text-decoration: none;
            color: #333;
        }

        .pagination-link.active {
            font-weight: bold;
            background-color: #007bff;
            color: #fff;
            border-color: #007bff;
        }
    </style>
    <!-- CSS for printing -->
    <style media="print">
        body * {
            visibility: hidden;
        }

        #studentsModal .modal-content,
        #studentsModal .modal-content * {
            visibility: visible;
        }

        #studentsModal .modal-content {
            min-width: 100vw;
            position: absolute;
            left: -50%;
            top: 0;
        }

        .modal-header {
            font-size: 24px;
        }

        .modal-header #printButton {
            display: none;
        }

        .modal-header .close {
            display: none;
        }

        /* Hide the header and footer of the modal */
        .modal-footer {
            display: none;
        }

        /* Set the modal body to cover the whole page */
        .modal-body {
            position: relative;
            width: 100%;
            height: 100%;
            font-size: 20px;
            font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
        }
    </style>

</head>

<body>

    <?php
    // Include database connection file
    include 'db_connect.php';
    ?>

    <?php

    // Constants for pagination
    $rowsPerPage = 4;

    // Fetch feedback statistics data from the database
    $queryCount = "SELECT COUNT(*) AS total FROM (
        SELECT 
            r.class_id AS class_id,
            r.subject_id AS subject_id
        FROM 
            student_list s
        JOIN 
            restriction_list r ON s.class_id = r.class_id
        LEFT JOIN 
            evaluation_list e ON s.id = e.student_id AND r.subject_id = e.subject_id
        GROUP BY 
            r.class_id, r.subject_id
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

    // Retrieve parameters from the URL
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

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
    LIMIT $offset, $rowsPerPage";

    $result = $conn->query($query);

    // Check if there are any results
    if ($result && $result->num_rows > 0) {
    ?>
        <div class="modal-dialog modal-lg">
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
                                    <button class="btn btn-primary" onclick="showStudents(<?php echo $row['class_id']; ?>, <?php echo $row['subject_id']; ?>)">View Students</button>
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
        <div class="pagination">
            <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                <a href="#" class="pagination-link <?php echo $currentPage == $i ? 'active' : ''; ?>" data-page="<?php echo $i; ?>" data-class-id="<?php echo isset($_GET['class_id']) ? $_GET['class_id'] : ''; ?>" data-subject-id="<?php echo isset($_GET['subject_id']) ? $_GET['subject_id'] : ''; ?>"><?php echo $i; ?></a>
            <?php endfor; ?>
        </div>

    <?php
        // End generating HTML content
        $html_content = ob_get_clean();
        echo $html_content;
    } else {
        // No feedback statistics data found
        echo "No feedback statistics data available.";
    }
    ?>


    <!-- Modal to display student feedback -->
    <div class="modal fade" id="studentsModal" tabindex="-1" role="dialog" aria-labelledby="studentsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="studentsModalLabel">Students not Evaluated</h5>
                    <!-- Button to print table data -->
                    <button class="btn btn-danger" style="margin-left: 28rem;" id="printButton">Print</button>
                    <button type="button" id="studentsModalCloseButton" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="modalContent">
                    <!-- Table to display student feedback -->
                    <table class="table" id="studentsTable">
                        <thead>
                            <tr>
                                <th>School ID</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Department</th>
                                <th>Class</th>
                                <th>Section</th>
                                <th>Subject</th>
                            </tr>
                        </thead>
                        <tbody id="studentsTableBody">
                            <!-- Student data will be dynamically added here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Ensure Bootstrap CSS and JavaScript files are included -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

    <script>
        // Script to Show Students not evaluated
        function showStudents(classId, subjectId) {
            // AJAX request to fetch students data
            $.ajax({
                url: "fetch_students.php",
                type: "GET",
                data: {
                    class_id: classId,
                    subject_id: subjectId,
                    // sort_by: 'class and section'
                },
                dataType: "json",
                success: function(response) {
                    // Clear previous table content
                    $('#studentsTableBody').empty();

                    // Append new data to the table
                    $.each(response, function(index, student) {
                        $('#studentsTableBody').append(
                            '<tr>' +
                            '<td>' + student.school_id + '</td>' +
                            '<td>' + student.firstname + '</td>' +
                            '<td>' + student.lastname + '</td>' +
                            '<td>' + student.curriculum + '</td>' +
                            '<td>' + student.level + '</td>' +
                            '<td>' + student.sec + '</td>' +
                            '<td>' + student.subject + '</td>' +
                            '</tr>'
                        );
                    });

                    // Show the modal
                    $('#studentsModal').modal('show');
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    alert('An error occurred while fetching data.');
                }
            });
        }

        // Add event listener to "View Students" buttons
        $('.btn-view-students').on('click', function() {
            var classId = $(this).data('class-id');
            var subjectId = $(this).data('subject-id');
            showStudents(classId, subjectId);
        });

        $(document).ready(function() {
            // Add event listener to pagination links
            $('.pagination-link').on('click', function(e) {
                e.preventDefault(); // Prevent default link behavior

                // Remove 'active' class from all pagination links
                $('.pagination-link').removeClass('active');

                // Add 'active' class to the clicked pagination link
                $(this).addClass('active');

                var page = $(this).data('page'); // Get page number from data attribute

                // AJAX request to fetch feedback statistics data for the selected page
                $.ajax({
                    url: 'fetch_feedback_statistics.php', // Change the URL to your PHP script
                    type: 'GET',
                    data: {
                        page: page
                    },
                    dataType: 'html',
                    success: function(response) {
                        $('#list tbody').html(response); // Update table content with fetched data
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        alert('An error occurred while fetching data.');
                    }
                });
            });

            // Close Students Modal
            $('#studentsModalCloseButton').on('click', function() {
                $('#studentsModal').modal('hide');
            });

            // Script to handle printing
            $('#printButton').on('click', function() {
                window.print();
            });
        });
    </script>

</body>

</html>