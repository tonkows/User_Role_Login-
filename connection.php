<?php

// Create connection
$db = mysqli_connect("localhost", "root", "", "php_login");
mysqli_query( $db, "SET NAMES UTF8" );


// Check connection
if (!$db) {
  die("Connection failed: " . mysqli_connect_error());
}
 echo "Connected successfully";
?>