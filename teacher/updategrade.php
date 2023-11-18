<?php 

include 'connection.php';

  
  $id = $_POST['gradeid'];
  $prelim = $_POST['p'];
  $midterm = $_POST['m'];
  $final = $_POST['f'];
 
  // $code = $_POST['cd'];
  $total = ($prelim + $midterm + $final) / 3;
  
  
  
    $sql = "UPDATE studentsubject SET prelim_grade='$prelim', midterm_grade='$midterm', final_grade='$final', total='$total' WHERE id= 2294";
  
    if ($dbconnection->query($sql) === TRUE) {
      // echo "<script>window.location.href='student.php?classid=".$classid."&sem=".$sem."&sec=".$sec."&ay=".$ay."&code=".$code."&y=".$year." '</script>";
    } else {
      echo "Error updating record: " . $dbconnection->error;
    }
  


?>
