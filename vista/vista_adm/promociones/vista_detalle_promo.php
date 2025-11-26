<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');
require_once(ROOT_PATH . '/modelo/modelo_adm/promociones/modelo_promociones.php');

$id_promo = $_GET['id'];

$clase_promociones = new promociones($conn);

$funcion_traer_detalle = $clase_promociones->traer_detalle_promo($id_promo);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Detalle promo</h1>

    <?php
    if($funcion_traer_detalle['tipo'] == 'combo'){
        if($funcion_traer_detalle['resultado'] && $funcion_traer_detalle['resultado']->num_rows > 0){

            echo "<table border = '1'>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Descripcion</th>
                    <th>Precio</th>
                    <th>Activo</th>
                    <th>Fecha de creacion del combo</th>
                </tr>
            </thead>
            <tbody>";
                    while($row = $funcion_traer_detalle['resultado']->fetch_assoc()){
                        echo "<tr>
                        <td>{$row['nombre']}</td>
                        <td>{$row['descripcion_combo']}</td>
                        <td>{$row['precio']}</td>
                        ";
                        if($row['activo'] == 1){
                            echo "<td>Activo</td>";

                        }else{
                            echo "<td>Inactivo</td>";
                        }
                        echo"
                            <td>{$row['fecha_creacion']}</td>
                        ";

                    }

        }
        echo "</tbody></table>";

    }elseif($funcion_traer_detalle['tipo'] == 'servicio'){
        if($funcion_traer_detalle['resultado'] && $funcion_traer_detalle['resultado']->num_rows > 0){
             echo "<table border = '1'>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Descripcion</th>
                    <th>Duracion</th>
                    <th>Tiempo del servicios</th>
                    <th>Precio</th>
                    <th>Activo</th>
                    <th>Tipo de servicio</th>
                </tr>
            </thead>
            <tbody>";

            while($row = $funcion_traer_detalle['resultado']->fetch_assoc()){
                echo "<tr>
                        <td>{$row['nombre']}</td>
                        <td>{$row['descripcion']}</td>
                        <td>{$row['duracion']}</td>
                        <td>{$row['tiempo_servicio']}</td>
                        <td>{$row['precio_servicio']}</td>
                        ";
                        if($row['activo'] == 1){
                            echo "<td>Activo</td>";

                        }else{
                            echo "<td>Inactivo</td>";
                        }
                        echo"
                            <td>{$row['tipo_servicio']}</td>
                        ";

            }
        }
        echo "<div>
                <a href='" . BASE_URL . "/vista/vista_adm/promociones/vista_promociones.php' class='add-btn'>Volver</a>
        </div>";

    }else{
        echo "hubo un bug en la vista de detalle";
    }

    ?>
    
</body>
</html>