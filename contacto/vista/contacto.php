<?php
require_once __DIR__ . '/../controladores/ControladorContacto.php';
require_once __DIR__ . '/../conexion.php';

$conn = Conexion::conectar();

// Cargar listas si después querés usarlas
$proveedores = $conn->query("SELECT id_proveedor, nombre_proveedor FROM proveedores");
$trabajadores = $conn->query("SELECT id_trabajador, nombre_trabajador FROM trabajadores");

// Guardar contacto
ControladorContacto::guardar();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Agregar Contacto</title>

<style>
    body {
        background: #1d1a29;
        font-family: Arial, sans-serif;
        color: #fff;
        margin: 0;
        padding: 20px;
    }

    .container {
        background: rgba(255, 255, 255, 0.1);
        padding: 25px;
        max-width: 500px;
        margin: auto;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(255, 0, 120, 0.3);
        backdrop-filter: blur(10px);
    }

    h2 {
        text-align: center;
        color: #ff4fa3;
        font-size: 28px;
        margin-bottom: 20px;
    }

    label {
        color: #ff8cc9;
        font-weight: bold;
        display: block;
        margin-bottom: 5px;
    }

    select, input {
        width: 100%;
        padding: 10px;
        border: 1px solid #ff4fa3;
        border-radius: 8px;
        margin-bottom: 15px;
        background: #fff;
        font-size: 16px;
    }

    button {
        width: 100%;
        background: #ff4fa3;
        color: white;
        padding: 12px;
        border: none;
        border-radius: 10px;
        font-size: 18px;
        cursor: pointer;
        transition: 0.3s;
    }

    button:hover {
        background: #ff69b8;
    }
</style>
</head>

<body>

<div class="container">
    <h2>Agregar Contacto</h2>

    <form method="POST">

        <!-- SOLO ESTE SELECT — COMO PEDISTE -->
        <label>Tipo de contacto:</label>
        <select name="tipo_contacto" required>
            <option value="">Seleccione</option>
            <option value="proveedor">Proveedor</option>
            <option value="trabajador">Trabajador</option>
        </select>

        <label>Código de área:</label>
        <input type="text" name="codigo_area" required>

        <label>Número telefónico:</label>
        <input type="text" name="numero_telefonico" required>

        <label>Correo electrónico:</label>
        <input type="email" name="correo_electronico">

        <button type="submit">Guardar</button>
    </form>
</div>

</body>
</html>
d