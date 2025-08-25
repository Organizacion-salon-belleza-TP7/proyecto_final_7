<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
    require_once(__DIR__ . '/../../../variable_global.php');
    require_once(ROOT_PATH . '/modelo/BD.php');
    require_once(ROOT_PATH . '/modelo/modelo_adm/servicios_combos/modelo_inicio_adm.php');
    $servicio_modelo = new servicios($conn);
    $id_servicio = $_GET['id'];
    $resultado_traer_servicios = $servicio_modelo->detalle_servicio($id_servicio);

    if($resultado_traer_servicios && $resultado_traer_servicios->num_rows > 0){
        echo "<table border = '1'>
            <thead>
                <tr>
                    <th>ID_detalle</th>
                    <th>Nombre del servicio</th>
                    <th>Nombre Del producto</th>
                    <th>Cantidad a usar</th>
                    <th>Tipo de servicio</th>
                    <th>Intereses</th>
                </tr>
            </thead>";

        while($row = $resultado_traer_servicios->fetch_assoc()){
            echo "<tr>
                    <td>{$row['id_products_usados']}</td>
                    <td>{$row['nombre']}</td>
                    <td>{$row['nombre_producto']}</td>
                    <td>{$row['cantidad_usada']}</td>
                    <td>{$row['tipo_servicio']}</td>
                    <td>{$row['intereses']}%</td>

                ";

            }
            echo "</tbody></table>";
            echo "<a href='" . BASE_URL . "/vista/vista_adm/servicios_combos/vista_inicio_adm.php' class='add-btn'>Volver</a>";

    }else{
        echo '<script language = javascript>
        alert("No se han usado nada para el servicio")
        self.location = "' . BASE_URL . '/vista/vista_adm/servicios_combos/vista_inicio_adm.php"
        </script>';
    }




    ?>
    
</body>
</html>