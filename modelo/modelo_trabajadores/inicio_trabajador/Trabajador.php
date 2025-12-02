<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');

class Trabajador {

    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Obtener trabajadores activos
    public function obtenerTodos() {
        $sql = "SELECT * FROM trabajadores WHERE activo = 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

  public function listaEspera() {

    $sql = "SELECT 
                le.id_lista_espera,
                le.tiempo_estimado,
                le.confirmacion,

                t.nombre_trabajador,
                t.apellido_trabajador,
                t.dni AS dni_trabajador,

                c.nombre AS nombre_cliente,
                c.apellido AS apellido_cliente,
                c.dni AS dni_cliente

            FROM lista_espera le

            INNER JOIN usuarios_personas up 
                    ON le.id_usuario_persona = up.id_usuarios_personas

            LEFT JOIN trabajadores t 
                    ON up.id_trabajador = t.id_trabajador

            LEFT JOIN clientes c 
                    ON up.id_cliente = c.id_cliente

            ORDER BY le.id_lista_espera DESC";

    // DEBUG SQL
    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("❌ ERROR SQL listaEspera(): " . $this->conn->error);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    return $result->fetch_all(MYSQLI_ASSOC);
}



    // Confirmar cita
    public function confirmar($id) {
        $sql = "UPDATE lista_espera SET confirmacion = 1 WHERE id_lista_espera = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    // Cancelar cita (borrar)
    public function cancelar($id) {
        $sql = "DELETE FROM lista_espera WHERE id_lista_espera = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
