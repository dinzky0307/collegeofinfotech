<?php
require('../../database.php');
$id=$_REQUEST['id'];
$query = "DELETE FROM student WHERE id=$id"; 
$result = mysqli_query($con,$query) or die ( mysqli_error());
header("Location: studentlist.php"); 
?>
