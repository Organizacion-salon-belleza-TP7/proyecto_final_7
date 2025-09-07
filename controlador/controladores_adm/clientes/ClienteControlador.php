<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/modelo_adm/clientes/Cliente.php');

class ClienteControlador {
    private $cliente;

    public function __construct($conn) {
        $this->cliente = new Cliente($conn);
    }

    public function listar() {
        return $this->cliente->obtenerTodos();
    }

    public function detalle($id) {
        return $this->cliente->obtenerDetalle($id);
    }
}
?>
