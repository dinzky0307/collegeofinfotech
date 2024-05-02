<?php
session_start();
$host = 'localhost';
$user = 'u510162695_infotechMCC';
$pass = 'infotechMCC2023';
$db = 'u510162695_infotechMCC';

mysql_connect($host, $user, $pass) or die(mysql_error());
mysql_select_db($db);

?>