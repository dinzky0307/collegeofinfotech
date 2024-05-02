<?php
$hostname = "127.0.0.1";
$username = "u510162695_infotechMCC";
$password = "infotechMCC2023";
$database = "u510162695_infotechMCC";

$connection = mysqli_connect($hostname, $username, $password, $database);

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}
?>