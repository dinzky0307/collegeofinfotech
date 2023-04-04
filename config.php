<?php
    session_start();
    $host = '127.0.0.1';
    $user = 'infotechmcc';
    $pass = 'infotechmcc';
    $db = 'infotechmcc';

    mysql_connect($host,$user,$pass) or die(mysql_error());
    mysql_select_db($db);

?>
