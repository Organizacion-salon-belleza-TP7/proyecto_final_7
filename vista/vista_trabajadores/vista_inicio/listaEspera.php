<?php
require_once(__DIR__ . '/../../../variable_global.php');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Espera</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #fce4ec, #f8bbd0);
            margin: 0;
            padding: 0;
            color: #4a148c;
        }
        .container {
            width: 90%;
            max-width: 1000px;
            margin: 50px auto;
            background: #fff;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        }
        h1 {
            text-align: center;
            color: #ad1457;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        table th, table td {
            padding: 12px;
            text-align: center;
        }
        table th {
            background: #f48fb1;
            color: white;
        }
        table tr:nth-child(even) {
            background: #fce4ec;
        }
        table tr:nth-child(odd) {
            background: #f8bbd0;
        }
        .acciones a {
            display: inline-block;
            padding: 6px 12px;
            margin: 2px;
            border-radius: 8px;
            font-size: 14px;
            text-decoration: none;
            transition: 0.3s;
        }
        .confirmar {
            background: #ec407a;
            color: white;
        }
        .confirmar:hover {
            background: #c2185b;
        }
        .cancelar {
            background: #f48fb1;
            color: white;
        }
        .cancelar:hover {
            background: #ad1457;
        }
        .volver {
            display: block;
            margin-top: 20px;
            text-align: center;
            font-weight: bold;
            text-decoration: none;
            color: #d81b60;
        }
        .volver:hover {
            color: #880e4f;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Lista de Espera</h1>
        <table border="1" cellpadding="8" cellspacing="0">
            <tr>
                <th>Trabajador</th>
                <th>DNI Trabajador</th>
                <th>Cliente</th>
                <th>DNI Cliente</th>
                <th>Tiempo Estimado</th>
                <th>Confirmación</th>
                <th>Acciones</th>
            </tr>
            <?php if (!empty($lista)): ?>
                <?php foreach ($lista as $l): ?>
                    <tr>
                        <td><?= htmlspecialchars($l['nombre_trabajador'] . " " . $l['apellido_trabajador']) ?></td>
                        <td><?= htmlspecialchars($l['dni_trabajador']) ?></td>
                        <td><?= htmlspecialchars($l['nombre_cliente'] . " " . $l['apellido_cliente']) ?></td>
                        <td><?= htmlspecialchars($l['dni_cliente']) ?></td>
                        <td><?= htmlspecialchars($l['tiempo_estimado']) ?></td>
                        <td><?= $l['confirmacion'] == 1 ? '✔ Confirmado' : '❌ Pendiente' ?></td>
                        <td class="acciones">
                            <a href="<?= BASE_URL ?>/vista/vista_trabajadores/vista_inicio/confirmar.php?id=<?= $l['id_lista_espera'] ?>" class="confirmar">Confirmar</a>
                            <a href="<?= BASE_URL ?>/vista/vista_trabajadores/vista_inicio/cancelar.php?id=<?= $l['id_lista_espera'] ?>" class="cancelar">Cancelar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">No hay registros en lista de espera</td>
                </tr>
            <?php endif; ?>
        </table>

        <a href="<?= BASE_URL ?>/vista/vista_trabajadores/vista_inicio/pantallaTrabajador.php" class="volver">⬅ Volver</a>
    </div>
</body>
</html>
