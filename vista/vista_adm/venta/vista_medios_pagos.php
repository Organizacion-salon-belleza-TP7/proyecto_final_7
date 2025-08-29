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
    require_once(ROOT_PATH . '/modelo/modelo_adm/modelo_venta/modelo_venta.php');

    $modelo_venta = new modelo_venta($conn);

    $funcion_traer_medios_pagos = $modelo_venta->mostrar_medios_pago();


    ?>

    <h1>Medios De Pago</h1>

    <?php
    if($funcion_traer_medios_pagos && $funcion_traer_medios_pagos->num_rows > 0){
        echo "<table border = '1'>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Metodo Pago</th>
                    <th>Incremento</th>
                    <th>Decremento</th>
                    <th>Activo</th>
                    <th>Modificar</th>
                    <th>Dar de baja medio de pago</th>
                </tr>
            </thead>";

        
        while($row_medios_pagos = $funcion_traer_medios_pagos->fetch_assoc()){
            echo "<tr>
                <td>{$row_medios_pagos['id_metodo_pago']}</td>
                <td>{$row_medios_pagos['metodo_pago']}</td>";
                if($row_medios_pagos['decremento'] == null){
                    echo"<td>{$row_medios_pagos['incremento']}%</td>";
                    echo"<td>{$row_medios_pagos['decremento']}</td>";
                }elseif($row_medios_pagos['incremento'] == null){
                    echo"<td>{$row_medios_pagos['incremento']}</td>";
                    echo"<td>{$row_medios_pagos['decremento']}%</td>";
                }else{
                    echo "hubo un fallo trayendo los importes";
                }
                
            if($row_medios_pagos['activo'] == 1){
                echo"<td>Activo</td>";
            }elseif($row_medios_pagos['activo'] == 0){
                echo"<td>Inactivo</td>";
            }else{
                echo"<td>Hubo un fallo trayecto su activo</td>";
            }

            echo"
                <td><a href='" . BASE_URL . "/controlador/controladores_adm/controlador_venta/controlador_venta.php?id={$row_medios_pagos['id_metodo_pago']}&modificar_medios_pagos=vista_medios_pagos'>Modificar</a></td>
                <td><a href='" . BASE_URL . "/controlador/controladores_adm/controlador_venta/controlador_venta.php?id={$row_medios_pagos['id_metodo_pago']}&dar_baja_medio_pago=vista_medios_pagos'>Dar de baja</a></td>
            </tr>";
        }

        echo "</tbody></table>";
        echo "<a href='" . BASE_URL . "/controlador/controladores_adm/controlador_venta/controlador_venta.php?agregar_metodo_pago=vista_agregar_metodo_pago' class='add-btn'>Agregar Medio De Pago</a>";
    }
    ?>
    
</body>
</html>