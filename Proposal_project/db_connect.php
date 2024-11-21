<?php
$servername = "localhost";
$username = "root";
$password = " "; 
$dbname = "child_vaccination_system"; 



$conn = new mysqli($servername, $username, '', $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
