<?php
require_once '../controlador/ClienteControlador.php';

if(!isset($_GET['id'])) {
    die("No se especificó un cliente");
}

$controlador = new ClienteControlador();
$datos = $controlador->ver($_GET['id']);
$cliente = $datos['cliente'];
$puntos = $datos['puntos'];
$servicios = $datos['servicios'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Detalle Cliente</title>
</head>
<body>
<h1>Detalle de <?= $cliente['nombre'] ?> <?= $cliente['apellido'] ?></h1>

<p><strong>Puntos acumulados:</strong> <?= $puntos['puntos_acumulados'] ?></p>
<p><strong>Descuento:</strong> <?= $puntos['descuento'] ?>%</p>

<h2>Servicios contratados:</h2>
<?php if(count($servicios) > 0): ?>
<ul>
<?php foreach($servicios as $s): ?>
    <li><?= $s['nombre'] ?> - <?= $s['descripcion'] ?> - Precio: $<?= $s['precio'] ?></li>
<?php endforeach; ?>
</ul>
<?php else: ?>
<p>El cliente no ha contratado ningún servicio aún.</p>
<?php endif; ?>

<a href="clientes_lista.php">Volver a lista</a>
</body>
</html>