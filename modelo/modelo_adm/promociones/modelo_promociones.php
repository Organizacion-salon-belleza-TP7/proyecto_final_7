<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');

class promociones {
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
        prom.puntos,
        prom.activo 
        FROM promociones prom
        LEFT JOIN combos comb ON prom.id_combos = comb.id_combos
        LEFT JOIN servicios serv ON serv.id_servicios = prom.id_servicios";

        $resultado_traer_promociones = $this->conn->query($traer_promociones);

        return $resultado_traer_promociones;
    }

    public function traer_servicios(){
        $traer_servicios = "SELECT id_servicios, nombre AS nombre_servicio, descripcion, duracion, id_tiempo_servicio, precio_servicio, activo, id_tipo_servicio, imagen 
        FROM servicios WHERE activo = 1";

        $resultado_traer_servicios = $this->conn->query($traer_servicios);

        return $resultado_traer_servicios;
    }

    public function traer_combos(){
        $traer_combos = "SELECT id_combos, nombre AS nombre_combo, descripcion_combo, precio, imagen, activo, fecha_creacion FROM combos WHERE activo = 1";

        $resultado_traer_combos = $this->conn->query($traer_combos);

        return $resultado_traer_combos;
    }

    public function insertar_promo_combo($combo_select,$dias_promo,$descuento_promo,$cantidad_puntos){
        $insertar_promo_combo = $this->conn->prepare("INSERT INTO promociones(id_combos,dias_promocion, descuento, puntos) VALUES (?,?,?,?)");
        $insertar_promo_combo->bind_param('isii',$combo_select,$dias_promo,$descuento_promo,$cantidad_puntos);

        if($insertar_promo_combo->execute()){
            return true;

        }else{
            return false;
        }

    }

    public function insertar_promo_servicio($servicio_select,$dias_promo,$descuento_promo,$cantidad_puntos){
        $insertar_promo_servicio = $this->conn->prepare("INSERT INTO promociones(id_servicios,dias_promocion, descuento, puntos) VALUES (?,?,?,?)");
        $insertar_promo_servicio->bind_param('isii',$servicio_select,$dias_promo,$descuento_promo,$cantidad_puntos);

        if($insertar_promo_servicio->execute()){
            return true;

        }else{
            return false;
        }
    }

    public function obtener_datos_promocion($id_promocion){
        $buscar_promo = $this->conn->prepare("SELECT id_promocion, id_combos, id_servicios, dias_promocion, descuento, puntos FROM promociones WHERE id_promocion = ?");
        $buscar_promo->bind_param('i',$id_promocion);

        if($buscar_promo->execute()){
            $resultado_buscar_promo = $buscar_promo->get_result();

            $array_promo_encontrada = $resultado_buscar_promo->fetch_assoc();

            if($array_promo_encontrada['id_combos'] != null){
                $id_combo = $array_promo_encontrada['id_combos'];

                $buscar_combos = $this->conn->prepare("SELECT id_combos, nombre, descripcion_combo, precio, imagen, activo, fecha_creacion FROM combos WHERE id_combos = ?");
                $buscar_combos->bind_param('i',$id_combo);

                if($buscar_combos->execute()){
                    $resultado_combo_encontrado = $buscar_combos->get_result();
                    $tipo = 'combo';

                    return [
                        'datos_promo'=>$array_promo_encontrada,
                        'resultado'=> $resultado_combo_encontrado,
                        'tipo'=> $tipo
                    ];

                }

            }elseif($array_promo_encontrada['id_servicios'] != null){
                $id_servicio = $array_promo_encontrada['id_servicios'];

                $buscar_servicios = $this->conn->prepare("SELECT id_servicios, nombre, descripcion, duracion, id_tiempo_servicio, precio_servicio, activo, id_tipo_servicio, imagen FROM servicios WHERE id_servicios = ?");
                $buscar_servicios->bind_param('i',$id_servicio);

                if($buscar_servicios->execute()){
                    $resultado_servicio_encontrado = $buscar_servicios->get_result();
                    $tipo = 'servicio';

                    //se deculve un array de los datos
                    return [
                        'datos_promo'=>$array_promo_encontrada,
                        'resultado'=> $resultado_servicio_encontrado,
                        'tipo'=> $tipo
                    ];

                }

            }

        }

    }

    public function modificar_promocion($tipo,$id_combo,$id_servicio,$dia_promo,$descuento,$puntos,$id_promocion){
        if($tipo == 'combo'){
            $servicio_nulo = null;
            $updatear_promocion_combo = $this->conn->prepare("UPDATE promociones SET id_combos = ?,id_servicios = ?,dias_promocion = ?,descuento = ?,puntos = ? WHERE id_promocion = ?");
            $updatear_promocion_combo->bind_param('issiii',$id_combo,$servicio_nulo,$dia_promo,$descuento,$puntos,$id_promocion);

            if($updatear_promocion_combo->execute()){
                return true;

            }else{
                return false;
            }

        }elseif($tipo == 'servicio'){
            $combo_nulo = null;
            $updatear_promocion_servicio = $this->conn->prepare("UPDATE promociones SET id_combos = ?, id_servicios = ?,dias_promocion = ?,descuento = ?,puntos = ? WHERE id_promocion = ?");
            $updatear_promocion_servicio->bind_param('sisiii',$combo_nulo,$id_servicio,$dia_promo,$descuento,$puntos,$id_promocion);

            if($updatear_promocion_servicio->execute()){
                return true;

            }else{
                return false;

            }

        }

    }

    public function dar_baja_promo($id_promocion){
        $buscar_promo = $this->conn->prepare("SELECT id_promocion, id_combos, id_servicios, dias_promocion, descuento, puntos, activo FROM promociones WHERE id_promocion = ?");
        $buscar_promo->bind_param('i',$id_promocion);

        if($buscar_promo->execute()){
            $resultado_buscar_promo = $buscar_promo->get_result();

            $array_promos = $resultado_buscar_promo->fetch_assoc();

            if($array_promos['activo'] == 1){
                $inactivo = intval(0);
                $updatear_estado_promo = $this->conn->prepare("UPDATE promociones SET activo = ? WHERE id_promocion = ?");
                $updatear_estado_promo->bind_param('ii',$inactivo,$id_promocion);

                if($updatear_estado_promo->execute()){
                    return true;

                }else{
                    return false;
                }

            }elseif($array_promos['activo'] == 0){
                $activo = intval(1);
                $updatear_estado_promo = $this->conn->prepare("UPDATE promociones SET activo = ? WHERE id_promocion = ?");
                $updatear_estado_promo->bind_param('ii',$activo,$id_promocion);

                if($updatear_estado_promo->execute()){
                    return true;

                }else{
                    return false;
                }


            }else{
                echo "hubo un fallo trayendo el estado";
            }

        }

    }

    public function traer_detalle_promo($id_promocion){
        $buscar_promo = $this->conn->prepare("SELECT id_promocion, id_combos, id_servicios, dias_promocion, descuento, puntos, activo FROM promociones WHERE id_promocion = ?");
        $buscar_promo->bind_param('i',$id_promocion);

        if($buscar_promo->execute()){
            $resultado_promo = $buscar_promo->get_result();

            $array_resultado_promo = $resultado_promo->fetch_assoc();

            $id_combo = $array_resultado_promo['id_combos'];
            $id_servicio = $array_resultado_promo['id_servicios'];
            if($id_combo != null){
                $buscar_combo = $this->conn->prepare("SELECT id_combos, nombre, descripcion_combo, precio, imagen, activo, fecha_creacion FROM combos WHERE id_combos = ?");
                $buscar_combo->bind_param('i',$id_combo);

                if($buscar_combo->execute()){
                    $resultado_buscar_combo = $buscar_combo->get_result();

                    $tipo = 'combo';

                    return [
                        'tipo'=> $tipo,
                        'resultado'=>$resultado_buscar_combo
                    ];

                }else{
                    $error = "hubo un error en el modelo de traer los detalles del combo en promos";

                    return $error;
                }

            }elseif($id_servicio != null){
                $buscar_servicio = $this->conn->prepare("SELECT
                serv.id_servicios,
                serv.nombre,
                serv.descripcion,
                serv.duracion,
                tiemp_serv.tiempo_servicio,
                serv.precio_servicio,
                serv.activo,
                tip_serv.tipo_servicio,
                serv.imagen
                FROM servicios AS serv
                INNER JOIN tiempo_servicio AS tiemp_serv
                    ON serv.id_tiempo_servicio = tiemp_serv.id_tiempo_servicio
                INNER JOIN tipo_servicio AS tip_serv
                    ON tip_serv.id_tipo_servicio = serv.id_tipo_servicio
                WHERE serv.id_servicios = ?");

                $buscar_servicio->bind_param('i',$id_servicio);

                if($buscar_servicio->execute()){
                    $resultado_buscar_servicio = $buscar_servicio->get_result();
                    $tipo = 'servicio';
                    return [
                        'tipo'=>$tipo,
                        'resultado'=>$resultado_buscar_servicio
                    ];

                }else{
                    $error = "hubo un error en el modelo de traer los detalles del servicio en promos";
                    
                    return $error;
                }

            }else{
                $error = "hubo un error en el if de buscar detalle";
                return $error;
            }

        }

    }
}
?>