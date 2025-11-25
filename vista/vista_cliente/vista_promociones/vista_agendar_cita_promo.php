<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/modelo_cliente/promociones/modelo_promociones.php');
require_once(ROOT_PATH . '/modelo/BD.php');

$clase_promociones = new promociones_cliente($conn);

$funcion_traer_lugares = $clase_promociones->traer_lugares();
$funcion_traer_medios_pagos = $clase_promociones->traer_metodos_pagos();

session_start();

$ids = $_SESSION['carrito_promos'];
$id_strings = implode(",",$ids);

$funcion_traer_datos_carrito = $clase_promociones->traer_servicios_combos_promocionados_carrito($id_strings);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Resumen De Venta</h1>

    <table border="1">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Tipo</th>
                <th>Días</th>
                <th>Descuento</th>
                <th>Puntos</th>
            </tr>
        </thead>
    <tbody>

    <?php
    while($row = $funcion_traer_datos_carrito->fetch_assoc()){
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

            echo "<tr>
                <td>{$nombre}</td>
                <td>{$tipo}</td>
                <td>{$row['dias_promocion']}</td>
                <td>{$row['descuento']}%</td>
                <td>{$row['puntos']}</td>
            </tr>";

    }

    ?>
    </tbody>

    </table>

    <form action="<?= BASE_URL ?>/controlador/controladores_cliente/controlador_promociones/controlador_promociones.php" method="post">
        <table border="1">
            <input type="hidden" name="proceso" value="agendar_paso1">
            <tr>
                <td>Lugares</td>
                <td>
                    <select name="lugar">
                        <?php
                        if($funcion_traer_lugares && $funcion_traer_lugares->num_rows > 0){
                            while($row_lugares = $funcion_traer_lugares->fetch_assoc()){
                                echo "<option value='{$row_lugares['id_lugar']}'>" . htmlspecialchars($row_lugares['nombre_lugar']) . "</option>";

                            }

                        }else{
                            echo "no ahi lugares";
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Fecha y Hora</td>
                <td><input type="datetime-local" name="fecha_hora"></td>
            </tr>
            <tr>
                <td><input type="submit"></td>
            </tr>

        </table>
    </form>
    

    
</body>
</html>

