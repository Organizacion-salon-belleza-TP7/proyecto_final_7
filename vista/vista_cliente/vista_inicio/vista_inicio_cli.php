<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');
require_once(ROOT_PATH . '/modelo/modelo_cliente/modelo_inicio/modelo_inicio.php');

session_start();

$id_usuario = $_SESSION['user'];

$modelo_inicio = new modelo_inicio($conn);

$funcion_traer_citas = $modelo_inicio->traer_citas_compradas($id_usuario);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Citas Compradas</h1>

    <?php
    if($funcion_traer_citas && $funcion_traer_citas->num_rows > 0){
        echo "<table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Nombre de usuario</th>
                    <th>Fecha Cita</th>
                    <th>Estado</th>
                    <th>Lugar</th>
                </tr>
            </thead><tbody>";

        while($array_citas_compradas = $funcion_traer_citas->fetch_assoc()){
            echo "<tr>
                <td>{$array_citas_compradas['id_cita']}</td>
                <td>{$array_citas_compradas['nombre']}</td>
                <td>{$array_citas_compradas['nombre_usuario']}</td>
                <td>{$array_citas_compradas['fecha_cita']}</td>
                ";
                if($array_citas_compradas['activo'] == 1){
                    echo "<td>Activo</td>";

                    echo "<td>{$array_citas_compradas['nombre_lugar']}</td>
                    <td><a class='btn btn-view' href='".BASE_URL."/controlador/controladores_cliente/controladores_inicio/controlador_inicio_cli.php?id_caja={$array_citas_compradas['id_caja']}&id_cita={$array_citas_compradas['id_cita']}&reembolsar_cita=vista_inicio_cli'>Reembolsar cita</a></td>";


                }else{
                    echo "<td>Inactivo</td>";
                    echo "<td>{$array_citas_compradas['nombre_lugar']}</td>
                        <td>
                            <a class='btn btn-disabled' href='#' onclick='alert(\"⚠️ No podés reembolsar una cita que ya está inactiva.\"); return false;'>
                                Reembolsar cita
                            </a>
                        </td>";

                }

            echo "</tr>";

        }
        echo "</tbody></table>";
    }

    ?>

    <a href="<?= BASE_URL ?>/vista/vista_cliente/vista_citas/layout.php">Veni gato</a>
    <a href="<?= BASE_URL ?>/vista/vista_cliente/vista_venta_productos/vista_venta.php">Veni a comprar gato</a>
    
</body>
</html>