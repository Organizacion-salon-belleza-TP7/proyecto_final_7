<?php
$servername = "localhost";
$username   = "root";
$password   = "";
$database   = "trabajo_final_7";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>
