<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class modelo_venta{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function mostrar_medios_pago(){
        $traer_medios_pagos = "SELECT id_metodo_pago, metodo_pago, incremento, decremento, activo FROM metodos_pagos";
        $resultado_traer_medios_pagos = $this->conn->query($traer_medios_pagos);


        return $resultado_traer_medios_pagos;
    }

    public function dar_baja_medio_pago($id_medio_pago){
        $buscar_medio_pago = $this->conn->prepare("SELECT id_metodo_pago, metodo_pago, incremento, decremento, activo FROM metodos_pagos WHERE id_metodo_pago = ?");
        $buscar_medio_pago->bind_param('i',$id_medio_pago);

        if($buscar_medio_pago->execute()){
            $traer_medio_pago = $buscar_medio_pago->get_result();
            $traer_estado_medio_pago = $traer_medio_pago->fetch_assoc();

            if($traer_estado_medio_pago['activo'] === 1){
                $updatear_estado = $this->conn->prepare("UPDATE `metodos_pagos` SET activo = 0 WHERE id_metodo_pago = ?");
                $updatear_estado->bind_param('i',$id_medio_pago);
                $updatear_estado->execute();

                return true;
            }elseif($traer_estado_medio_pago['activo'] === 0){
                $updatear_estado = $this->conn->prepare("UPDATE `metodos_pagos` SET activo = 1 WHERE id_metodo_pago = ?");
                $updatear_estado->bind_param('i',$id_medio_pago);
                $updatear_estado->execute();

                return true;

            }else{
                echo '<script>
                alert("hubo un fallo en al dar de baja el medio de pago");
                window.location.href = "' . BASE_URL . '/vista/vista_login/vista_login.php";
                </script>';
                exit;

                return false;

            }


        }

    }

    public function agregar_metodo_pago($nombre_metodo_pago,$importe,$cantidad_importe,$activo){
        if($importe == 1){
            $insertar_metodo_pago = $this->conn->prepare("INSERT INTO `metodos_pagos`(metodo_pago, incremento, decremento, activo) VALUES (?,?,null,?)");
            $insertar_metodo_pago->bind_param('sii',$nombre_metodo_pago,$cantidad_importe,$activo);
            if($insertar_metodo_pago->execute()){
                return true;

            }else{
                return false;
            }
        }elseif($importe == 0){
            $insertar_metodo_pago = $this->conn->prepare("INSERT INTO `metodos_pagos`(metodo_pago, incremento, decremento, activo) VALUES (?,null,?,?)");
            $insertar_metodo_pago->bind_param('sii',$nombre_metodo_pago,$cantidad_importe,$activo);
            if($insertar_metodo_pago->execute()){
                return true;

            }else{
                return false;
            }

        }

    }

public function formulario_modificar_medios_pagos($id_medio_pago){
    $buscar_medio_pago = $this->conn->prepare("SELECT id_metodo_pago, metodo_pago, incremento, decremento, activo FROM metodos_pagos WHERE id_metodo_pago = ?");
    $buscar_medio_pago->bind_param('i',$id_medio_pago);

    if($buscar_medio_pago->execute()){
        $resultado_traer_medios_pagos = $buscar_medio_pago->get_result();
        return $resultado_traer_medios_pagos;
    } else {
        return false;
    }
}

    public function modificar_medios_pagos($id_medio_pago,$nombre_metodo_pago,$importe,$cantidad_importe){
        if($importe == 1){
            $updatear_medios_pagos = $this->conn->prepare("UPDATE metodos_pagos SET metodo_pago = ?,incremento = ?,decremento = null WHERE id_metodo_pago = ?");
            $updatear_medios_pagos->bind_param('sii',$nombre_metodo_pago,$cantidad_importe,$id_medio_pago);

            if($updatear_medios_pagos->execute()){
                return true;

            }else{
                return false;
            }

        }elseif($importe == 0){
            $updatear_medios_pagos = $this->conn->prepare("UPDATE metodos_pagos SET metodo_pago = ?,incremento = null,decremento = ? WHERE id_metodo_pago = ?");
            $updatear_medios_pagos->bind_param('sii',$nombre_metodo_pago,$cantidad_importe,$id_medio_pago);

            if($updatear_medios_pagos->execute()){
                return true;

            }else{
                return false;
            }


        }else{
            echo "hubo un fallo en el modelo";
        }

    }



    
}

?>