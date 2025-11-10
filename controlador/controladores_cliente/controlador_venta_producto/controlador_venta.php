<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php'); // ✅ conexión $conn
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

    public function agregarAlCarrito($id_sesion, $id_producto, $cantidad) {
        return $this->modelo->agregarAlCarrito($id_sesion, $id_producto, $cantidad);
    }

    public function eliminarDelCarrito($id_sesion, $id_producto) {
        return $this->modelo->eliminarDelCarrito($id_sesion, $id_producto);
    }

    public function vaciarCarrito($id_sesion) {
        return $this->modelo->vaciarCarrito($id_sesion);
    }

    public function finalizarCompra($id_sesion, $id_metodo_pago) {
        return $this->modelo->finalizarCompra($id_sesion, $id_metodo_pago);
    }
}

// ✅ Manejo de acciones POST desde los formularios
session_start();
$id_sesion = session_id();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';

    $modelo = new ModeloVenta($conn);
    $controlador = new ControladorVenta($modelo);

    switch ($accion) {
        case 'agregar':
            $id_producto = intval($_POST['id_producto']);
            $cantidad = intval($_POST['cantidad']);
            $controlador->agregarAlCarrito($id_sesion, $id_producto, $cantidad);
            break;

        case 'eliminar':
            $id_producto = intval($_POST['id_producto']);
            $controlador->eliminarDelCarrito($id_sesion, $id_producto);
            break;

        case 'finalizar':
            $id_metodo_pago = intval($_POST['id_metodo_pago']);
            $resultado = $controlador->finalizarCompra($id_sesion, $id_metodo_pago);
            if ($resultado) {
                echo "<script>alert('Compra finalizada correctamente. Total: $" . number_format($resultado['total'], 2) . "');</script>";
            } else {
                echo "<script>alert('Error al finalizar la compra.');</script>";
            }
            break;
    }

    header("Location: " . BASE_URL . "/vista/vista_cliente/vista_venta_productos/vista_venta.php");
    exit;
}
?>
