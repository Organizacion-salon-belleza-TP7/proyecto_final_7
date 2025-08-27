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
    $resultado_traer_servicios = $servicio_modelo->mostrar_servicios();
    ?>

    <div>
        <a href="">Servicios Y Combos</a>
        <a href="">Productos</a>
        <a href="">Ventas Y Compras</a>
        <a href="">Proveedores</a>
        <a href="<?= BASE_URL ?>/vista/vista_adm/vista_logouts/vista_logouts_adm.php">Logeos y Movimientos</a>
        <a href="<?= BASE_URL ?>/vista/vista_adm/citas/citas.php">Citas</a>
        <a href="<?= BASE_URL ?>/controlador/controladores_adm/controlador_logout/controlador_logout.php?logout=vista_inicio_adm" class="logout">Cerrar sesión</a>
    <?php

    if($resultado_traer_servicios && $resultado_traer_servicios->num_rows > 0){
        echo "<table border = '1'>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripcion</th>
                    <th>Duracion</th>
                    <th>Tiempo de servicio</th>
                    <th>Precio</th>
                    <th>Trabajador</th>
                    <th>Activo</th>
                    <th>Tipo de servicio</th>
                    <th>Detalles del servicio</th>
                    <th>Modificar</th>
                    <th>Eliminar</th>
                </tr>
            </thead>";
        
        while($row = $resultado_traer_servicios->fetch_assoc()){
            echo "<tr>
                        <td>{$row['id_servicios']}</td>
                        <td>{$row['nombre']}</td>
                        <td>{$row['descripcion']}</td>
                        <td>{$row['duracion']}</td>
                        <td>{$row['tiempo_servicio']}</td>
                        <td>{$row['precio']}</td>
                        <td>{$row['nombre_trabajador']}</td>
                        ";
                        if($row['activo'] == 1){
                            echo"<td>Activo</td>";
                        }elseif($row['activo'] == 0){
                            echo"<td>Inactivo</td>";
                        }else{
                            echo"<td>Hubo un fallo trayecto su activo</td>";
                        }

                        echo"<td>{$row['tipo_servicio']}</td>";

                        echo"
                            <td><a href='" . BASE_URL . "/controlador/controladores_adm/servicios_combos/controlador_inicio_adm.php?id={$row['id_servicios']}&detalle_servicio=vista_inicio_adm'>Detalle del servicio</a></td>
                            <td><a href='" . BASE_URL . "/controlador/controladores_adm/servicios_combos/controlador_inicio_adm.php?id={$row['id_servicios']}&modificar=vista_inicio_adm'>Modificar</a></td>
                            <td><a href='" . BASE_URL . "/controlador/controladores_adm/servicios_combos/controlador_inicio_adm.php?id={$row['id_servicios']}&eliminar=vista_inicio_adm'>Eliminar</a></td>
                        </tr>";

        }
        echo "</tbody></table>";
        echo "<a href='" . BASE_URL . "/controlador/controladores_adm/servicios_combos/controlador_inicio_adm.php?agregar=vista_inicio_adm' class='add-btn'>Agregar Servicio</a>";
    }
    
    ?>

    <br>

    <h1>Combos</h1>
    <?php
    $resultado_traer_combos = $servicio_modelo->mostrar_combos();

    if($resultado_traer_combos && $resultado_traer_combos->num_rows > 0){
        echo "<table border = '1'>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripcion Combo</th>
                    <th>Precio</th>
                    <th>Imagen</th>
                    <th>Activo</th>
                    <th>Fecha de creacion</th>
                    <th>Detalles del Combo</th>
                    <th>Modificar</th>
                    <th>Eliminar</th>
                </tr>
            </thead>";

        while($row_combos = $resultado_traer_combos->fetch_assoc()){
            echo "<tr>
                    <td>{$row_combos['id_combos']}</td>
                    <td>{$row_combos['nombre']}</td>
                    <td>{$row_combos['descripcion_combo']}</td>
                    <td>{$row_combos['precio']}</td>
                    <td><img src='" . BASE_URL . "/imagenes/imagenes_combos/{$row_combos['imagen']}' width='150px' height='120px' alt='Imagen Combo'></td>
                    ";
                    if($row_combos['activo'] == 1){
                        echo"<td>Activo</td>";
                    }elseif($row_combos['activo'] == 0){
                        echo"<td>Inactivo</td>";
                    }else{
                        echo"<td>Hubo un fallo trayecto su activo</td>";
                    }

                    echo "<td>{$row_combos['fecha_creacion']}</td>";

            echo"
                <td><a href='" . BASE_URL . "/controlador/controladores_adm/servicios_combos/controlador_inicio_adm.php?id={$row_combos['id_combos']}&detalle_combo=vista_inicio_adm'>Detalle del servicio</a></td>
                <td><a href='" . BASE_URL . "'>Modificar</a></td>
                <td><a href='" . BASE_URL . "/controlador/controladores_adm/servicios_combos/controlador_inicio_adm.php?id={$row_combos['id_combos']}&dar_baja_combo=vista_inicio_adm'>Eliminar</a></td>
            </tr>";
            



                


        }
        echo "</tbody></table>";
        echo "<a href='" . BASE_URL . "/controlador/controladores_adm/servicios_combos/controlador_inicio_adm.php?agregar_combo=vista_inicio_adm' class='add-btn'>Agregar Combo</a>";
    }
    ?>

    

    
</body>
</html>