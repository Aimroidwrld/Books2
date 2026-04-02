<?php
$host = "localhost";
$user = "2405657";    
$pass = "bds1vw";       
$name = "db2405657";    

$mysqli = new mysqli($host, $user, $pass, $name);

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}
?>
