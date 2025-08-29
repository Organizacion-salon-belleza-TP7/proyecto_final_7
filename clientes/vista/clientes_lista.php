<?php
require_once '../controlador/ClienteControlador.php';
$controlador = new ClienteControlador();
$clientes = $controlador->listar();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Lista de Clientes</title>
</head>
<body>
<h1>Clientes</h1>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Apellido</th>
        <th>Acciones</th>
    </tr>
    <?php foreach($clientes as $c): ?>
    <tr>
        <td><?= $c['id_cliente'] ?></td>
        <td><?= $c['nombre'] ?></td>
        <td><?= $c['apellido'] ?></td>
        <td><a href="cliente_detalle.php?id=<?= $c['id_cliente'] ?>">Ver detalle</a></td>
    </tr>
    <?php endforeach; ?>
</table>
</body>
</html>
