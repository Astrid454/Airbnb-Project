<?php
$servername = "localhost";  // sau IP-ul serverului MySQL
$username = "root";         // userul tău MySQL
$password = "";             // parola ta MySQL
$dbname = "airbnb";         // numele bazei tale de date

// Creare conexiune
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificare conexiune
if ($conn->connect_error) {
    die("Conexiunea a eșuat: " . $conn->connect_error);
}
?>
