<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');
require_once(ROOT_PATH . '/modelo/modelo_cliente/modelo_venta_productos/modelo_venta.php');
date_default_timezone_set('America/Argentina/Buenos_Aires');

session_start();

class ControladorVenta {

    private $modeloVenta;

    public function __construct($conn) {
        $this->modeloVenta = new ModeloVenta($conn);
    }

    private function iniciarSesion() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function mostrarProductos() {
        return $this->modeloVenta->obtenerProductos();
    }

    public function agregarAlCarrito($id_producto, $cantidad) {
        $this->iniciarSesion();
        $id_sesion = session_id();
        $this->modeloVenta->agregarAlCarrito($id_sesion, $id_producto, $cantidad);
    }

    public function mostrarCarrito() {
        $this->iniciarSesion();
        $id_sesion = session_id();
        return $this->modeloVenta->obtenerCarrito($id_sesion);
    }

    public function mostrarMetodosPago() {
        return $this->modeloVenta->obtenerMetodosPago();
    }

    public function eliminarDelCarrito($id_producto) {
        $this->iniciarSesion();
        $id_sesion = session_id();
        $this->modeloVenta->eliminarDelCarrito($id_sesion, $id_producto);
    }

    public function vaciarCarrito() {
        $this->iniciarSesion();
        $id_sesion = session_id();
        $this->modeloVenta->vaciarCarrito($id_sesion);
    }

    public function confirmarCompra($id_metodo_pago) {
        $this->iniciarSesion();
        $id_sesion = session_id();
        return $this->modeloVenta->finalizarCompra($id_sesion, $id_metodo_pago);
    }
}
?>
