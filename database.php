<?php
$servername = "127.0.0.1";
$username = "infotechmcc";
$password = "infotechmcc";
$dbname = "infotechmcc";

$connection = null;
try {
  $connection = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  // set the PDO error mode to exception
  $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch(PDOException $e) {
  echo $sql . "<br>" . $e->getMessage();
}
?>