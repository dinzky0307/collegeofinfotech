<?php

define('DB_SERVER', '127.0.0.1');
define('DB_USERNAME', 'infotechmcc');
define('DB_PASSWORD', 'infotechmcc');
define('DB_NAME', 'infotech');
$dbconnection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
if($dbconnection === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

?>
