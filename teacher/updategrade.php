<?php 

include 'connection.php';

$id = $_GET['id'];
$pre = $_GET['p'];
$midterm = $_GET['m'];
$final = $_GET['f'];
$classid = $_GET['c'];


$sql = "UPDATE studentsubject SET prelim_grade='$pre', midterm_grade='$midterm', final_grade='$final' WHERE studid='$id' AND classid='$classid'";

if ($dbconnection->query($sql) === TRUE) {
	echo "<script>window.location.href='student.php?classid=".$classid."'</script>";
} else {
  echo "Error updating record: " . $dbconnection->error;
}


?>