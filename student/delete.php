<?php
	$id=$_GET['id'];
	include('connection.php');
	mysqli_query($dbconnection,"delete from `consultations` where id='$id'");
	header('location:index.php');
?>
