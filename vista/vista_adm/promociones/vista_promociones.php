<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');
require_once(ROOT_PATH . '/modelo/modelo_adm/promociones/modelo_promociones.php');

$clase_promociones = new promociones($conn);
$resultado_traer_promociones = $clase_promociones->traer_servicios_combos_promocionados();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Promociones</title>
</head>
<body>
    <h1>Promociones</h1>

    <?php
    if ($resultado_traer_promociones && $resultado_traer_promociones->num_rows > 0) {

        echo "<table border = '1'>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Tipo</th>
                    <th>Días de promoción</th>
                    <th>Descuento</th>
                    <th>Puntos</th>
                    <th>Activo</th>
                    <th colspan='3'>Acciones</th>
                </tr>
            </thead>
            <tbody>";

        
        while ($row = $resultado_traer_promociones->fetch_assoc()) {

            //se define que tipo es dentro del while
            if (!empty($row['nombre_combo'])) {
                $nombre = $row['nombre_combo'];
                $tipo = "Combo";
            } elseif (!empty($row['nombre_servicio'])) {
                $nombre = $row['nombre_servicio'];
                $tipo = "Servicio";
            } else {
                $nombre = "—";
                $tipo = "Desconocido";
            }

            //y ya solo se recorre :p
            echo "<tr>
                <td>{$nombre}</td>
                <td>{$tipo}</td>
                <td>{$row['dias_promocion']}</td>
                <td>{$row['descuento']}%</td>
                <td>{$row['puntos']}</td>
                ";
                if($row['activo'] == 1){
                    echo "<td>Activo</td>";

                }else{
                    echo "<td>Inactivo</td>";
                }
                echo"
                <td><a class='btn btn-view' href='".BASE_URL."/controlador/controladores_adm/controlador_promociones/controlador_promociones.php?id={$row['id_promocion']}&detalle_promo=vista_promociones'>Detalle</a></td>
                <td><a class='btn btn-edit' href='".BASE_URL."/controlador/controladores_adm/controlador_promociones/controlador_promociones.php?id={$row['id_promocion']}&modificar=vista_promociones'>Modificar</a></td>
                <td><a class='btn btn-delete' href='".BASE_URL."/controlador/controladores_adm/controlador_promociones/controlador_promociones.php?id={$row['id_promocion']}&cambiar_estado=vista_promociones'>Dar baja Promo</a></td>
            </tr>";
        }

        echo "</tbody></table>";

        echo "<a href='".BASE_URL."/controlador/controladores_adm/controlador_promociones/controlador_promociones.php?agregar=vista_promociones' class='add-btn'>+ Agregar Promoción</a>";

    } else {
        echo "<p>No hay promociones registradas.</p>";
    }
    ?>
</body>
</html>
