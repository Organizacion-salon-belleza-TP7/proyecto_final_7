<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php'); // Incluir tu conexión original
require_once(ROOT_PATH . '/controlador/controladores_adm/clientes/ClienteControlador.php');

// Usar la conexión global $conn de tu archivo original
$controlador = new ClienteControlador($conn);
$clientes = $controlador->listar();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Clientes</title>
</head>
<body>
    <h1>Listado de Clientes</h1>
    <table border="1">
        <tr>
            <th>ID</th><th>Nombre</th><th>Apellido</th><th>DNI</th><th>Acciones</th>
        </tr>
        <?php foreach ($clientes as $c): ?>
        <tr>
            <td><?= $c['id_cliente'] ?></td>
            <td><?= $c['nombre'] ?></td>
            <td><?= $c['apellido'] ?></td>
            <td><?= $c['dni'] ?></td>
            <td>
                <a href="cliente_detalle.php?id=<?= $c['id_cliente'] ?>">Ver detalle</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>