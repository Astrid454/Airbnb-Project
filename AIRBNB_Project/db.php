<?php
$servername = "localhost";  // or IP of the server
$username = "root";         // username
$password = "";             // password
$dbname = "airbnb";         // name of the database

$conn = new mysqli($servername, $username, $password, $dbname); 

if ($conn->connect_error) {
    die("Conexiunea a eșuat: " . $conn->connect_error);
}
?>

