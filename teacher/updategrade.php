<?php 

include 'connection.php';

$id = $_GET['id'];
$prelim = $_GET['p'];
$midterm = $_GET['m'];
$final = $_GET['f'];
$classid = $_GET['c'];
$year = $_GET['y'];
$sem = $_GET['s'];
$sec = $_GET['e'];
$ay = $_GET['a'];
$sub = $_GET['b'];
$code = $_GET['cd'];
$total = ($prelim + $midterm + $final) / 3;


  $sql = "UPDATE studentsubject SET prelim_grade='$prelim', midterm_grade='$midterm', final_grade='$final', total='$total' WHERE studid='$id' AND year='$year' AND subjectid = '$sub' AND semester='$sem' AND section='$sec' AND SY='$ay'";

  if ($dbconnection->query($sql) === TRUE) {
    echo "<script>window.location.href='student.php?classid=".$classid."&sem=".$sem."&sec=".$sec."&ay=".$ay."&code=".$code."&sub=".$sub."&y=".$year." '</script>";
  } else {
    echo "Error updating record: " . $dbconnection->error;
  }







?>
