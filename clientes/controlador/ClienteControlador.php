<?php
require_once '../modelo/Cliente.php';
require_once '../modelo/Servicio.php';

class ClienteControlador {
    private $cliente;
    private $servicio;

    public function __construct() {
        $this->cliente = new Cliente();
        $this->servicio = new Servicio();
    }

    public function listar() {
        return $this->cliente->obtenerTodos();
    }

    public function ver($id) {
        $datos = [];
        $datos['cliente'] = $this->cliente->obtenerPorId($id);
        $datos['puntos'] = $this->cliente->obtenerPuntos($id);
        $datos['servicios'] = $this->servicio->obtenerPorCliente($id);
        return $datos;
    }
}
?>
