<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../variable_global.php');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Proveedor</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f9f9f9; }
        form { max-width: 400px; margin: 40px auto; padding: 20px; background: #fff; border-radius: 10px; box-shadow: 0 0 10px #ccc; }
        input { width: 100%; padding: 10px; margin: 8px 0; border: 1px solid #ccc; border-radius: 5px; }
        button { background: #28a745; color: #fff; padding: 10px; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background: #218838; }
        a { display: inline-block; margin-top: 10px; text-decoration: none; color: #007bff; }
    </style>
</head>
<body>
    <form action="<?= BASE_URL ?>/controlador/controladores_adm/proveedores/controlador_agregar_proveedor.php" method="POST">
        <h2>Agregar Proveedor</h2>
        <input type="text" name="nombre_proveedor" placeholder="Nombre" required>
        <input type="text" name="apellido_proveedor" placeholder="Apellido" required>
        <input type="text" name="dni" placeholder="DNI" required>
        <button type="submit">Guardar</button>
        <a href="<?= BASE_URL ?>/vista/vista_adm/proveedores/vista_proveedores.php">Volver</a>
    </form>
</body>
</html>
