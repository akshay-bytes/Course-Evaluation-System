<?php
session_start();
include('./db_connect.php');

// Start output buffering
ob_start();

// Fetch system settings
$stmt = $conn->prepare("SELECT * FROM system_settings");
$stmt->execute();
$system = $stmt->get_result()->fetch_array();

// Store system settings in session
$_SESSION['system'] = $system;

// End output buffering
ob_end_flush();

// If user is already logged in, redirect to home page
if (isset($_SESSION['login_id'])) {
  header("location:index.php?page=home");
  exit;
}

// Include header file
include 'header.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
</head>

<body class="hold-transition login-page bg-black">

  <!-- Display system name and user role -->
  <h2><b><?php echo $_SESSION['system']['name'] ?> - <span id="user_role">Student</span></b></h2>

  <div class="login-box">
    <div class="login-logo">
      <a href="#" class="text-white"></a>
    </div>
    <!-- /.login-logo -->
    <div class="card">
      <div class="card-body login-card-body">
        <form action="" id="login-form">
          <div class="input-group mb-3">
            <input type="email" class="form-control" name="email" required placeholder="Email">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="password" class="form-control" name="password" required placeholder="Password">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <div class="form-group mb-3">
            <label for="">Login As</label>
            <select name="login" id="login" class="custom-select custom-select-sm">
              <option value="3">Student</option>
              <option value="2">Faculty</option>
              <option value="1">Admin</option>
            </select>
          </div>
          <div class="row">
            <div class="col-8">
              <div class="icheck-primary">
                <input type="checkbox" id="remember">
                <label for="remember">
                  Remember Me
                </label>
              </div>
            </div>
            <!-- /.col -->
            <div class="col-4">
              <button type="submit" class="btn btn-primary btn-block">Sign In</button>
            </div>
            <!-- /.col -->
          </div>
        </form>
      </div>
      <!-- /.login-card-body -->
    </div>
  </div>
  <!-- /.login-box -->

  <script>
    $(document).ready(function() {
      // Update user role text based on selected option
      $('#login').change(function() {
        var selectedOption = $(this).children('option:selected').text();
        $('#user_role').text(selectedOption);
      });

      $('#login-form').submit(function(e) {
        e.preventDefault()
        start_load()
        if ($(this).find('.alert-danger').length > 0)
          $(this).find('.alert-danger').remove();
        $.ajax({
          url: 'ajax.php?action=login',
          method: 'POST',
          data: $(this).serialize(),
          error: err => {
            console.log(err)
            end_load();

          },
          success: function(resp) {
            if (resp == 1) {
              location.href = 'index.php?page=home';
            } else {
              $('#login-form').prepend('<div class="alert alert-danger">Username or password is incorrect.</div>')
              end_load();
            }
          }
        })
      })
    })
  </script>

  <?php include 'footer.php' ?>

</body>

</html>