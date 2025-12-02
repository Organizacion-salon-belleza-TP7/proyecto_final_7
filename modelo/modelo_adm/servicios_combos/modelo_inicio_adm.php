
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');

class servicios {
    private $conn;

    public function __construct($conn){
        $this->conn = $conn;
    }

    // Método para traer todos los servicios
    public function mostrar_servicios() {
        $query = "
            SELECT 
                s.id_servicios, 
                s.nombre, 
                s.descripcion, 
                s.duracion,
                COALESCE(ts.tiempo_servicio, 'Sin tiempo definido') AS tiempo_servicio,
                s.precio_servicio,
                t.nombre_trabajador,
                s.activo,
                COALESCE(tp.tipo_servicio, 'Sin tipo definido') AS tipo_servicio,
                s.imagen
            FROM servicios s
            LEFT JOIN trabajadores_servicios tsr 
                ON tsr.id_servicio = s.id_servicios
            LEFT JOIN trabajadores t 
                ON t.id_trabajador = tsr.id_trabajador
            LEFT JOIN tiempo_servicio ts 
                ON ts.id_tiempo_servicio = s.id_tiempo_servicio
            LEFT JOIN tipo_servicio tp 
                ON tp.id_tipo_servicio = s.id_tipo_servicio
        ";
        return $this->conn->query($query);
    }


	public function dar_baja_servicios($id_servicio){
		$encontrar_servicio = $this->conn->prepare("SELECT id_servicios, nombre, descripcion, duracion, id_tiempo_servicio, precio_servicio, id_trabajadores_servicios, activo 
		FROM servicios WHERE id_servicios = ?");

		$encontrar_servicio->bind_param("i",$id_servicio);
		$encontrar_servicio->execute();

		$array_asociativo_elim_serv = $encontrar_servicio->fetch();



		if ($array_asociativo_elim_serv['activo'] == 1) {
			$dar_alta_servicio = $this->conn->prepare("UPDATE servicios SET activo = 1 WHERE ?");

			$dar_alta_servicio->bind_param("i",$id_servicio);

			$dar_alta_servicio->execute();

		}elseif ($array_asociativo_elim_serv['activo'] == 0) {
			$dar_baja_servicio = $this->conn->prepare("UPDATE servicios SET activo = 0 WHERE ?");

			$dar_baja_servicio->bind_param("i",$id_servicio);

			$dar_baja_servicio->execute();
		}else {
			echo '<script language = javascript>
                alert("hubo un fallo tratando de dar de baja el servicio")
                self.location = "' . BASE_URL . '/vista/vista_adm/servicios_combos/vista_inicio_adm.php"
                </script>';
                exit;

		}
	
        $eliminar_servicio = $this->conn->prepare("");
        $eliminar_servicio->bind_param('i',$id_servicio);
        $eliminar_servicio->execute();

        return $eliminar_servicio;
        

    }

    public function formulario_agregar_servicio(){
        $traer_tiempo = "SELECT id_tiempo_servicio, tiempo_servicio FROM tiempo_servicio";
        $resultado_tiempo = $this->conn->query($traer_tiempo);

        $traer_trabajador = "SELECT id_trabajador, nombre_trabajador FROM trabajadores";
        $resultado_trabajador = $this->conn->query($traer_trabajador);

        $traer_inventario = "SELECT id_inventario, nombre_producto FROM inventario";
        $resultado_inventario = $this->conn->query($traer_inventario);

        $traer_tipo_servicio = "SELECT id_tipo_servicio, tipo_servicio, intereses FROM tipo_servicio";
        $resultado_tipo_servicio = $this->conn->query($traer_tipo_servicio);

        $productosJS = [];

        if($resultado_inventario && $resultado_inventario->num_rows > 0){
            while($row3 = $resultado_inventario->fetch_assoc()){
                $productosJS[] = $row3;

            }
        }else{
            echo '<script language = javascript>
            alert("hubo un fallo trayendo los datos de los servicios")
            self.location = "' . BASE_URL . '/vista/vista_adm/servicios_combos/vista_inicio_adm.php"
            </script>';
            exit;
        }

        return [
            'tiempos' => $resultado_tiempo,
            'trabajadores' => $resultado_trabajador,
            'inventario' => $resultado_inventario,
            'tipo_servicio' => $resultado_tipo_servicio,
            'productosJS' => $productosJS
        ];
        

    }

    public function detalle_servicio($id_servicio){
        $traer_detalle_servicio = $this->conn->prepare("SELECT prod_used.id_products_usados, serv.nombre, inv.nombre_producto, prod_used.cantidad_usada,tp.tipo_servicio,tp.intereses 
        FROM product_usados prod_used
        INNER JOIN servicios serv ON serv.id_servicios = prod_used.id_servicios 
        INNER JOIN inventario inv ON inv.id_inventario = prod_used.id_inventario
        INNER JOIN tipo_servicio tp ON tp.id_tipo_servicio = serv.id_tipo_servicio
        WHERE serv.id_servicios = ?");

        $traer_detalle_servicio->bind_param("i",$id_servicio);
        $traer_detalle_servicio->execute();

        $resultado_detalle_servicio = $traer_detalle_servicio->get_result();

        return $resultado_detalle_servicio;


    }

    public function agregar_servicio($nombre_servicio,$descripcion,$duracion,$id_tiempo_servicio,$precio,$id_trabajador,$activo,$tipo_servicio,$imagen,$nombre_imagen){
        if($imagen && $imagen['error'] === UPLOAD_ERR_OK){
            $carpeta_destino = ROOT_PATH . "/imagenes/servicios/";

        }

        $ruta_destino = $carpeta_destino . $nombre_imagen;

        if(move_uploaded_file($imagen['tmp_name'],$ruta_destino)){
            $ruta_imagen = "/imagenes/servicios/" . $nombre_imagen;

        }else{
            echo "no se pudo enviar la imagen";
            die();
        }

        $insertar_nuevo_servicio = $this->conn->prepare("INSERT INTO servicios(nombre, descripcion, duracion, id_tiempo_servicio, precio_servicio ,activo,id_tipo_servicio,imagen) VALUES (?,?,?,?,?,?,?,?)");
        $insertar_nuevo_servicio->bind_param("ssiiiiis",$nombre_servicio,$descripcion,$duracion,$id_tiempo_servicio,$precio,$activo,$tipo_servicio,$nombre_imagen);

       if($insertar_nuevo_servicio->execute()){
        $id_servicio_insertado = $this->conn->insert_id;
        $insertar_trabajores_servicios = $this->conn->prepare("INSERT INTO trabajadores_servicios(id_trabajador, id_servicio) VALUES (?,?)");
        $insertar_trabajores_servicios->bind_param('ii',$id_trabajador,$id_servicio_insertado);
        
        if($insertar_trabajores_servicios->execute()){
            if($id_trabajadores_servicios = $this->conn->insert_id){
                $insert_trab_servi = "UPDATE trabajadores_servicios SET id_trabajador=$id_trabajador WHERE $id_servicio_insertado";
                $resultado = $this->conn->query($insert_trab_servi);

                return $id_servicio_insertado;

            }
            
        }else{
            echo "hubo un fallo al insertar el id del servicio_trabajador en servicios";
            die();
        }

        
       }else{
        echo "hubo un fallo al insertar en servicios";
        die();
        
       }

    }

    public function agregar_productos_usados_servicio($id_servicio_insertado,$producto,$cantidad_usada){
        $insertar_product_usados_servicio = $this->conn->prepare("INSERT INTO product_usados(id_servicios, id_inventario, cantidad_usada) VALUES (?,?,?)");
        for($i = 0; $i < count($producto); $i++){
            $id_inventario = $producto[$i];
            $cantidad_usada = $cantidad_usada[$i];
            $insertar_product_usados_servicio->bind_param('iii',$id_servicio_insertado,$producto,$cantidad_usada);
            $insertar_product_usados_servicio->execute();


        }
        return true;

    }

    public function formulario_modificar($id_servicio){
        $seleccionar_servicio = $this->conn->prepare("SELECT servicios.id_servicios, servicios.nombre, servicios.descripcion, servicios.duracion,tiempo_servicio.tiempo_servicio, servicios.precio_servicio, trabajadores.nombre_trabajador, servicios.activo,tiempo_servicio.id_tiempo_servicio, trabajadores.id_trabajador,tipo_servicio.id_tipo_servicio,servicios.imagen 
        FROM servicios
        INNER JOIN trabajadores_servicios ON trabajadores_servicios.id_servicio = servicios.id_servicios
        INNER JOIN trabajadores 
        ON trabajadores.id_trabajador = trabajadores_servicios.id_trabajador
        INNER JOIN tiempo_servicio
        ON tiempo_servicio.id_tiempo_servicio = servicios.id_tiempo_servicio 
        INNER JOIN tipo_servicio ON tipo_servicio.id_tipo_servicio = servicios.id_tipo_servicio
        WHERE id_servicios = ?");
        $seleccionar_servicio->bind_param('i',$id_servicio);
        

	    if($seleccionar_servicio->execute()){

            $resultado_traer_servicios_mod = $seleccionar_servicio->get_result();

            $array_servicios_mod = $resultado_traer_servicios_mod->fetch_assoc();

			$tiempo_servicio_nuevo = $this->conn->query("SELECT id_tiempo_servicio, tiempo_servicio 
			FROM tiempo_servicio");

			$nuevo_trabajador = $this->conn->query("SELECT id_trabajador, nombre_trabajador, dni, id_tipo_trabajador, id_nivel_profesionalismo, activo 
			FROM trabajadores");

            $tipo_servicio = $this->conn->query("SELECT id_tipo_servicio, tipo_servicio, intereses FROM tipo_servicio");

			$traer_inventario_modificado = $this->conn->query("SELECT id_inventario, nombre_producto, stock,vencimiento, precio_producto, precio_venta, imagen_producto, id_proveedor 
			FROM inventario");

			$productos_usados = $this->conn->prepare("SELECT id_inventario, cantidad_usada 
		        FROM product_usados 
			WHERE id_servicios = ?");

			$productos_usados->bind_param('i',$id_servicio);

			$productos_usados->execute();

			$resultado_productos_usados = $productos_usados->get_result();

			$productos_array2 = [];

			while ($row = $resultado_productos_usados->fetch_assoc()) {
				$productos_array2[] = $row; 
				
			}




        return [
            'servicio' => $array_servicios_mod,
            'tiempos' => $tiempo_servicio_nuevo,
		    'trabajadores' => $nuevo_trabajador,
            'tipo_servicio' => $tipo_servicio,
		    'productosUsados' => $productos_array2,
            'productosJS' => $traer_inventario_modificado 
                
        ];
                        
			

	    }
		return null;

        
	}

	public function modificar_servicio($id_servicio,$nombre,$descripcion,$duracion,$tiempo_servicio,$precio,$trabajador,$activo,$productos,$cantidad_usada,$tipo_servicio,$nombre_imagen,$imagen) {

    // --- Manejo de imagen ---
        $imagen_final = null;

        if($imagen && $imagen['error'] === UPLOAD_ERR_OK){
            $carpeta_destino = ROOT_PATH . "/imagenes/servicios/";
            $ruta_destino = $carpeta_destino . $nombre_imagen;

            if(move_uploaded_file($imagen['tmp_name'],$ruta_destino)){
            $imagen_final = $nombre_imagen; // Guardamos nuevo nombre
            } else {
                echo "no se pudo enviar la imagen";
                die();
            }
        }

        // --- Armar query según corresponda ---
        if($imagen_final){
            // Con nueva imagen
            $sql = "UPDATE servicios 
                SET nombre = ?, descripcion = ?, duracion = ?, id_tiempo_servicio = ?, precio_servicio = ?, activo = ?, id_tipo_servicio = ?, imagen = ?
                WHERE id_servicios = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('ssiidiisi',$nombre,$descripcion,$duracion,$tiempo_servicio,$precio,$activo,$tipo_servicio,$imagen_final,$id_servicio);
        } else {
        // Sin nueva imagen
            $sql = "UPDATE servicios 
                SET nombre = ?, descripcion = ?, duracion = ?, id_tiempo_servicio = ?, precio_servicio = ?, activo = ?, id_tipo_servicio = ?
                WHERE id_servicios = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('ssiidiii',$nombre,$descripcion,$duracion,$tiempo_servicio,$precio,$activo,$tipo_servicio,$id_servicio);
        }

        if($stmt->execute()){

            // --- Actualizar trabajador asignado ---
            $updatear_trabajadores_servicios = $this->conn->prepare("UPDATE trabajadores_servicios SET id_trabajador = ? WHERE id_servicio = ?");
            $updatear_trabajadores_servicios->bind_param('ii',$trabajador,$id_servicio);

                if($updatear_trabajadores_servicios->execute()){
                #Trae los productos actuales
                    $productos_actuales = [];
                    $res = $this->conn->prepare("SELECT id_inventario FROM product_usados WHERE id_servicios = ?");
                    $res->bind_param('i',$id_servicio);
                    $res->execute();
                    $resultado = $res->get_result();

                    while ($row = $resultado->fetch_assoc()) {
                        $productos_actuales[] = $row['id_inventario'];
                    }

                #Array que trae los nuevos productos
                $nuevos_productos = $productos;

                foreach($productos as $i => $id_inventario){
                    $cantidad = $cantidad_usada[$i];

                    if(in_array($id_inventario,$productos_actuales)){
                    // Ya existía, solo actualizo cantidad
                        $update = $this->conn->prepare("UPDATE product_usados SET cantidad_usada = ? WHERE id_servicios = ? AND id_inventario = ?");
                        $update->bind_param('iii',$cantidad,$id_servicio,$id_inventario);
                        $update->execute();
                    } else {
                    // Nuevo producto
                        $insert = $this->conn->prepare("INSERT INTO product_usados(id_servicios,id_inventario,cantidad_usada) VALUES (?,?,?)");
                        $insert->bind_param('iii',$id_servicio,$id_inventario,$cantidad);
                        $insert->execute();
                    }
                }

                // Eliminar productos quitados
                foreach($productos_actuales as $id_existente){
                    if(!in_array($id_existente,$nuevos_productos)){
                        $delete = $this->conn->prepare("DELETE FROM product_usados WHERE id_servicios = ? AND id_inventario = ?");
                        $delete->bind_param('ii',$id_servicio,$id_existente);
                        $delete->execute();
                    }
                }

                return true;
            } else {
                echo '<script language=javascript>
                alert("hubo un fallo updateando los datos de los trabajadores")
                self.location = "' . BASE_URL . '/vista/vista_adm/servicios_combos/vista_inicio_adm.php"
                </script>';
                exit;
            }
        } else {
            return false;
        }
    }


	public function mostrar_combos(){
		$mostrar_combos = "SELECT id_combos, nombre,descripcion_combo,precio,imagen,activo,fecha_creacion 
		FROM combos";

		$traer_combos = $this->conn->query($mostrar_combos);

		return $traer_combos;



		
	}

    public function detalle_combo($id_combo) {
        $traer_detalle_combo = $this->conn->prepare("
        SELECT 
            cs.id_combo_servicio,
            s.id_servicios,
            s.nombre,
            s.descripcion,
            s.precio_servicio,
            ts.tipo_servicio,
            ts.intereses,
            c.nombre as nombre_combo,
            c.descripcion_combo,
            c.precio as precio_combo
        FROM combo_servicios cs
        INNER JOIN servicios s ON cs.id_servicios = s.id_servicios
        INNER JOIN tipo_servicio ts ON s.id_tipo_servicio = ts.id_tipo_servicio
        INNER JOIN combos c ON cs.id_combos = c.id_combos
        WHERE cs.id_combos = ?
        ");
    
        $traer_detalle_combo->bind_param('i', $id_combo);
        $traer_detalle_combo->execute();
    
        return $traer_detalle_combo->get_result();
    }

    public function formulario_agregar_combo(){
        $traer_servicios = "SELECT id_servicios, nombre, descripcion, duracion, id_tiempo_servicio, precio_servicio, activo, id_tipo_servicio 
        FROM servicios WHERE activo = 1";

        $traer_servicios = $this->conn->query($traer_servicios);

        $servicios = [];

        while($row_servicos = $traer_servicios->fetch_assoc()){
            $servicios[] = $row_servicos;
        }

        return $servicios;
    }

    public function agregar_combo($nombre,$descripcion,$precio,$imagen,$activo,$fecha_creacion,$nombre_archivo,$servicios_combos){
        $ruta_imagen = null;


        if($imagen && $imagen['error'] === UPLOAD_ERR_OK){
            $carpeta_destino = ROOT_PATH . "/imagenes/imagenes_combos/";
        }

        $ruta_destino = $carpeta_destino . $nombre_archivo;

        if(move_uploaded_file($imagen['tmp_name'],$ruta_destino)){
            $ruta_imagen = "/imagenes/imagenes_combos/" . $nombre_archivo;

        }else{
            echo "no se pudo enviar la imagen";
            die();
        }

        $activo_valor = ($activo === "activo") ? 1 : 0;

        if($activo === "activo"){
            $insertar_combo = $this->conn->prepare("INSERT INTO combos(nombre, descripcion_combo, precio, imagen, activo, fecha_creacion) VALUES (?,?,?,?,?,?)");
            $insertar_combo->bind_param("ssisis",$nombre,$descripcion,$precio,$nombre_archivo,$activo_valor,$fecha_creacion);

            if($insertar_combo->execute()){
                $id_combo_insertado = $this->conn->insert_id;

                // Insertar relaciones en tabla combos_servicios
                $servicios_array = explode(',',$servicios_combos);
                foreach($servicios_array as $id_servicio){
                    $insertar_serviciosCombos = $this->conn->prepare("INSERT INTO combo_servicios(id_combos, id_servicios) VALUES (?,?)");

                if($insertar_combo){
                    $insertar_serviciosCombos->bind_param("ii",$id_combo_insertado,$id_servicio);
                    $insertar_serviciosCombos->execute();
                }

                }
            return true;
            }
        
        }elseif($activo === "inactivo"){
            $insertar_combo = $this->conn->prepare("INSERT INTO combos(nombre, descripcion_combo, precio, imagen, activo, fecha_creacion) VALUES (?,?,?,?,?,?)");
            $insertar_combo->bind_param("ssisis",$nombre,$descripcion,$precio,$nombre_archivo,$activo_valor,$fecha_creacion);

            if($insertar_combo->execute()){
                $id_combo_insertado = $this->conn->insert_id;

                // Insertar relaciones en tabla combos_servicios
                $servicios_array = explode(',',$servicios_combos);
                foreach($servicios_array as $id_servicio){
                    $insertar_serviciosCombos = $this->conn->prepare("INSERT INTO combo_servicios(id_combos, id_servicios) VALUES (?,?)");

                if($insertar_combo){
                    $insertar_serviciosCombos->bind_param("ii",$id_combo_insertado,$id_servicio);
                    $insertar_serviciosCombos->execute();
                }

                }
            return true;
            }

        }else{
            echo '<script language = javascript>
                alert("hubo un fallo updateando los agregando los servicios relacionados con los combos")
                self.location = "' . BASE_URL . '/vista/vista_adm/servicios_combos/vista_inicio_adm.php"
                </script>';
                exit;
        }

        
        
        
    }

    public function dar_baja_combos($id_combo){
        $buscar_combo = $this->conn->prepare("SELECT id_combos, nombre, descripcion_combo, precio, imagen, activo, fecha_creacion 
        FROM combos WHERE id_combos = ?");
        $buscar_combo->bind_param('i',$id_combo);

        if($buscar_combo->execute()){
            $resultado = $buscar_combo->get_result();
            if($resultado && $resultado->num_rows > 0){
                $dar_bajaAlta_combo = $resultado->fetch_assoc();

                if($dar_bajaAlta_combo['activo'] == 1){
                    $dar_baja_combo = "UPDATE combos SET activo = 0 WHERE id_combos = $id_combo";
                    $ejecutar_baja_combo = $this->conn->query($dar_baja_combo);

                    return true;

                }elseif($dar_bajaAlta_combo['activo'] == 0){
                    $dar_baja_combo = "UPDATE combos SET activo = 1 WHERE id_combos = $id_combo";
                    $ejecutar_baja_combo = $this->conn->query($dar_baja_combo);

                    return true;

                }else{
                    echo '<script language = javascript>
                    alert("hubo un fallo eliminandi los combos")
                    self.location = "' . BASE_URL . '/vista/vista_adm/servicios_combos/vista_inicio_adm.php"
                    </script>';
                    exit;
                }

            }

            

        }
    }

    public function formulario_modificar_combo($id_combo){
    // Traer información del combo y servicios asociados
    $traer_combo = $this->conn->prepare("
        SELECT comb.id_combos, comb.nombre, comb.descripcion_combo, comb.precio, comb.imagen, comb.activo, comb.fecha_creacion,
               cs.id_servicios
        FROM combos comb
        LEFT JOIN combo_servicios cs ON comb.id_combos = cs.id_combos
        WHERE comb.id_combos = ?
    ");
    $traer_combo->bind_param('i', $id_combo);

    if($traer_combo->execute()){
        $resultado = $traer_combo->get_result();

        $combo = [];
        $servicios_ids = [];

        while($row = $resultado->fetch_assoc()){
            // Solo llenamos datos del combo una vez
            if(empty($combo)){
                $combo = [
                    'id_combos' => $row['id_combos'],
                    'nombre' => $row['nombre'],
                    'descripcion_combo' => $row['descripcion_combo'],
                    'precio' => $row['precio'],
                    'imagen' => $row['imagen'],
                    'activo' => $row['activo'],
                    'fecha_creacion' => $row['fecha_creacion'],
                    'servicios' => []
                ];
            }

            if($row['id_servicios']){
                $servicios_ids[] = $row['id_servicios'];
            }
        }

        // Traer datos completos de los servicios asociados
        $servicios = [];
        if(count($servicios_ids) > 0){
            $ids_string = implode(',', $servicios_ids);
            $query_servicios = "SELECT id_servicios, nombre, descripcion, duracion, id_tiempo_servicio, precio_servicio, activo, id_tipo_servicio, imagen
                                FROM servicios
                                WHERE id_servicios IN ($ids_string)";
            $res_servicios = $this->conn->query($query_servicios);

            while($row_serv = $res_servicios->fetch_assoc()){
                $combo['servicios'][] = $row_serv;
            }
        }

        return $combo;
        }
    }

    public function modificar_combo($id_combo,$nombre,$descripcion,$precio,$imagen,$nombre_imagen,$estado,$servicios){
        $modificar_combo = $this->conn->prepare("UPDATE combos SET 
        nombre = '$nombre',descripcion_combo = '$descripcion',precio = $precio,imagen = '$nombre_imagen',activo = $estado
        WHERE id_combos = ?");

        $modificar_combo->bind_param('i',$id_combo);

        if($modificar_combo->execute()){
            if($imagen && $imagen['error'] === UPLOAD_ERR_OK){
                $carpeta_destino = ROOT_PATH . "/imagenes/imagenes_combos/";
                $ruta_destino = $carpeta_destino . $nombre_imagen;

            if(move_uploaded_file($imagen['tmp_name'],$ruta_destino)){
                $ruta_imagen = "/imagenes/imagenes_combos/" . $nombre_imagen;
            }else{
                echo "No se pudo enviar la imagen";
                die();
            }

            }
            $this->conn->query("DELETE FROM combo_servicios WHERE id_combos = $id_combo");

            if(!empty($servicios)){
                foreach($servicios as $id_servicio){
                $insert = $this->conn->prepare("INSERT INTO combo_servicios(id_combos, id_servicios) VALUES (?, ?)");
                $insert->bind_param("ii", $id_combo, $id_servicio);
                $insert->execute();
                }
            }

            return true;

        }

    }
}

?>
