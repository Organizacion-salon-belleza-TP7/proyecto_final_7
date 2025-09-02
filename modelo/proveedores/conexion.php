<?php
$host = "localhost";         // o la IP del servidor de base de datos
$usuario = "root";           // cambia si tienes otro usuario
$contrasena = "";            // cambia si tu usuario tiene contraseña
$base_de_datos = "trabajo_final_7";

// Crear la conexión
$conn = new mysqli($host, $usuario, $contrasena, $base_de_datos);

// Verificar si hay errores
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
} else {
    echo "Conexión exitosa a la base de datos";
}
?>