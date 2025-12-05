<?php
require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');
require_once(ROOT_PATH . '/controlador/controlador_trabajadores/controlador_inicio/TrabajadorController.php');

$controller = new TrabajadorController($conn);
$lista = $controller->listaEspera();
?>
<style>
    body {
        font-family: Arial, sans-serif;
        background: #ffe6f2;
        margin: 0;
        padding: 20px;
    }

    h2 {
        text-align: center;
        color: #d63384;
        margin-bottom: 20px;
        font-size: 28px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    thead {
        background: #ff99cc;
        color: white;
    }

    thead th {
        padding: 12px;
        font-size: 16px;
    }

    tbody td {
        padding: 12px;
        text-align: center;
        border-bottom: 1px solid #ffe0f0;
        font-size: 15px;
    }

    tr:nth-child(even) {
        background: #fff5fa;
    }

    tr:hover {
        background: #ffe0ef;
        transition: 0.2s;
    }

    .btn {
        padding: 6px 12px;
        border-radius: 5px;
        text-decoration: none;
        font-size: 14px;
        font-weight: bold;
        color: white;
    }

    .btn-confirmar {
        background: #ff66b3;
    }

    .btn-confirmar:hover {
        background: #ff3385;
    }

    .btn-cancelar {
        background: #ff4d88;
    }

    .btn-cancelar:hover {
        background: #cc0052;
    }


    .btn-volver {
        display: inline-block;
        margin-bottom: 15px;
        background: #d63384;
        color: white;
        padding: 8px 16px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: bold;
        font-size: 14px;
    }

    .btn-volver:hover {
        background: #b0246a;
    }
</style>


<h2> Lista de Espera</h2>

<table border="1" width="100%">
    <thead>
        <tr>
             <th>ID</th>
            <th>Cliente</th>
            <th>Trabajador</th>
            <th>Tiempo Estimado</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
    </thead>

    <tbody>
        <?php if (!empty($lista)): ?>
            <?php foreach ($lista as $fila): ?>
                <tr>
                    <td><?= $fila['id_lista_espera'] ?></td>


                    <td><?= $fila['nombre_cliente'] . " " . $fila['apellido_cliente'] ?></td>

                    <td>
                        <?= $fila['nombre_trabajador'] 
                            ? $fila['nombre_trabajador'] . " " . $fila['apellido_trabajador']
                            : "Sin trabajador asignado" ?>
                    </td>

                    <td><?= $fila['tiempo_estimado'] ?></td>

                    <td><?= $fila['confirmacion'] == 1 ? "Confirmado" : "Pendiente" ?></td>

                   <td>
    <?php if ($fila['confirmacion'] == 0): ?>
        <a class="btn btn-confirmar" href="confirmar.php?id=<?= $fila['id_lista_espera'] ?>">Confirmar</a>
    <?php endif; ?>

    <a class="btn btn-cancelar" href="cancelar.php?id=<?= $fila['id_lista_espera'] ?>">Cancelar</a>
</td>
                </tr>
                

                
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="6">No hay clientes en la lista de espera.</td></tr>
        <?php endif; ?>
    </tbody>
</table>
<a href="pantallaTrabajador.php" class="btn btn-volver">← Volver</a>

</div>
