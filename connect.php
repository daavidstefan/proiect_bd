<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Platforma_Recenzii";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexiunea a eșuat: " . $conn->connect_error);
}
echo "Conexiunea la baza de date a fost realizată cu succes!";
?>
