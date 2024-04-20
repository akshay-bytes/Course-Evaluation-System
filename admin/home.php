<?php include('db_connect.php');

function ordinal_suffix1($val)
{
  if (is_numeric($val)) {
    $num = $val % 100; // protect against large numbers
    if ($num < 11 || $num > 13) {
      switch ($num % 10) {
        case 1:
          return $val . 'st';
        case 2:
          return $val . 'nd';
        case 3:
          return $val . 'rd';
      }
    }
    return $val . 'th';
  } else {
    // Handle non-numeric values here
    // For example, return the value as is or provide a default suffix
    return $val; // Just return the value as is for now
  }
}

$astat = array("Not Yet Started", "On-going", "Closed");
?>

<div class="col-12">
  <div class="card">
    <div class="card-body">
      Welcome <?php echo $_SESSION['login_name'] ?>!
      <br>
      <div class="col-md-10" style="display: flex;">
        <div class="callout callout-info" style="margin-right: 4rem;">
          <h5><b>Academic Year: <?php echo $_SESSION['academic']['year'] . ' ' . (ordinal_suffix1($_SESSION['academic']['semester'])) ?> Semester</b></h5>
          <h6><b>Evaluation Status: <?php echo $astat[$_SESSION['academic']['status']] ?></b></h6>
        </div>

        <div class="callout callout-info" style="display: flex; flex-direction: column;">
          <!-- Add Students Form -->
          <h4>Add Student Data</h4>
          <p>Download Sample File: <a href="./uploads/sample_student_data.csv" download>CSV</a></p>
          <form action="student_form.php" method="post" id="imp_student" enctype="multipart/form-data">
            <input type="file" name="file" accept=".csv">
            <button type="submit" name="submit">Upload</button>
          </form>

          <!-- Add Teachers Form -->
          <h4>Add Teachers Data</h4>
          <p>Download Sample File: <a href="./uploads/sample_teacher_data.csv" download>CSV</a></p>
          <form action="faculty_form.php" method="post" id="imp_faculty" enctype="multipart/form-data">
            <input type="file" name="file" accept=".csv">
            <button type="submit" name="submit">Upload</button>
          </form>

          <!-- Add Courses Form -->
          <h4>Add Course Data</h4>
          <p>Download Sample File: <a href="./uploads/sample_course_data.csv" download>CSV</a></p>
          <form action="teacher_form.php" method="post" id="imp_course" enctype="multipart/form-data">
            <input type="file" name="file" accept=".csv">
            <button type="submit" name="submit">Upload</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<!--  -->

<div class="row">
  <!-- Faculty Details -->
  <div class="col-12 col-sm-6 col-md-4">
    <a href="index.php?page=faculty_list" style="text-decoration: none; color: inherit;">
      <div class="small-box bg-light shadow-sm border">
        <div class="inner">
          <h3><?php echo $conn->query("SELECT * FROM faculty_list ")->num_rows; ?></h3>
          <p>Total Faculties</p>
        </div>
        <div class="icon">
          <i class="fa fa-user-friends"></i>
        </div>
      </div>
    </a>
  </div>
  <div class="col-12 col-sm-6 col-md-4">
    <a href="index.php?page=student_list" style="text-decoration: none; color: inherit;">
      <div class="small-box bg-light shadow-sm border">
        <div class="inner">
          <h3><?php echo $conn->query("SELECT * FROM student_list")->num_rows; ?></h3>

          <p>Total Students</p>
        </div>
        <div class="icon">
          <i class="fa ion-ios-people-outline"></i>
        </div>
      </div>
    </a>
  </div>
  <div class="col-12 col-sm-6 col-md-4">
    <div class="small-box bg-light shadow-sm border">
      <div class="inner">
        <h3><?php echo $conn->query("SELECT * FROM users")->num_rows; ?></h3>

        <p>Total Users</p>
      </div>
      <div class="icon">
        <i class="fa fa-users"></i>
      </div>
    </div>
  </div>
  <div class="col-12 col-sm-6 col-md-4">
    <div class="small-box bg-light shadow-sm border">
      <div class="inner">
        <h3><?php echo $conn->query("SELECT * FROM class_list")->num_rows; ?></h3>

        <p>Total Classes</p>
      </div>
      <div class="icon">
        <i class="fa fa-list-alt"></i>
      </div>
    </div>
  </div>
</div>

<script>
  // Student Update Script
  $('#imp_student').submit(function(e) {
    e.preventDefault(); // Prevent default form submission

    // Show loading indicator
    start_load();

    // Perform AJAX request
    $.ajax({
      url: 'student_form.php', // URL to your PHP script for importing CSV data
      data: new FormData($(this)[0]), // Serialize form data
      cache: false,
      contentType: false,
      processData: false,
      method: 'POST',
      type: 'POST',
      success: function(resp) {
        if (resp == 1) {
          alert_toast("Student data is imported to database successfully", 'success'); // Show success toast alert
          setTimeout(function() {
            location.reload(); // Reload the page after a delay
          }, 1500);
        } else {
          alert_toast("Error: " + resp, 'error');
          console.log(resp) // Show error toast alert
        }
      },
      error: function(xhr, status, error) {
        alert_toast("Error: " + error, 'error'); // Show error toast alert
      },
      complete: function() {
        end_load(); // Hide loading indicator
      }
    });
  });

  // Faculty Update Script
  $('#imp_faculty').submit(function(e) {
    e.preventDefault(); // Prevent default form submission

    // Show loading indicator
    start_load();

    // Perform AJAX request
    $.ajax({
      url: 'teacher_form.php', // URL to your PHP script for importing CSV data
      data: new FormData($(this)[0]), // Serialize form data
      cache: false,
      contentType: false,
      processData: false,
      method: 'POST',
      type: 'POST',
      success: function(resp) {
        if (resp == 1) {
          alert_toast("Teacher data is imported to database successfully", 'success'); // Show success toast alert
          setTimeout(function() {
            location.reload(); // Reload the page after a delay
          }, 1500);
        } else {
          alert_toast("Error: " + resp, 'error');
          console.log(resp) // Show error toast alert
        }
      },
      error: function(xhr, status, error) {
        alert_toast("Error: " + error, 'error'); // Show error toast alert
      },
      complete: function() {
        end_load(); // Hide loading indicator
      }
    });
  });

  // Course Update Script
  $('#imp_course').submit(function(e) {
    e.preventDefault(); // Prevent default form submission

    // Show loading indicator
    start_load();

    // Perform AJAX request
    $.ajax({
      url: 'course_form.php', // URL to your PHP script for importing CSV data
      data: new FormData($(this)[0]), // Serialize form data
      cache: false,
      contentType: false,
      processData: false,
      method: 'POST',
      type: 'POST',
      success: function(resp) {
        if (resp == 1) {
          alert_toast("Courses are imported to the database successfully", 'success'); // Show success toast alert
          setTimeout(function() {
            location.reload(); // Reload the page after a delay
          }, 1500);
        } else {
          alert_toast("Error: " + resp, 'error');
          console.log(resp) // Show error toast alert
        }
      },
      error: function(xhr, status, error) {
        alert_toast("Error: " + error, 'error'); // Show error toast alert
      },
      complete: function() {
        end_load(); // Hide loading indicator
      }
    });
  });
</script>