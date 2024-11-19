<?php
$servername = "localhost";
$username = "root";
$password = "p1234"; 
$dbname = "child_vaccination_system"; 
$port = 3307;


$conn = new mysqli($servername, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
