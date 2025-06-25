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
        <a href="">Logeos y Movimientos</a>
        <a href="" class="logout">Cerrar sesión</a>
    </div>

    <h1>Servicios</h1>

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

                        echo"
                            <td><a href='" . BASE_URL . "/controlador/controladores_adm/servicios_combos/controlador_inicio_adm.php?id={$row['id_servicios']}&detalle_servicio=vista_inicio_adm'>Detalle del servicio</a></td>
                            <td><a href='" . BASE_URL . "/controlador/controladores_adm/servicios_combos/controlador_inicio_adm.php?id={$row['id_servicios']}&modificar=vista_inicio_adm'>Modificar</a></td>
                            <td><a href='" . BASE_URL . "/controlador/controladores_adm/servicios_combos/controlador_inicio_adm.php?id={$row['id_servicios']}&eliminar=vista_inicio_adm'>Eliminar</a></td>
                        </tr>";

        }
        echo "</tbody></table>";
        echo "<a href='" . BASE_URL . "/controlador/controladores_adm/servicios_combos/controlador_inicio_adm.php?agregar=vista_inicio_adm' class='add-btn'>Agregar Servicio</a>";
    }else{
        echo '<script language = javascript>
            alert("No hay servicios disponibles")
            self.location = "' . BASE_URL . '/vista/vista_login/vista_login.php"
            </script>';
    }
    
    ?>

    

    
</body>
</html>