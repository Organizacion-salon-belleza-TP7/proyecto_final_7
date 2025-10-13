<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');

class Trabajador {
    private $conn;

    // Constructor recibe la conexión PDO
    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Obtener todos los trabajadores activos
    public function obtenerTodos() {
        $stmt = $this->conn->query("SELECT * FROM trabajadores WHERE activo = 1");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lista de espera con join a clientes y trabajadores
    public function listaEspera() {
        $sql = "SELECT le.id_lista_espera,
                       le.tiempo_estimado,
                       le.confirmacion,
                       t.nombre_trabajador, t.apellido_trabajador, t.dni AS dni_trabajador,
                       c.nombre AS nombre_cliente, c.apellido AS apellido_cliente, c.dni AS dni_cliente
                FROM lista_espera le
                INNER JOIN usuarios_personas up ON le.id_usuario_persona = up.id_usuarios_personas
                LEFT JOIN trabajadores t ON up.id_trabajador = t.id_trabajador
                LEFT JOIN clientes c ON up.id_cliente = c.id_cliente";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Confirmar un registro de lista de espera
    public function confirmar($id) {
        $stmt = $this->conn->prepare("UPDATE lista_espera SET confirmacion = 1 WHERE id_lista_espera = ?");
        return $stmt->execute([$id]);
    }

    // Cancelar un registro de lista de espera
    public function cancelar($id) {
        $stmt = $this->conn->prepare("UPDATE lista_espera SET confirmacion = 0 WHERE id_lista_espera = ?");
        return $stmt->execute([$id]);
    }
}
