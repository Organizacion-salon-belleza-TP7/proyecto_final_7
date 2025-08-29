<?php
$servername = "localhost";
$username = "root";
$password = ""; // o tu contraseña
$database = "trabajo_final_7";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>