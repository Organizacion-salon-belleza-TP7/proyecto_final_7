<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');

class servicios{
    private $conn;

    public function __construct($conn){
        $this->conn = $conn;

    }

    public function mostrar_servicios(){
        $traer_servicios = "SELECT servicios.id_servicios, servicios.nombre, servicios.descripcion, servicios.duracion,tiempo_servicio.tiempo_servicio, servicios.precio, trabajadores.nombre_trabajador, servicios.activo,tipo_servicio.tipo_servicio ,servicios.imagen
        FROM servicios
        INNER JOIN trabajadores_servicios ON trabajadores_servicios.id_servicio = servicios.id_servicios
        INNER JOIN trabajadores 
        ON trabajadores.id_trabajador = trabajadores_servicios.id_trabajador
        INNER JOIN tiempo_servicio
        ON tiempo_servicio.id_tiempo_servicio = servicios.id_tiempo_servicio
        INNER JOIN tipo_servicio ON servicios.id_tipo_servicio = tipo_servicio.id_tipo_servicio";
        $resultado_traer_servicios = $this->conn->query($traer_servicios);

        return $resultado_traer_servicios;
    }

	public function dar_baja_servicios($id_servicio){
		$encontrar_servicio = $this->conn->prepare("SELECT id_servicios, nombre, descripcion, duracion, id_tiempo_servicio, precio, id_trabajadores_servicios, activo 
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

        $insertar_nuevo_servicio = $this->conn->prepare("INSERT INTO servicios(nombre, descripcion, duracion, id_tiempo_servicio, precio ,activo,id_tipo_servicio,imagen) VALUES (?,?,?,?,?,?,?,?)");
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
        $seleccionar_servicio = $this->conn->prepare("SELECT servicios.id_servicios, servicios.nombre, servicios.descripcion, servicios.duracion,tiempo_servicio.tiempo_servicio, servicios.precio, trabajadores.nombre_trabajador, servicios.activo,tiempo_servicio.id_tiempo_servicio, trabajadores.id_trabajador,tipo_servicio.id_tipo_servicio 
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

	public function modificar_servicio($id_servicio,$nombre,$descripcion,$duracion,$tiempo_servicio,$precio,$trabajador,$activo,$productos,$cantidad_usada,$tipo_servicio) {

        $insertar_modificacion_servicio = $this->conn->prepare("UPDATE servicios SET nombre = ?,descripcion = ?,duracion = ?,id_tiempo_servicio = ?,precio = ?,activo = ?,id_tipo_servicio = ? WHERE id_servicios = ?");
        $insertar_modificacion_servicio->bind_param('ssiidiii',$nombre,$descripcion,$duracion,$tiempo_servicio,$precio,$activo,$tipo_servicio,$id_servicio);
        
        if($insertar_modificacion_servicio->execute()){
            

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

                    #Si exitia algun producto sigue igual
                    if(in_array($id_inventario,$productos_actuales)){
                        $update = $this->conn->prepare("UPDATE product_usados SET cantidad_usada = ? WHERE id_servicios = ? AND id_inventario = ?");
                        $update->bind_param('iii',$cantidad,$id_servicio,$id_inventario);
                        $update->execute();


                    }else{
                        #Si el la modificacion agrego un nuevo producto se ejecuta esto
                        $insert = $this->conn->prepare("INSERT INTO product_usados(id_servicios,id_inventario,cantidad_usada) VALUES (?,?,?)");
                        $insert->bind_param('iii',$id_servicio,$id_inventario,$cantidad);
                        $insert->execute();
                    }

                }

                foreach($productos_actuales as $id_existente){
                    if(!in_array($id_existente,$nuevos_productos)){
                        #En caso de que elimino un producto en la modificacion
                        $delete = $this->conn->prepare("DELETE FROM product_usados WHERE id_servicios = ? AND id_inventario = ?");
                        $delete->bind_param('ii',$id_servicio,$id_existente);
                        $delete->execute();

                    }

                }

                return true;
            }else{
                echo '<script language = javascript>
                alert("hubo un fallo updateando los datos de los trabajadores")
                self.location = "' . BASE_URL . '/vista/vista_adm/servicios_combos/vista_inicio_adm.php"
                </script>';
                exit;
            }
        }else{
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
            s.precio,
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
        $traer_servicios = "SELECT id_servicios, nombre, descripcion, duracion, id_tiempo_servicio, precio, activo, id_tipo_servicio 
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

    




}




?>
