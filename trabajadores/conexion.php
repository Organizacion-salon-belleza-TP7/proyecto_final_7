<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "trabajo_final_7"; // Cambia si tu base es distinta

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>
