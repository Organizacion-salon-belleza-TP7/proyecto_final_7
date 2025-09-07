<?php
require_once '../controlador/ClienteControlador.php';
$controlador = new ClienteControlador();
$id = $_GET['id'] ?? 0;
$datos = $controlador->detalle($id);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Detalle Cliente</title>
</head>
<body>
    <h1>Detalle de Cliente</h1>

    <h2>Datos Personales</h2>
    <p><b>Nombre:</b> <?= $datos['cliente']['nombre'] ?> <?= $datos['cliente']['apellido'] ?></p>
    <p><b>DNI:</b> <?= $datos['cliente']['dni'] ?></p>
    <p><b>Alergias:</b> <?= $datos['cliente']['alergias'] ?></p>
    <p><b>Fecha Nac.:</b> <?= $datos['cliente']['fecha_nacimiento'] ?></p>

    <h2>Puntos</h2>
    <p><b>Puntos acumulados:</b> <?= $datos['puntos']['puntos_acumulados'] ?? 0 ?></p>
    <p><b>Descuento:</b> <?= $datos['puntos']['descuento'] ?? 0 ?>%</p>
 cx
    <h2>Servicios Contratados</h2>
    <ul>
        <?php foreach ($datos['servicios'] as $s): ?>
            <li><?= $s['servicio'] ?> - <?= $s['descripcion'] ?> (Fecha: <?= $s['fecha_cita'] ?>)</li>
        <?php endforeach; ?>
    </ul>

    <br>
    <a href="clientes_lista.php">Volver</a>
</body>
</html>
