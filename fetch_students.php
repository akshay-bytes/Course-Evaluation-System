<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

    <?php
    // Include database connection file
    include 'db_connect.php';

    // Check if class_id and subject_id are set and numeric
    if (isset($_GET['class_id'], $_GET['subject_id']) && is_numeric($_GET['class_id']) && is_numeric($_GET['subject_id'])) {
        // Sanitize inputs to prevent SQL injection
        $class_id = mysqli_real_escape_string($conn, $_GET['class_id']);
        $subject_id = mysqli_real_escape_string($conn, $_GET['subject_id']);

        // Constants for pagination
        $rowsPerPage = 10;

        // Determine current page
        $currentPage = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;

        // Calculate offset for pagination
        $offset = ($currentPage - 1) * $rowsPerPage;

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
        AND e.student_id IS NULL
    LIMIT ?, ?");

        // Bind parameters
        $stmt->bind_param("iiiii", $subject_id, $subject_id, $class_id, $offset, $rowsPerPage);

        // Execute query
        $stmt->execute();

        // Get result
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Start building the HTML for the students table
            $table_html = '<div class="modal-dialog modal-lg">
    <div class="modal-content">
    <div class="modal-header">
    <h5 class="modal-title">List of Students Not Evaluated</h5>
    <button type="button" class="btn btn-primary ml-lg-4" id="printButton">Print</button>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
    </button>
    </div>
    <div class="modal-body">
    <table class="table" id="eval-table">
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
    <tbody>';

            // Fetching and appending each row of student data to the HTML table
            while ($row = $result->fetch_assoc()) {
                $table_html .= '<tr>
        <td>' . $row['school_id'] . '</td>
        <td>' . $row['firstname'] . '</td>
        <td>' . $row['lastname'] . '</td>
        <td>' . $row['curriculum'] . '</td>
        <td>' . $row['level'] . '</td>
        <td>' . $row['sec'] . '</td>
        <td>' . $row['subject'] . '</td>
        </tr>';
            }

            // Close the HTML table and modal
            $table_html .= '</tbody>
    </table>
    </div>
    </div>
    </div>';

            echo $table_html; // Output the HTML for the students table
        } else {
            echo "No students found for the selected class and subject.";
        }

        // Close statement and database connection
        $stmt->close();
        $conn->close();
    } else {
        echo "Invalid input parameters.";
    }
    ?>


    <script>
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

                    // AJAX request to fetch new data based on the selected page
                    var xhr = new XMLHttpRequest();
                    xhr.onreadystatechange = function() {
                        if (this.readyState == 4 && this.status == 200) {
                            // Update the content of the modal or table with the new data
                            document.getElementById('studentsTable').innerHTML = this.responseText;
                        }
                    };
                    xhr.open("GET", "fetch_students.php?class_id=" + classId + "&subject_id=" + subjectId + "&page=" + page, true);
                    xhr.send();
                });
            });
        });
        // Function to print modal contents
        function printModalContents() {
            var tableContents = $('#eval-table').html();
            var printWindow = window.open('', '', 'height=400,width=600');
            printWindow.document.write('<html><head><title>Print</title></head><body>');
            printWindow.document.write('<table>');
            printWindow.document.write(tableContents);
            printWindow.document.write('</table>');
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
        }

        // Attach event handler for print button
        $('#printButton').on('click', function() {
            console.log("Print button clicked"); // Check if event listener is triggered
            printModalContents(); // Call the function to print modal contents
        });
    </script>

</body>

</html>