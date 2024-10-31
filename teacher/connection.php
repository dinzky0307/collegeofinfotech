<?php

define('DB_SERVER', '127.0.0.1');
define('DB_USERNAME', 'u510162695_infotechM');
define('DB_PASSWORD', 'infotechMCC2023');
define('DB_NAME', 'u510162695_infotechMCC');
$dbconnection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
if ($dbconnection === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

?>
