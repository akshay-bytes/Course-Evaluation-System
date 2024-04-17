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
    return $val; // Simply return the value as is
  }
}

// Array for evaluation status

$astat = array("Not Yet Started", "Started", "Closed");

// Query to fetch the student's class_id
$stmt = $conn->prepare("SELECT class_id FROM student_list WHERE id = ?");
$stmt->bind_param("i", $_SESSION['login_id']);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$class_id = $row['class_id'];

// Query to fetch the class name using class_id
$stmt = $conn->prepare("SELECT curriculum, level, section FROM class_list WHERE id = ?");
$stmt->bind_param("i", $class_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$class_name = $row['curriculum'] . ' ' . $row['level'] . ' - ' . $row['section'];
?>

<div class="col-12">
  <div class="card">
    <div class="card-body">
      Welcome <?php echo $_SESSION['login_name'] ?>!
      <br>
      <div class="col-md-5">
        <div class="callout callout-info">
          <h5><b>Academic Year: <?php echo $_SESSION['academic']['year'] . ' ' . (ordinal_suffix1($_SESSION['academic']['semester'])) ?> Semester</b></h5>
          <h6><b>Evaluation Status: <?php echo $astat[$_SESSION['academic']['status']] ?></b></h6>
          <h6><b>Class: <?php echo $class_name ?></b></h6> <!-- Display student's class -->
        </div>
      </div>
    </div>
  </div>
</div>