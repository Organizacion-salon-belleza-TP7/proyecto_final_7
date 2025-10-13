<?php
$host = "localhost";
$dbname = "trabajo_final_7";
$user = "root";   // o tu usuario de MySQL
$pass = "";       // si tu MySQL no tiene clave, déjalo vacío

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
