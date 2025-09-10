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

    return 0; // fallback si falla
}


    public function insertar_caja($id_cita,$fecha_venta,$monto_total){
        $insertar_caja = $this->conn->prepare("INSERT INTO caja(fecha_venta, id_cita, monto_total) VALUES (?,?,?)");
        $insertar_caja->bind_param('sii',$fecha_venta,$id_cita,$monto_total);

        if($insertar_caja->execute()){
            $ultimo_id_caja = $this->conn->insert_id;

            return $ultimo_id_caja;


        }
        

    }

    public function insertar_varios_metodos_pagos($id_caja,$metodo_pago,$cantidad_pagar){
        $insertar_metodos_pagos = $this->conn->prepare("INSERT INTO multiple_pago(id_metodo_pago, id_caja, cantidad_pagar) VALUES (?,?,?)");
        $insertar_metodos_pagos->bind_param('iii',$metodo_pago,$id_caja,$cantidad_pagar);
        
        if($insertar_metodos_pagos->execute()){
            $ultimo_id_metodo_pago = $insertar_metodos_pagos->insert_id;

            $buscar_caja = $this->conn->prepare("SELECT mul_pag.id_multiple_pago, med_pag.incremento,med_pag.decremento, ca.id_caja, mul_pag.cantidad_pagar,ca.monto_total
            FROM multiple_pago mul_pag
            INNER JOIN metodos_pagos med_pag ON med_pag.id_metodo_pago = mul_pag.id_metodo_pago
            INNER JOIN caja ca ON mul_pag.id_caja = ca.id_caja
            WHERE mul_pag.id_caja = ?");

            $buscar_caja->bind_param('i',$id_caja);

            if($buscar_caja->execute()){
                $resultado = $buscar_caja->get_result();
                $array_resultado_caja = $resultado->fetch_assoc();
                if(!empty($array_resultado_caja['incremento'])){
                    $incremento = ($array_resultado_caja['monto_total'] * $array_resultado_caja['incremento']) / 100;

                    $precio_incrementado = $incremento + $array_resultado_caja['monto_total'];

                    $updatear_caja_incrementada = $this->conn->prepare("UPDATE `caja` SET monto_total = ? WHERE id_caja = ?");

                    $updatear_caja_incrementada->bind_param('ii',$precio_incrementado,$id_caja);

                    $updatear_caja_incrementada->execute();

                    return true;

                }elseif(!empty($array_resultado_caja['decremento'])){
                    $decremento = ($array_resultado_caja['monto_total'] * $array_resultado_caja['decremento']) / 100;

                    $precio_decrementado = $array_resultado_caja['monto_total'] - $decremento;

                    $updatear_caja_decrementada = $this->conn->prepare("UPDATE `caja` SET monto_total = ? WHERE id_caja = ?");

                    $updatear_caja_decrementada->bind_param('ii',$precio_decrementado,$id_caja);

                    $updatear_caja_decrementada->execute();

                    return true;


                }elseif(!empty($array_resultado_caja['decremento']) && !empty($array_resultado_caja['incremento'])){
                    $incremento = ($array_resultado_caja['monto_total'] * $array_resultado_caja['incremento']) / 100;
                    $decremento = ($array_resultado_caja['monto_total'] * $array_resultado_caja['decremento']) / 100;
                    
                    $precio_final = $array_resultado_caja['monto_total'] + ($incremento - $decremento);

                    $updatear_caja_ajustada = $this->conn->prepare("UPDATE `caja` SET monto_total = ? WHERE id_caja = ?");
                    $updatear_caja_ajustada->bind_param('ii',$precio_final,$id_caja);

                    return true;

                }

            }



            return true;
        }else{
            echo "hubo un fallo ejecutando la insercion de los medios de pago";
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


    }

    public function insertar_detalle_caja($id_caja,$id_servicio,$id_combo,$cantidad,$precio_unitario,$subtotal){
        $insertar_detalle_caja = $this->conn->prepare("INSERT INTO detalle_caja (id_caja, id_servicios, id_combos, cantidad, precio_unitario, subtotal)
        VALUES (?,?,?,?,?,?)");

        $insertar_detalle_caja->bind_param('iiiiii',$id_caja,$id_servicio,$id_combo,$cantidad,$precio_unitario,$subtotal);

        if($insertar_detalle_caja->execute()){
            return true;

        }else{
            return false;
        }
    }

    

}

?>