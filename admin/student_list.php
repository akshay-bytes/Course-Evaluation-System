<?php include 'db_connect.php'; ?>

<div class="col-lg-12">
    <div class="card card-outline card-success">
        <div class="card-header">
            <div class="card-tools">
                <a class="btn btn-block btn-sm btn-default btn-flat border-primary" href="./index.php?page=new_student"><i class="fa fa-plus"></i> Add New Student</a>
            </div>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label for="academic_year">Select Academic Year:</label>
                <select class="form-control" id="academic_year">
                    <option value="">All</option>
                    <?php
                    $academic_years = $conn->query("SELECT * FROM academic_list");
                    if ($academic_years) {
                        while ($row = $academic_years->fetch_assoc()) :
                    ?>
                            <option value="<?php echo $row['id']; ?>"><?php echo $row['year'] . ' - ' . $row['semester']; ?></option>
                    <?php
                        endwhile;
                    } else {
                        echo "Error: " . $conn->error; // Error handling for academic years query
                    }
                    ?>
                </select>
            </div>
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
                        <th>Academic Year</th>
                        <th>Action</th>
                    </tr>
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

                    $qry = $conn->query("SELECT s.*, CONCAT(s.firstname, ' ', s.lastname) AS name, a.year, a.semester FROM student_list s LEFT JOIN class_list c ON s.class_id = c.id LEFT JOIN academic_list a ON s.academic_id = a.id ORDER BY CONCAT(s.firstname, ' ', s.lastname) ASC");
                    if ($qry) {
                        while ($row = $qry->fetch_assoc()) :
                    ?>
                            <tr>
                                <th class="text-center"><?php echo $i++ ?></th>
                                <td><strong><?php echo $row['school_id'] ?></strong></td>
                                <td><strong><?php echo ucwords($row['name']) ?></strong></td>
                                <td><strong><?php echo $row['email'] ?></strong></td>
                                <td><strong><?php echo isset($class[$row['class_id']]) ? $class[$row['class_id']] : "N/A" ?></strong></td>
                                <td><strong><?php echo $row['year'] ? $row['year'] . ' - ' . $row['semester'] : "N/A"; ?></strong></td>
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

        $(document).on('click', '.view_student', function() {
            uni_modal("<i class='fa fa-id-card'></i> student Details", "<?php echo $_SESSION['login_view_folder'] ?>view_student.php?id=" + $(this).attr('data-id'))
        });

        $(document).on('click', '.delete_student', function() {
            var studentId = $(this).data('id');
            var confirmDelete = confirm("Are you sure to delete this student?");
            if (confirmDelete) {
                delete_student(studentId);
            }
        });

        var dataTable = $('#list').DataTable();

        $('#academic_year, #class').change(function() {
            localStorage.setItem($(this).attr('id'), $(this).val());
            fetchData();
        });

        function fetchData() {
            var academic_year = localStorage.getItem('academic_year') || '';
            var class_id = localStorage.getItem('class') || '';
            $('#academic_year').val(academic_year);
            $('#class').val(class_id);
            $.ajax({
                url: 'fetch_data.php',
                method: 'POST',
                data: {
                    academic_year: academic_year,
                    class_id: class_id
                },
                dataType: 'json',
                success: function(response) {
                    updateTable(response);
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }

        function updateTable(data) {
            $('#list').DataTable().clear().draw();
            var j = 1;
            $.each(data, function(index, row) {
                var classInfo = '<strong>' + row.curriculum + ' ' + row.level + ' - ' + row.section + '</strong>';
                var newRow = [
                    '<strong>' + j++ + '</strong>',
                    '<strong>' + row.school_id + '</strong>',
                    '<strong>' + row.name + '</strong>',
                    '<strong>' + row.email + '</strong>',
                    classInfo,
                    '<strong>' + row.year + '</strong>',
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
            });
        }

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
</script>