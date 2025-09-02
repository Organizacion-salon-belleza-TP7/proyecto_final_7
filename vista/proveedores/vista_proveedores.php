<?php

$modelo = new ModeloProveedor();
$proveedores = $modelo->obtenerProveedores();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Proveedores</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; }
        .container { max-width: 800px; margin: 40px auto; background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px #ccc; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: center; }
        th { background: #007bff; color: #fff; }
        a { text-decoration: none; padding: 6px 12px; border-radius: 5px; }
        .add { background: #28a745; color: #fff; }
        .delete { background: #dc3545; color: #fff; }
        .add:hover { background: #218838; }
        .delete:hover { background: #c82333; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Listado de Proveedores</h2>
        <a class="add" href="vista_agregar_proveedor.php">Agregar Proveedor</a>
        <table>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>DNI</th>
                <th>Acciones</th>
            </tr>
            <?php foreach ($proveedores as $p): ?>
            <tr>
                <td><?= $p['id_proveedor'] ?></td>
                <td><?= $p['nombre_proveedor'] ?></td>
                <td><?= $p['apellido_proveedor'] ?></td>
                <td><?= $p['dni'] ?></td>
                <td>
                    <a class="delete" href="../controlador/controlador_eliminar_proveedor.php?id=<?= $p['id_proveedor'] ?>">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>
