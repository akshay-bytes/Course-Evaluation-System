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

  <style>
    .logo {
      display: flex;
      position: absolute;
      top: 40;
      left: 40;
    }

    .login-box {
      width: 400px;
    }

    body {
      max-width: 100%;
      background-image: url(./assets/uploads/login-background.jpg);
      background-position: center;
      background-size: cover;
      background-repeat: no-repeat;
      backdrop-filter: blur(3px);
    }

    .heading h1 {
      font-family: sans-serif;
      font-size: 48px;
      text-transform: uppercase;
      text-align: center;
      align-items: center;
      font: bolder;
      -webkit-text-stroke-width: 2px;
      background-image: linear-gradient(#941727, #66000D);
      color: transparent;
      -webkit-background-clip: text;
      background-clip: text;
      transition: ease-in-out 0.5s;
    }

    .heading h1:hover {
      font-family: sans-serif;
      color: #840F1E;
      cursor: pointer;
      transition: ease-in-out 0.5s;
      letter-spacing: 0.02em;
      -webkit-text-stroke-width: 3px;
    }

    .heading h1 span {
      font-family: sans-serif;
      font-size: 1.5vw;
      color: #581845;
      cursor: pointer;
      transition: ease-in-out 0.3s;
    }

    .heading h1 span:hover {
      font-family: sans-serif;
      color: #840F1E;
      cursor: pointer;
      transition: ease-in-out 0.3s;
      letter-spacing: 0.2em;
      -webkit-text-stroke-width: 3px;
    }

    .login-page {
      background-color: transparent;
    }

    .card {
      padding: 1vw 0;
      border: 1px solid #581845;
      border-radius: 2vw;
    }
  </style>

</head>


<body>
  <div class="container login-page">

    <div class="logo">
      <img src="./assets//uploads/logo-navbar.png" width="300px" alt="">
    </div>

    <!-- Display system name and user role -->
    <div class="heading">
      <h1> <strong class="system-name"><?php echo $_SESSION['system']['name'] ?></strong> <br>
        <span>Login as:</span> <span id="user_role">Student</span>
      </h1>
    </div>
    <div class="login-box">
      <div class="login-logo">
        <!-- <a href="./assets/uploads/login-logo.png" class=""></a> -->
      </div>
      <!-- /.login-logo -->
      <div class="card">
        <div class="card-body">
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
              <select name="login" id="login" class="custom-select custom-select-md">
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

    <footer class="text-white">
      <div style="position: absolute; left:20%; bottom: 0; font-size: 18px;">
        Copyright &copy; 2024 Developed & Designed by <span> &nbsp;Akshay Billore&nbsp; </span> and Aastha Raj Singh under the guidance of Dr. Prashant Panse.
      </div>
    </footer>
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
  </div>
</body>

</html>