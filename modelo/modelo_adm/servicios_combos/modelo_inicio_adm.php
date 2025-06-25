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
        $traer_servicios = "SELECT servicios.id_servicios, servicios.nombre, servicios.descripcion, servicios.duracion,tiempo_servicio.tiempo_servicio, servicios.precio, trabajadores.nombre_trabajador, servicios.activo 
        FROM servicios
        INNER JOIN trabajadores_servicios ON trabajadores_servicios.id_servicio = servicios.id_servicios
        INNER JOIN trabajadores 
        ON trabajadores.id_trabajador = trabajadores_servicios.id_trabajador
        INNER JOIN tiempo_servicio
        ON tiempo_servicio.id_tiempo_servicio = servicios.id_tiempo_servicio";
        $resultado_traer_servicios = $this->conn->query($traer_servicios);

        return $resultado_traer_servicios;
    }

    public function eliminar_servicios($id_servicio){
        $eliminar_servicio = $this->conn->prepare("DELETE FROM servicios WHERE id_servicios = ?");
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
            'productosJS' => $productosJS
        ];
        

    }

    public function detalle_servicio($id_servicio){
        $traer_detalle_servicio = $this->conn->prepare("SELECT prod_used.id_products_usados, serv.nombre, inv.nombre_producto, prod_used.cantidad_usada 
        FROM product_usados prod_used
        INNER JOIN servicios serv ON serv.id_servicios = prod_used.id_servicios 
        INNER JOIN inventario inv ON inv.id_inventario = prod_used.id_inventario
        WHERE serv.id_servicios = ?");

        $traer_detalle_servicio->bind_param("i",$id_servicio);
        $traer_detalle_servicio->execute();

        $resultado_detalle_servicio = $traer_detalle_servicio->get_result();

        return $resultado_detalle_servicio;


    }

    public function agregar_servicio($nombre_servicio,$descripcion,$duracion,$id_tiempo_servicio,$precio,$id_trabajador,$activo){
        $insertar_nuevo_servicio = $this->conn->prepare("INSERT INTO servicios(nombre, descripcion, duracion, id_tiempo_servicio, precio ,activo) VALUES (?,?,?,?,?,?)");
        $insertar_nuevo_servicio->bind_param("ssiiii",$nombre_servicio,$descripcion,$duracion,$id_tiempo_servicio,$precio,$activo);

       if($insertar_nuevo_servicio->execute()){
        $id_servicio_insertado = $this->conn->insert_id;
        $insertar_trabajores_servicios = $this->conn->prepare("INSERT INTO trabajadores_servicios(id_trabajador, id_servicio) VALUES (?,?)");
        $insertar_trabajores_servicios->bind_param('ii',$id_trabajador,$id_servicio_insertado);
        
        if($insertar_trabajores_servicios->execute()){
            $id_trabajadores_servicios = $this->conn->insert_id;
            $insert_trab_servi = "UPDATE servicios SET id_trabajadores_servicios=$id_trabajadores_servicios WHERE $id_servicio_insertado";
            $resultado = $this->conn->query($insert_trab_servi);

            return $id_servicio_insertado;

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
        $seleccionar_servicio = $this->conn->prepare("SELECT servicios.id_servicios, servicios.nombre, servicios.descripcion, servicios.duracion,tiempo_servicio.tiempo_servicio, servicios.precio, trabajadores.nombre_trabajador, servicios.activo 
        FROM servicios
        INNER JOIN trabajadores_servicios ON trabajadores_servicios.id_servicio = servicios.id_servicios
        INNER JOIN trabajadores 
        ON trabajadores.id_trabajador = trabajadores_servicios.id_trabajador
        INNER JOIN tiempo_servicio
        ON tiempo_servicio.id_tiempo_servicio = servicios.id_tiempo_servicio WHERE id_servicios = ?");
        $seleccionar_servicio->bind_param('i',$id_servicio);
        

        if($seleccionar_servicio->execute()){
            $resultado = $seleccionar_servicio->get_result();
            $bucle_datos_selec_serv = $resultado->fetch_assoc();
            
            return $bucle_datos_selec_serv;


        }else{
            echo '<script language = javascript>
            alert("hubo un fallo trayendo ejecutando la consulta de modificar servicios formulario")
            self.location = "' . BASE_URL . '/vista/vista_adm/servicios_combos/vista_inicio_adm.php"
            </script>';
            exit;
        }

        
    }

    



    
}

?>