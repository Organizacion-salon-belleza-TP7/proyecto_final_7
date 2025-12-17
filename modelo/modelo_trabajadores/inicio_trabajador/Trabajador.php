<?php
class ListaEspera {

    private $conn;

    public function __construct($conn){
        $this->conn = $conn;
    }

    // 👉 INSERTAR RESERVA DEL CLIENTE
    public function agregar($id_usuario_persona, $id_servicio, $id_tipo_servicio, $tiempo_estimado){

        $sql = "INSERT INTO lista_espera 
                (id_usuario_persona, id_servicio, id_tipo_servicio, tiempo_estimado, confirmacion)
                VALUES (?, ?, ?, ?, 0)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iiis", $id_usuario_persona, $id_servicio, $id_tipo_servicio, $tiempo_estimado);
        return $stmt->execute();
    }


    public function obtenerListaEspera() {

        $sql = "SELECT 
                    le.id_lista_espera,
                    le.tiempo_estimado,
                    le.confirmacion,

                    c.nombre AS nombre_cliente,
                    c.apellido AS apellido_cliente,

                    t.nombre_trabajador,
                    t.apellido_trabajador

                FROM lista_espera le

                LEFT JOIN usuarios_personas up 
                    ON up.id_usuarios_personas = le.id_usuario_persona

                LEFT JOIN clientes c 
                    ON c.id_cliente = up.id_cliente

                LEFT JOIN trabajadores t 
                    ON t.id_trabajador = up.id_trabajador

                ORDER BY le.id_lista_espera DESC";

        $result = $this->conn->query($sql);
        $datos = [];

        while ($row = $result->fetch_assoc()) {
            $datos[] = $row;
        }

        return $datos;
    }



    // 👉 CONFIRMAR TURNO
    public function confirmar($id){
        $sql = "UPDATE lista_espera SET confirmacion = 1 WHERE id_lista_espera = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    // 👉 CANCELAR
  public function cancelar($id) {
    $sql = "DELETE FROM lista_espera WHERE id_lista_espera = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

}
