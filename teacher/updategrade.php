<?php 

include 'connection.php';

$id = $_GET['id'];
$midterm = $_GET['m'];
$final = $_GET['f'];
$classid = $_GET['c'];
$total = ($midterm + $final) / 2;


$sql = "UPDATE studentsubject SET midterm_grade='$midterm', final_grade='$final', total='$total' WHERE studid='$id' AND classid='$classid'";

if ($dbconnection->query($sql) === TRUE) {
	echo "<script>window.location.href='student.php?classid=".$classid."'</script>";
} else {
  echo "Error updating record: " . $dbconnection->error;
}


?>
