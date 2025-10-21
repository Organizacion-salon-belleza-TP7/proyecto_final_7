<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../config/db.php');

class modelo_inicio{
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function traer_citas_compradas($id_usuario){
        $traer_citas = $this->conn->prepare("SELECT
        cit.id_cita,
        cli.nombre,
        usr.nombre_usuario,
        cit.fecha_cita,
        cit.activo,
        lug.nombre_lugar,
        caj.id_caja,
        hist_comp.id_historial_compra,
        hist_comp.id_usuario
        FROM citas cit
        INNER JOIN caja caj ON caj.id_cita = cit.id_cita
        INNER JOIN historial_compra hist_comp ON hist_comp.id_caja = caj.id_caja
        INNER JOIN lugares lug ON lug.id_lugar = cit.id_lugar

        LEFT JOIN usuarios usr ON hist_comp.id_usuario = usr.id_usuario 
        LEFT JOIN clientes cli ON usr.id_usuario = cli.id_usuario 

        WHERE hist_comp.id_usuario = ?");

        $traer_citas->bind_param('i',$id_usuario);

        if($traer_citas->execute()){
            $resultado_traer_citas = $traer_citas->get_result();
            return $resultado_traer_citas;

        }else{
            return false;
        }
    }

    public function reembolsar_cita($id_caja,$id_usuario,$id_cita){
        $buscar_precio_caja = $this->conn->prepare("SELECT id_caja, fecha_venta, id_cita, monto, monto_total FROM caja WHERE id_caja = ?");
        $buscar_precio_caja->bind_param('i',$id_caja);

        if($buscar_precio_caja->execute()){
            $resultado_traer_precio_caja = $buscar_precio_caja->get_result();

            $array_precio_caja = $resultado_traer_precio_caja->fetch_assoc();

            $monto_total = $array_precio_caja['monto_total'];

            $porcentaje_reembolso = 0.70;

            $importe_reebolso = $porcentaje_reembolso * 100;

            $monto_reembolso = $monto_total * $porcentaje_reembolso;

            $reembolso = $monto_total - $monto_reembolso;

            $insertar_reembolso = $this->conn->prepare("INSERT INTO reembolsos(id_caja, id_usuario, cantidad_reembolsada, importe_reembolso) 
            VALUES (?,?,?,?)");

            $insertar_reembolso->bind_param('iiii',$id_caja,$id_usuario,$reembolso,$importe_reebolso);

            if($insertar_reembolso->execute()){
                $traer_citas = $this->conn->prepare("SELECT
                cit.id_cita,
                cli.nombre,
                usr.nombre_usuario,
                cit.fecha_cita,
                cit.activo,
                lug.nombre_lugar,
                caj.id_caja,
                hist_comp.id_historial_compra,
                hist_comp.id_usuario
                FROM citas cit
                INNER JOIN caja caj ON caj.id_cita = cit.id_cita
                INNER JOIN historial_compra hist_comp ON hist_comp.id_caja = caj.id_caja
                INNER JOIN lugares lug ON lug.id_lugar = cit.id_lugar

                LEFT JOIN usuarios usr ON hist_comp.id_usuario = usr.id_usuario 
                LEFT JOIN clientes cli ON usr.id_usuario = cli.id_usuario 

                WHERE cit.id_cita = ?");

                $traer_citas->bind_param('i',$id_cita);

                if($traer_citas->execute()){
                    $resultado_traer_citas = $traer_citas->get_result();
                    
                    $array_resultado_traer_citas = $resultado_traer_citas->fetch_assoc();

                    $id_cita = $array_resultado_traer_citas['id_cita'];

                    $updatear_estado_cita = $this->conn->prepare("UPDATE citas SET activo = 0 WHERE id_cita = ?");

                    $updatear_estado_cita->bind_param('i',$id_cita);

                    if($updatear_estado_cita->execute()){
                        return [
                            'precio_total' => $monto_total,
                            'porcentaje_reembolsado' => $importe_reebolso,
                            'reembolso' => $reembolso
                        ];
                    }




                }else{
                    return false;
                }

            }

        }
    }
}
?>