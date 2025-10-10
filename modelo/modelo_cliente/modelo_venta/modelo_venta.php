<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');

class venta{
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function traer_medios_pagos(){
        $traer_medios_pagos = $this->conn->query("SELECT id_metodo_pago, metodo_pago, incremento, decremento, activo FROM metodos_pagos WHERE activo = 1");
        return $traer_medios_pagos;
    }

    public function traer_datos_cita($id_cita){
        $traer_cita = $this->conn->prepare("SELECT 
            dt.id_detalle, 
            dt.id_cita, 
            serv.id_servicios, 
            serv.precio_servicio AS precio_servicio,
            comb.id_combos, 
            comb.precio AS precio_combo
        FROM detalle_cita dt
        LEFT JOIN servicios serv ON dt.id_servicios = serv.id_servicios
        LEFT JOIN combos comb ON dt.id_combos = comb.id_combos
        WHERE dt.id_cita = ?");
        
        $traer_cita->bind_param('i',$id_cita);

        if($traer_cita->execute()){
            $resultado_traer_cita = $traer_cita->get_result();

            $total = 0;

            while($row = $resultado_traer_cita->fetch_assoc()){
                if(!empty($row['precio_servicio'])){
                    $total += $row['precio_servicio'];
                }

                if(!empty($row['precio_combo'])){
                    $total += $row['precio_combo'];
                }
            }

            return $total;
        }

        return 0;
    }

    public function insertar_caja($id_cita, $fecha_venta, $monto, $monto_total){
    $insertar_caja = $this->conn->prepare("INSERT INTO caja(fecha_venta, id_cita, monto, monto_total) VALUES (?,?,?,?)");
    $insertar_caja->bind_param('siii', $fecha_venta, $id_cita, $monto, $monto_total);

    if($insertar_caja->execute()){
        return $this->conn->insert_id;
    }
    return false;
}


    public function insertar_metodo_pago($id_caja, $metodo_pago, $cantidad_pagar){
        $insertar_metodo_pago = $this->conn->prepare("INSERT INTO multiple_pago(id_metodo_pago, id_caja, cantidad_pagar) VALUES (?,?,?)");
        $insertar_metodo_pago->bind_param('iii', $metodo_pago, $id_caja, $cantidad_pagar);
        
        if($insertar_metodo_pago->execute()){
            // Aplicar incrementos/decrementos según el método de pago
            $buscar_metodo = $this->conn->prepare("SELECT incremento, decremento FROM metodos_pagos WHERE id_metodo_pago = ?");
            $buscar_metodo->bind_param('i', $metodo_pago);
            
            if($buscar_metodo->execute()){
                $resultado = $buscar_metodo->get_result();
                $metodo_info = $resultado->fetch_assoc();
                
                if(!empty($metodo_info['incremento']) || !empty($metodo_info['decremento'])){require_once(ROOT_PATH . '/modelo/modelo_cliente/modelo_venta/modelo_venta.php');
                    $buscar_caja = $this->conn->prepare("SELECT monto_total FROM caja WHERE id_caja = ?");
                    $buscar_caja->bind_param('i', $id_caja);
                    
                    if($buscar_caja->execute()){
                        $resultado_caja = $buscar_caja->get_result();require_once(ROOT_PATH . '/modelo/modelo_cliente/modelo_venta/modelo_venta.php');
                        $caja_info = $resultado_caja->fetch_assoc();
                        
                        $nuevo_monto = $caja_info['monto_total'];
                        
                        // Aplicar incremento
                        if(!empty($metodo_info['incremento'])){
                            $incremento = ($caja_info['monto_total'] * $metodo_info['incremento']) / 100;
                            $nuevo_monto += $incremento;
                        }
                        
                        // Aplicar decremento
                        if(!empty($metodo_info['decremento'])){
                            $decremento = ($caja_info['monto_total'] * $metodo_info['decremento']) / 100;
                            $nuevo_monto -= $decremento;
                        }
                        
                        // Actualizar caja con el monto ajustado
                        $actualizar_caja = $this->conn->prepare("UPDATE caja SET monto_total = ? WHERE id_caja = ?");
                        $actualizar_caja->bind_param('ii', $nuevo_monto, $id_caja);
                        $actualizar_caja->execute();
                    }
                }
            }
            return true;
        } else {
            error_log("Hubo un fallo ejecutando la inserción del medio de pago: " . $insertar_metodo_pago->error);
            return false;
        }
    }

    public function traer_datos_detalle_cita($id_cita){
        $traer_detalle_cita = $this->conn->prepare("SELECT 
            dc.id_detalle,
            dc.id_servicios,
            dc.id_combos,
            dc.id_cita,
            s.precio_servicio AS precio_servicio,
            c.precio AS precio_combo
        FROM detalle_cita dc
        LEFT JOIN servicios s ON dc.id_servicios = s.id_servicios
        LEFT JOIN combos c ON dc.id_combos = c.id_combos
        WHERE dc.id_cita = ?");

        $traer_detalle_cita->bind_param('i',$id_cita);

        if($traer_detalle_cita->execute()){
            $resultado_traer_cita = $traer_detalle_cita->get_result();
            $detalle = [];

            while($row = $resultado_traer_cita->fetch_assoc()){
                $detalle[] = $row;
            }

            return $detalle;
        }
        return [];
    }

    public function insertar_detalle_caja($id_caja, $id_servicio, $id_combo, $cantidad, $precio_unitario, $subtotal, $id_medio_pago){
        $insertar_detalle_caja = $this->conn->prepare("INSERT INTO detalle_caja (id_caja, id_servicios, id_combos, cantidad, precio_unitario, subtotal, id_metodo_pago) 
        VALUES (?,?,?,?,?,?,?)");

        $insertar_detalle_caja->bind_param('iiiiiii', $id_caja, $id_servicio, $id_combo, $cantidad, $precio_unitario, $subtotal, $id_medio_pago);

        if($insertar_detalle_caja->execute()){
            return true;
        } else {
            error_log("Error al insertar detalle caja: " . $insertar_detalle_caja->error);
            return false;
        }
    }

    public function insertar_historial_venta($id_caja,$id_usuario){
        $insertar_historial_venta = $this->conn->prepare("INSERT INTO historial_compra(id_caja, id_usuario, calificacion_servicio) VALUES (?,?,null)");
        $insertar_historial_venta->bind_param('ii',$id_caja,$id_usuario);

        if($insertar_historial_venta->execute()){
            return true;

        }


        
        
    }
}
?>