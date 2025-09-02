<?php
define('ROOT_PATH', __DIR__);
define('BASE_URL', '/proyecto_final_7');

// Conexión a la base de datos
$conn = new mysqli("localhost", "root", "", "trabajo_final_7");
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
