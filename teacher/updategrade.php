<?php 

include 'connection.php';

  
  $id = $_POST['id'];
  $prelim = $_POST['p'];
  $midterm = $_POST['m'];
  $final = $_POST['f'];
  $classid = $_POST['c'];
  $year = $_POST['y'];
  $sem = $_POST['s'];
  $sec = $_POST['e'];
  $ay = $_POST['a'];
 
  // $code = $_POST['cd'];
  $total = ($prelim + $midterm + $final) / 3;
 $sub = $_POST['subject'];
  
  
  
    $sql = "UPDATE studentsubject SET prelim_grade = 1 WHERE studid='$id' AND subjectid='$sub' AND year='$year' AND semester='$sem' AND section='$sec' AND SY='$ay'";
  
    if ($dbconnection->query($sql) === TRUE) {
      // echo "<script>window.location.href='student.php?classid=".$classid."&sem=".$sem."&sec=".$sec."&ay=".$ay."&code=".$code."&y=".$year." '</script>";
    } else {
      echo "Error updating record: " . $dbconnection->error;
    }
  


?>
