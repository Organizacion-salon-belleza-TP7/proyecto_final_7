<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    require_once(__DIR__ . '/../../../variable_global.php');
    require_once(ROOT_PATH . '/modelo/modelo_adm/servicios_combos/modelo_inicio_adm.php');
    require_once(ROOT_PATH . '/modelo/BD.php');
    date_default_timezone_set('America/Argentina/Buenos_Aires');
    
    session_start();

    if(isset($_GET['eliminar']) && $_GET['eliminar'] === 'vista_inicio_adm'){
        if($_SERVER['REQUEST_METHOD'] === 'GET'){
            $id_servicio = $_GET['id'];
            $servicio_modelo = new servicios($conn);

            $eliminar_servicio = $servicio_modelo->dar_baja_servicios($id_servicio);

            if($eliminar_servicio && $eliminar_servicio->affected_rows > 0){
                echo '<script language = javascript>
                alert("servicio eliminado correctamente")
                self.location = "' . BASE_URL . '/vista/vista_adm/servicios_combos/vista_inicio_adm.php"
                </script>';
                exit;

            }else{
                echo '<script language = javascript>
                alert("hubo un error al eliminar el servicio")
                self.location = "' . BASE_URL . '/vista/vista_adm/servicios_combos/vista_inicio_adm.php"
                </script>';
                exit;
            }



        }else{
            echo '<script language = javascript>
            alert("hubo un fallo con el servidor")
            self.location = "' . BASE_URL . '/vista/vista_adm/servicios_combos/vista_inicio_adm.php"
            </script>';
            exit;
        }

    }elseif(isset($_GET['agregar']) && $_GET['agregar'] === 'vista_inicio_adm'){
        header("Location: " . BASE_URL . "/vista/vista_adm/servicios_combos/vista_agregar_servicio_adm.php");
        exit;


    }elseif(isset($_GET['detalle_servicio']) && $_GET['detalle_servicio'] === 'vista_inicio_adm'){
        $id_servicio = $_GET['id'];
        header("Location: " . BASE_URL . "/vista/vista_adm/servicios_combos/vista_detalle_servicio_adm.php?id=$id_servicio");
        exit;
    }elseif(isset($_POST['agregar']) && $_POST['agregar'] === 'vista_agregar_servicio_adm'){
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion_servicio'];
        $duracion = $_POST['duracion_servicio'];
        $tiempo_servicio = $_POST['tiempo_realizar'];
        $precio = $_POST['precio_servicio'];
        $trabajador_cargo = $_POST['trabajador_cargo'];
        $activo = ($_POST['activo'] === 'activo') ? 1 : 0;
        $tipo_servicio = $_POST['tipo_servicio'];

        $productos_usados = $_POST['productos'];
        $cantidad_usada = $_POST['cantidades'];

        $servicio_modelo = new servicios($conn);

        $id_servicio_insertado = $servicio_modelo->agregar_servicio($nombre, $descripcion, $duracion, $tiempo_servicio, $precio, $trabajador_cargo, $activo,$tipo_servicio);
        
        if($id_servicio_insertado){
            $servicio_modelo->agregar_productos_usados_servicio($id_servicio_insertado,$productos_usados,$cantidad_usada);

            header("Location: " . BASE_URL . "/vista/vista_adm/servicios_combos/vista_inicio_adm.php");
            exit;
        }else{
            echo '<script language = javascript>
            alert("no se pudo insertar un sevicio intentelo de nuevo")
            self.location = "' . BASE_URL . '/vista/vista_adm/servicios_combos/vista_inicio_adm.php"
            </script>';
            exit;
        }


    }elseif(isset($_GET['modificar']) && $_GET['modificar'] === 'vista_inicio_adm'){
        $id_servicio = $_GET['id'];
        header("Location: " . BASE_URL . "/vista/vista_adm/servicios_combos/vista_modificar_servicio_adm.php?id=$id_servicio");
        exit;


    }elseif(isset($_POST['modificar_servicio']) && $_POST['vista_modificar_servicio_adm'] === 'vista_modificar_servicio_adm'){
        $id_servicio = $_POST['id_servicio'];
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $duracion = $_POST['duracion'];
        $tiempo_servicio = $_POST['tiempo_servicio'];
        $precio = $_POST['precio'];
        $trabajador_cargo = $_POST['trabajadoraCargo'];
        $activo = $_POST['activo'];

        $tipo_servicio = $_POST['tipo_servicio'];

        $productos = $_POST['productos'];
        $cantidad_usada = $_POST['cantidades'];

        $servicio_modelo = new servicios($conn);

        $ejecutar_modificacion = $servicio_modelo->modificar_servicio($id_servicio,$nombre,$descripcion,$duracion,$tiempo_servicio,$precio,$trabajador_cargo,$activo,$productos,$cantidad_usada,$tipo_servicio);

        if ($ejecutar_modificacion === true) {
            echo '<script language = javascript>
            alert("se ha modificado el servicio correctamente")
            self.location = "' . BASE_URL . '/vista/vista_adm/servicios_combos/vista_inicio_adm.php"
            </script>';
            exit;

        }elseif($ejecutar_modificacion === false){
            echo '<script language = javascript>
            alert("hubo un fallo modificando el servicio")
            self.location = "' . BASE_URL . '/vista/vista_adm/servicios_combos/vista_modificar_servicio_adm.php"
            </script>';
            exit;

        }

        
    	
    }elseif(isset($_GET['detalle_combo']) && $_GET['detalle_combo'] === 'vista_inicio_adm'){
        $id_combo = $_GET['id'];
        header("Location: " . BASE_URL . "/vista/vista_adm/servicios_combos/vista_detalle_combo_adm.php?id=$id_combo");
        exit;

    }elseif(isset($_GET['agregar_combo']) && $_GET['agregar_combo'] === 'vista_inicio_adm'){
        header("Location: " . BASE_URL . "/vista/vista_adm/servicios_combos/vista_agregar_combo.php");
        exit;

    }elseif(isset($_POST['enviar_nuevo_combo']) && $_POST['enviar_nuevo_combo'] === 'vista_agregar_combo_adm'){
        $nombre = $_POST['nombre_combo'];
        $descripcion = $_POST['descripcion_combo'];
        $precio = $_POST['precio_combo'];
        $activo = $_POST['activo_combo'];
        $servicios_combos = $_POST['servicios_combo'];
        
        if(isset($_FILES['imagen_combo']) && $_FILES['imagen_combo']['error'] === UPLOAD_ERR_OK){
            $nombre_archivo = basename($_FILES['imagen_combo']['name']);
            $imagen = $_FILES['imagen_combo'];
            $fecha_actual = date('Y-m-d H:i:s');
            $servicio_modelo = new servicios($conn);

            $combo_insertado = $servicio_modelo->agregar_combo($nombre, $descripcion, $precio, $imagen, $activo,$fecha_actual ,$nombre_archivo,$servicios_combos);

            if($combo_insertado){
                echo '<script language = javascript>
                alert("Se ejecuto correctamente para agregar ")
                self.location = "' . BASE_URL . '/vista/vista_adm/servicios_combos/vista_inicio_adm.php"
                </script>';

                exit;

            }
        }

        

        

    }elseif(isset($_GET['dar_baja_combo']) && $_GET['dar_baja_combo'] === 'vista_inicio_adm'){
        $id_combo = $_GET['id'];

        $servicio_modelo = new servicios($conn);

        $dar_baja_combo = $servicio_modelo->dar_baja_combos($id_combo);

        if($dar_baja_combo){
            echo '<script language = javascript>
            alert("combo dado de baja/alta")
            self.location = "' . BASE_URL . '/vista/vista_adm/servicios_combos/vista_inicio_adm.php"
            </script>';
            exit;


        }

    }
    
    ?>
    
</body>
</html>
