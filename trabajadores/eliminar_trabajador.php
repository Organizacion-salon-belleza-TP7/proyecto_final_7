<?php
require_once("conexion.php");

$id = $_GET['id'] ?? null;

if (!$id) {
    die("ID no válido.");
}

// Eliminar trabajador
$stmt = $conn->prepare("DELETE FROM trabajadores WHERE id_trabajador = ?");
$stmt->bind_param("i", $id);
if ($stmt->execute()) {
    header("Location: index.php");
    exit;
} else {
    echo "Error al eliminar: " . $conn->error;
}
?>
