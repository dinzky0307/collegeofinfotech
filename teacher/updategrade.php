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
  $sub = $_POST['subject'];
  $code = $_POST['cd'];
  $grade_id = $_POST['grade_id'];
  $total = (($midterm*0.3) + ($final*0.7)) ;
  
  
  
    $sql = "UPDATE studentsubject SET prelim_grade='$prelim', midterm_grade='$midterm', final_grade='$final', total='$total' WHERE id = $grade_id ";
  
    if ($dbconnection->query($sql) === TRUE) {
      // echo "<script>window.location.href='student.php?classid=".$classid."&sem=".$sem."&sec=".$sec."&ay=".$ay."&code=".$code."&y=".$year." '</script>";
    } else {
      echo "Error updating record: " . $dbconnection->error;
    }
  


?>
