<?php
$hostname = "127.0.0.1";
$username = "infotechmcc";
$password = "infotechmcc";
$database = "infotechmcc";

$connection = mysqli_connect($hostname, $username, $password, $database);

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
