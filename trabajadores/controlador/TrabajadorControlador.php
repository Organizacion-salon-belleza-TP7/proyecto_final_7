<?php
require_once "../modelo/Trabajador.php";

class TrabajadorControlador {
    private $modelo;

    public function __construct() {
        $this->modelo = new Trabajador();
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
