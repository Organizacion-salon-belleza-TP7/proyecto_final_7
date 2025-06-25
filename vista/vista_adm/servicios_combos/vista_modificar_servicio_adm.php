<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');
require_once(ROOT_PATH . '/modelo/modelo_adm/servicios_combos/modelo_inicio_adm.php');
require_once(ROOT_PATH . '/controlador/controladores_adm/servicios_combos/controlador_inicio_adm.php');

$servicio_modelo = new servicios($conn);
$id_servicio = $_GET['id'];
$datos_formulario_agregar = $servicio_modelo->formulario_agregar_servicio();
$array = $servicio_modelo->formulario_modificar($id_servicio);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div>
        <h1>Modificar servicio</h1>

        <form action="">
            <table border = '1'>
                <tr>
                    <td><label for="">id_servicio</label></td>
                    <td><input type="hidden" name = 'id_servicio' value = '<?php echo htmlspecialchars($array['id_servicio']) ?>'></td>
                </tr>
                <tr>
                    <td><label for="">Nombre</label></td>
                    <td><input type="text" name = 'nombre' value = '<?php echo htmlspecialchars($array['nombre']) ?>'></td>
                </tr>
                <tr>
                    <td><label for="">Descripcion</label></td>
                    <td><input type="text" name = 'descripcion' value = '<?php echo  htmlspecialchars($array['descripcion']) ?>'></td>
                </tr>
                <tr>
                    <td><label for="">Duracion</label></td>
                    <td><input type="number" name = 'duracion' value = '<?php echo htmlspecialchars($array['duracion']) ?>'></td>
                </tr>
                <tr>
                    <td><label for="">Tiempo de servicio</label></td>
                    <td>
                        <select name="tiempo_servicio">
                            <?php
                            if($datos_formulario_agregar && $datos_formulario_agregar->num_rows > 0){
                                while($row = $datos_formulario_agregar->fetch_assoc()){
                                    $selected = ($row['id_tiempo_servicio'] === $array['tiempo_servicio'] ? 'selected' : '');

                                    echo "<option value = '{$row['id_tiempo_servicio']}' $selected" . htmlspecialchars($row['tiempo_servicio']) . "</option>"

                                }

                                
                            }else{
                                echo "<option value = ''>No se pudo cargar el tiempo del servicio</option>"
                            }


                            ?>

                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="">Precio</label></td>
                    <td><input type="number" name = 'precio' value = '<?php echo htmlspecialchars($array['precio']) ?>'></td>
                </tr>
                <tr>
                    <td><label for="">Trabajador</label></td>
                    <td>
                        <select name="trabajador_serv">
                            <?php
                            if($datos_formulario_agregar && $datos_formulario_agregar->num_rows > 0){
                                while($row = $datos_formulario_agregar->fetch_assoc()){
                                    $select_trabajador = ($row)

                                }

                            }
                            ?>

                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="">Activo</label></td>
                    <td>
                        <select name="">
                            <?php
                            if($array['activo'] == 1):
                            ?>
                            <option value="activo">Activo</option>
                            <option value="inactivo">Inactivo</option>
                            <?php
                            elseif($array['activo'] == 0):
                            ?>
                            <option value="inactivo">Inactivo</option>
                            <option value="activo">Activo</option>
                            <?php
                            else:
                            echo "hubo un fallo";
                            die();
                            ?>
                            <?php
                            endif;
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><input type="submit"></td>
                </tr>

            </table>
        </form>
    </div>
    
</body>
</html>