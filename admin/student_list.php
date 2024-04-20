<?php include 'db_connect.php'; ?>


<style>
    section {
        margin-bottom: 2rem;
    }

    .promotion-form {
        width: 400px;
        /* margin: 0 auto; */
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 8px;
    }

    .promotion-form h4 {
        padding: 10px 0;
        border-bottom: 2px solid #840F1E;
    }

    .promotion-form.form-group {
        margin-bottom: 20px;
    }

    .promotion-form .form-label {
        display: block;
        font-weight: bold;
        margin-bottom: 5px;
    }

    .promotion-form .form-select {
        width: 100%;
        padding: 10px;
        font-size: 16px;
        border: 1px solid #ccc;
        border-radius: 5px;
        background-color: #f8f9fa;
    }

    .promotion-form .btn-primary {
        display: inline-block;
        padding: 10px 20px;
        font-size: 16px;
        color: #fff;
        background-color: #007bff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .promotion-form .btn-primary:hover {
        background-color: #0056b3;
    }

    .page-top {
        display: flex;
        /* flex-direction: row; */
        justify-content: center;
        gap: 5rem;
    }
</style>


<section class="page-top">
    <form id="promotion_form" class="promotion-form">
        <h4>Promote Students to New Class</h4>
        <div class="form-group">
            <label for="current_class" class="form-label">Select Current Class:</label>
            <select class="form-select" id="current_class" name="current_class">
                <option value="" selected disabled>Select Current Class</option>
                <?php
                // Fetch classes from the database and populate the dropdown
                $classes = $conn->query("SELECT * FROM class_list");
                if ($classes) {
                    while ($row = $classes->fetch_assoc()) :
                ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['curriculum'] . ' ' . $row['level'] . ' - ' . $row['section']; ?></option>
                <?php
                    endwhile;
                } else {
                    echo "<option disabled>Error: " . $conn->error . "</option>"; // Error handling for classes query
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="new_class" class="form-label">Select New Class:</label>
            <select class="form-select" id="new_class" name="new_class">
                <option value="" selected disabled>Select New Class</option>
                <?php
                // Fetch classes from the database and populate the dropdown
                $classes = $conn->query("SELECT * FROM class_list");
                if ($classes) {
                    while ($row = $classes->fetch_assoc()) :
                ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['curriculum'] . ' ' . $row['level'] . ' - ' . $row['section']; ?></option>
                <?php
                    endwhile;
                } else {
                    echo "<option disabled>Error: " . $conn->error . "</option>"; // Error handling for classes query
                }
                ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Promote Students</button>
    </form>

    <form class="promotion-form" action="">

        <h4>Delete All Students from Class</h4>
        <div class="form-group">
            <label for="new_class" class="form-label">Delete Students:</label>
            <select class="form-select" id="delete_class" name="delete_class">
                <option value="" selected disabled>Select Class to Delete Students</option>
                <?php
                // Fetch classes from the database and populate the dropdown
                $classes = $conn->query("SELECT * FROM class_list");
                if ($classes) {
                    while ($row = $classes->fetch_assoc()) :
                ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['curriculum'] . ' ' . $row['level'] . ' - ' . $row['section']; ?></option>
                <?php
                    endwhile;
                } else {
                    echo "<option disabled>Error: " . $conn->error . "</option>"; // Error handling for classes query
                }
                ?>
            </select>
        </div>
        <button type="submit" class="btn btn-danger">Delete Students</button>
    </form>
</section>

<div class="col-lg-12">
    <div class="card card-outline card-success">
        <div class="card-header">
            <div class="card-tools">
                <a class="btn btn-block btn-sm btn-default btn-flat border-primary" href="./index.php?page=new_student"><i class="fa fa-plus"></i> Add New Student</a>
            </div>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label for="class">Select Class:</label>
                <select class="form-control" id="class">
                    <option value="">All</option>
                    <?php
                    $classes = $conn->query("SELECT * FROM class_list");
                    if ($classes) {
                        while ($row = $classes->fetch_assoc()) :
                    ?>
                            <option value="<?php echo $row['id']; ?>"><?php echo $row['curriculum'] . ' ' . $row['level'] . ' - ' . $row['section']; ?></option>
                    <?php
                        endwhile;
                    } else {
                        echo "Error: " . $conn->error; // Error handling for classes query
                    }
                    ?>
                </select>
            </div>
            <table class="table table-hover table-bordered" id="list">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th>School ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Current Class</th>
                        <th>Action</th>
                    </tr>class
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    $class = array();
                    $classes = $conn->query("SELECT id,concat(curriculum,' ',level,' - ',section) as `class` FROM class_list");
                    if ($classes) {
                        while ($row = $classes->fetch_assoc()) :
                            $class[$row['id']] = $row['class'];
                        endwhile;
                    } else {
                        echo "Error: " . $conn->error; // Error handling for fetching classes
                    }

                    $qry = $conn->query("SELECT s.*, CONCAT(s.firstname, ' ', s.lastname) AS name 
                    FROM student_list s 
                    LEFT JOIN class_list c ON s.class_id = c.id 
                    ORDER BY CONCAT(s.firstname, ' ', s.lastname) ASC");
                    if ($qry) {
                        while ($row = $qry->fetch_assoc()) :
                    ?>
                            <tr>
                                <th class="text-center"><?php echo $i++ ?></th>
                                <td><strong><?php echo $row['school_id'] ?></strong></td>
                                <td><strong><?php echo ucwords($row['name']) ?></strong></td>
                                <td><strong><?php echo $row['email'] ?></strong></td>
                                <td><strong><?php echo isset($class[$row['class_id']]) ? $class[$row['class_id']] : "N/A" ?></strong></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                                        Action
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item view_student" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">View</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="./index.php?page=edit_student&id=<?php echo $row['id'] ?>">Edit</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item delete_student" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">Delete</a>
                                    </div>
                                </td>
                            </tr>
                    <?php
                        endwhile;
                    } else {
                        echo "Error: " . $conn->error; // Error handling for fetching student list
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Clear local storage on page load
        localStorage.clear();

        // SCRIPT TO VIEW STUDENT WHEN OPTION IS CLICKED IN DROPDOWN
        $(document).on('click', '.view_student', function() {
            uni_modal("<i class='fa fa-id-card'></i> student Details", "<?php echo $_SESSION['login_view_folder'] ?>view_student.php?id=" + $(this).attr('data-id'))
        });

        // SCRIPT TO DELETE SPECIFIC STUDENT 
        $(document).on('click', '.delete_student', function() {
            var studentId = $(this).data('id');
            var confirmDelete = confirm("Are you sure to delete this student?");
            if (confirmDelete) {
                delete_student(studentId);
            }
        });

        // INITIALIZE THE TABLE
        var dataTable = $('#list').DataTable();

        // CHANGE FUNCTION IS USED WHENEVER A NEW CLASS IS SELECTED TO IMPLEMENT THE fetchData() FUNCTION
        $('#class').change(function() {
            localStorage.setItem($(this).attr('id'), $(this).val());
            fetchData();
        });

        // FUNCTION TO FETCH THE DATA OF STUDENTS BY THEIR CLASS FROM DATABASE
        // SCRIPT IS WRITTEN IN fetch_data.php 
        function fetchData() {
            var class_id = localStorage.getItem('class') || '';
            $('#class').val(class_id);
            $.ajax({
                url: 'fetch_data.php',
                method: 'POST',
                data: {
                    class_id: class_id
                },
                dataType: 'json',
                success: function(response) {
                    console.log(response); // Log the response for debugging
                    updateTable(response);
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }

        // Function to update table when a specific class is selected.
        function updateTable(data) {
            $('#list').DataTable().clear().draw();
            var j = 1;
            $.each(data, function(index, row) {
                var classInfo = '<strong>' + row.curriculum + ' ' + row.level + ' - ' + row.section + '</strong>';
                var newRow = [
                    '<strong>' + j++ + '</strong>',
                    '<strong>' + row.school_id + '</strong>',
                    '<strong>' + row.name + '</strong>',
                    '<strong>' + row.email + '</strong>', classInfo,
                    '<div class="dropdown">' +
                    '<button class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
                    'Action' +
                    '</button>' +
                    '<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">' +
                    '<a class="dropdown-item view_student" href="javascript:void(0)" data-id="' + row.id + '">View</a>' +
                    '<a class="dropdown-item" href="./index.php?page=edit_student&id=' + row.id + '">Edit</a>' +
                    '<a class="dropdown-item delete_student" href="javascript:void(0)" data-id="' + row.id + '">Delete</a>' +
                    '</div>' +
                    '</div>'
                ];
                $('#list').DataTable().row.add(newRow).draw();
                // fetchData();
            });
        }

        // Delete Single Students
        function delete_student(studentId) {
            start_load();
            $.ajax({
                url: 'ajax.php?action=delete_student',
                method: 'POST',
                data: {
                    id: studentId
                },
                success: function(resp) {
                    if (resp == 1) {
                        alert_toast("Data successfully deleted", 'success');
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    } else {
                        alert_toast("Failed to delete data", 'error');
                    }
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }

        fetchData();
    });


    // SCRIPT TO DELETE STUDENTS BY THEIR CLASS
    $(document).on('click', '#delete_students', function() {
        var class_id = $('#delete_class').val();
        if (class_id) {
            var confirmDelete = confirm("Are you sure you want to delete all students from this class?");
            if (confirmDelete) {
                delete_students(class_id);
            }
        } else {
            alert_toast("Please select a class to delete students.", 'warning');
        }
    });

    // SCRIPT TO DELETE STUDENTS OF WHOLE CLASS
    function delete_students(class_id) {
        $.ajax({
            url: 'delete_students.php', // PHP script to handle deletion
            method: 'POST',
            data: {
                class_id: class_id
            },
            success: function(resp) {
                if (resp == 1) {
                    alert_toast("All students from the selected class have been deleted.", 'success');
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                } else {
                    alert_toast("Failed to delete students.", 'error');
                }
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    }

    // SCRIPT TO PROMOTE STUDENTS TO NEW CLASS
    $(document).ready(function() {
        $('#promotion_form').submit(function(e) {
            e.preventDefault(); // Prevent form submission
            var currentClass = $('#current_class').val();
            var newClass = $('#new_class').val();
            if (!currentClass || !newClass) {
                alert_toast('Please select both current and new classes.', 'warning');
                return;
            }
            // Show confirmation dialog
            var confirmation = confirm('Are you sure you want to promote students from ' + currentClass + ' to ' + newClass + '?');

            if (!confirmation) {
                // If user cancels, do nothing
                return;
            }

            $.ajax({
                url: 'promotion_process.php',
                method: 'POST',
                data: {
                    current_class: currentClass,
                    new_class: newClass
                },
                success: function(response) {
                    alert_toast(response, 'success');
                    // Display success message
                    // You can also reload the page or update the UI as needed
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    alert_toast('An error occurred while processing the request.', 'error');
                }
            });
        });
    });
</script>