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

$astat = array("Not Yet Started", "On-going", "Closed");
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
        </div>
      </div>
    </div>
  </div>
</div>