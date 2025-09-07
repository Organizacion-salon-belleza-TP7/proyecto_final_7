<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/modelo_adm/trabajadores/Trabajador.php');

class TrabajadorControlador {
    private $modelo;

    public function __construct($conn) { // <-- Recibir $conn como parámetro
        $this->modelo = new Trabajador($conn);
    }

    public function listar() {
        return $this->modelo->obtenerTodos();
    }

    public function ver($id) {
        return $this->modelo->obtenerPorId($id);
    }

    public function guardar($datos, $id = null) {
        if ($id) {
            return $this->modelo->actualizar($id, $datos);
        } else {
            return $this->modelo->agregar($datos);
        }
    }

    public function borrar($id) {
        return $this->modelo->eliminar($id);
    }
}
?>