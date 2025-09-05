<?php
require_once '../modelo/Cliente.php';

class ClienteControlador {
    private $cliente;

    public function __construct() {
        $this->cliente = new Cliente();
    }

    public function listar() {
        return $this->cliente->obtenerTodos();
    }

    public function detalle($id) {
        return $this->cliente->obtenerDetalle($id);
    }
}
?>
