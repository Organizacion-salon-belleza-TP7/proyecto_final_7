<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/modelo_cliente/promociones/modelo_promociones.php');
require_once(ROOT_PATH . '/modelo/BD.php');

session_start();

if(!isset($_SESSION['carrito_promos']) || empty($_SESSION['carrito_promos'])){
    echo "<h2>Tu carrito esta vacio</h2>";
    echo "<a href='". BASE_URL ."/vista/vista_cliente/vista_promociones/vista_promociones.php'>Volver a promociones</a>";
    exit;

}

$ids = $_SESSION['carrito_promos'];
$id_strings = implode(",",$ids);

$clase_promos = new promociones_cliente($conn);

$funcion_traer_datos_carrito = $clase_promos->traer_servicios_combos_promocionados_carrito($id_strings);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Carrito</h1>

    <table border="1">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Tipo</th>
                <th>Días</th>
                <th>Descuento</th>
                <th>Puntos</th>
                <th>Eliminar</th>
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
                <td>
                    <a href='".BASE_URL."/controlador/controladores_cliente/controlador_promociones/controlador_promociones.php?id={$row['id_promocion']}&eliminar_prom_carrito=vista_carrito_promos'>
                        Eliminar
                    </a>
                </td>
            </tr>";

    }

    ?>
    </tbody>

    </table>

    <br>
    <a href="<?= BASE_URL ?>/controlador/controladores_cliente/controlador_promociones/controlador_promociones.php?carrito=vista_carrito_promos">Terminar Compra</a>
    <a href="<?= BASE_URL ?>/vista/vista_cliente/vista_promociones/vista_promociones.php">Seguir comprando</a>
    
</body>
</html>