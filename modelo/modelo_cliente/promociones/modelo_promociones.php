<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');

class promociones_cliente{
    private $conn;

    public function __construct($conn){
        $this->conn = $conn;

    }

    public function traer_servicios_combos_promocionados(){
        $traer_promociones = "SELECT 
        prom.id_promocion, 
        comb.nombre AS nombre_combo, 
        serv.nombre AS nombre_servicio, 
        prom.dias_promocion, 
        prom.descuento, 
        prom.puntos 
        FROM promociones prom
        LEFT JOIN combos comb ON prom.id_combos = comb.id_combos
        LEFT JOIN servicios serv ON serv.id_servicios = prom.id_servicios
        WHERE prom.activo = 1
        ";

        $resultado_traer_promociones = $this->conn->query($traer_promociones);

        return $resultado_traer_promociones;
    }

    public function traer_servicios_combos_promocionados_carrito($id_promo_carrito){
        $sql = "SELECT 
        prom.id_promocion,
        prom.id_combos,
        comb.nombre AS nombre_combo,
        prom.id_servicios,
        serv.nombre AS nombre_servicio,
        prom.dias_promocion,
        prom.descuento,
        prom.puntos
    FROM promociones prom
    LEFT JOIN combos comb ON prom.id_combos = comb.id_combos
    LEFT JOIN servicios serv ON serv.id_servicios = prom.id_servicios
    WHERE prom.id_promocion IN ($id_promo_carrito)";

        $resultado = $this->conn->query($sql);

        return $resultado;

    }

    public function traer_metodos_pagos(){
        $traer_metodos_pagos = "SELECT id_metodo_pago, metodo_pago, incremento, decremento, activo FROM metodos_pagos";
        $resultado_metods_pagos = $this->conn->query($traer_metodos_pagos);

        return $resultado_metods_pagos;

    }

    public function traer_lugares(){
        $traer_lugares = "SELECT id_lugar, nombre_lugar, cooordenadas, imagen_lugar, activo FROM lugares";
        $resultado_traer_lugares = $this->conn->query($traer_lugares);

        return $resultado_traer_lugares;
            
    }

    public function buscar_importe_servicio($id_servicio){
        $buscar_importe_servicio = $this->conn->prepare("SELECT id_servicios, nombre, descripcion, duracion, id_tiempo_servicio, precio_servicio, activo, id_tipo_servicio, imagen FROM servicios WHERE id_servicios = ?");
        $buscar_importe_servicio->bind_param("i",$id_servicio);

        if($buscar_importe_servicio->execute()){
            $resultado_buscar_importe = $buscar_importe_servicio->get_result();

            return $resultado_buscar_importe;

        }else{
            echo "hubo un error en el modelo buscar_importe_servicio";
        }

    }

    public function obtener_trabajadores_servicio($id_servicio){
        $sql = $this->conn->prepare("
        SELECT ts.id_trabajador, np.intereses
        FROM trabajadores_servicios ts
        INNER JOIN trabajadores t ON ts.id_trabajador = t.id_trabajador
        INNER JOIN nivel_profesionalismo np ON t.id_nivel_profesionalismo = np.id_nivel_profesionalismo
        WHERE ts.id_servicio = ?
        ");

        $sql->bind_param("i", $id_servicio);
        $sql->execute();
        return $sql->get_result();
    }


    public function buscar_combos_servicios($id_combo){
        $traer_combos_servicios = $this->conn->prepare("SELECT id_combo_servicio, id_combos,id_servicios FROM combo_servicios WHERE id_combos = ?");
        $traer_combos_servicios->bind_param("i",$id_combo);

        if($traer_combos_servicios->execute()){
            $resultado_combo_servicios = $traer_combos_servicios->get_result();

            return $resultado_combo_servicios;

        }else{
            echo "hubo un error en la funcion buscar_combos_servicios";
        }

    }

    public function obtener_tipo_servicio($id_tipo_servicio){
        $sql = $this->conn->prepare("
        SELECT tipo_servicio, intereses
        FROM tipo_servicio
        WHERE id_tipo_servicio = ?
        ");

        $sql->bind_param("i", $id_tipo_servicio);

        if($sql->execute()){
            $resultado_traer_tipo_servicio = $sql->get_result();

            return $resultado_traer_tipo_servicio;

        }else{
            echo "hubo un error en la funcion de obtener tipo servicio";
        }

    }

    public function buscar_relacion_personas_usr($id_usuario){
        $traer_id_cliente_usr_relacionado = $this->conn->prepare("SELECT usr.id_usuario, usr.nombre_usuario, usr.contrasena, pers_usr.id_cliente FROM usuarios usr
        INNER JOIN usuarios_personas pers_usr ON usr.id_usuario = pers_usr.id_usuario WHERE usr.id_usuario = ?");

        $traer_id_cliente_usr_relacionado->bind_param("i",$id_usuario);

        if($traer_id_cliente_usr_relacionado->execute()){
            $resultado_cliente_relacionado = $traer_id_cliente_usr_relacionado->get_result();

            $array_cliente_relacionado = $resultado_cliente_relacionado->fetch_assoc();

            $cliente_relacionado = $array_cliente_relacionado['id_cliente'];

            return $cliente_relacionado;

        }else{
            echo "hubo un error en la funcion de buscar_relacion_personas_usr";
        }


    }

    public function insertar_cita($id_cliente,$fecha_cita,$activo,$lugar){
        $insertar_cita = $this->conn->prepare("INSERT INTO citas(id_cliente, fecha_cita, activo, id_lugar) VALUES (?,?,?,?)");
        $insertar_cita->bind_param("isii",$id_cliente,$fecha_cita,$activo,$lugar);

        if($insertar_cita->execute()){
            $ultima_cita = $insertar_cita->insert_id;

            return $ultima_cita;

        }else{
            echo "hubo un error en la funcion de insertar cita";
        }

    }

    public function insertar_detalle_cita($id_cita,$id_promocion){
        $insertar_detalle_cita = $this->conn->prepare("INSERT INTO detalle_cita(id_cita,id_promocion) VALUES (?,?)");
        $insertar_detalle_cita->bind_param("ii",$id_cita,$id_promocion);

        if($insertar_detalle_cita->execute()){
            return true;

        }else{
            echo "hubo un error en la funcion de insertar detalle cita";
        }
    }

    public function insertar_caja_promociones($fecha_venta,$id_cita,$monto_total){
        $insertar_caja_promociones = $this->conn->prepare("INSERT INTO caja_promociones(fecha_venta, id_cita, monto_total) VALUES (?,?,?)");
        $insertar_caja_promociones->bind_param("ssd",$fecha_venta,$id_cita,$monto_total);

        if($insertar_caja_promociones->execute()){
            $ultima_caja_promociones = $insertar_caja_promociones->insert_id;

            return $ultima_caja_promociones;

        }else{
            echo "hubo un error en la funcion insertar caja";
        }

    }

    public function insertar_detalle_caja_promociones($id_caja_promociones,$monto,$id_metodo_pago){
        $insertar_detalle_caja_promociones = $this->conn->prepare("INSERT INTO detalle_caja_promociones(id_caja_promociones, monto, id_metodo_pago) VALUES (?,?,?)");
        $insertar_detalle_caja_promociones->bind_param("idi",$id_caja_promociones,$monto,$id_metodo_pago);

        if($insertar_detalle_caja_promociones->execute()){
            return true;

        }else{
            echo "hubo un error en la funcion de insertar el detalle de la caja de promociones";
        }


    }
    


   

    

}

?>