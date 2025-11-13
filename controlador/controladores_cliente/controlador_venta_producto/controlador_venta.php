<?php
require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');
require_once(ROOT_PATH . '/modelo/modelo_cliente/modelo_venta_productos/modelo_venta.php');

class ControladorVenta {
    private $modelo;

    public function __construct($modelo) {
        $this->modelo = $modelo;
    }

    public function mostrarInventario() {
        return $this->modelo->obtenerProductos();
    }

    public function mostrarCarrito($id_sesion) {
        return $this->modelo->obtenerCarrito($id_sesion);
    }

    public function mostrarMetodosPago() {
        return $this->modelo->obtenerMetodosPago();
    }
}
?>