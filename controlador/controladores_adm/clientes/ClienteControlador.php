<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');

require_once(ROOT_PATH . '/modelo/modelo_adm/clientes/Cliente.php');
require_once(ROOT_PATH . '/modelo/modelo_adm/clientes/Servicio.php');


class ClienteControlador {
    private $cliente;
    private $servicio;

    public function __construct($conn) {
        $this->cliente = new Cliente($conn);
        $this->servicio = new Servicio($conn);
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
