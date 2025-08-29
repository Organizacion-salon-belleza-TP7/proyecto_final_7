<?php
require_once("conexion.php");
require_once("controlador_producto/InventarioControlador.php");

$controlador = new InventarioControlador($conn);
$datos = $controlador->mostrarInventario();

include("vista_producto/inventarioVista.php");

$conn->close();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['accion'])) {
    $datos = [
      'nombre' => $_POST['nombre'],
      'stock' => $_POST['stock'],
      'vencimiento' => $_POST['vencimiento'],
      'precio' => $_POST['precio'],
      'venta' => $_POST['venta'],
      'imagen' => $_POST['imagen'],
      'proveedor' => $_POST['proveedor']
    ];

    if ($_POST['accion'] === 'agregar') {
      $controlador->agregarProducto($datos);
    } elseif ($_POST['accion'] === 'editar' && isset($_POST['id'])) {
      $controlador->actualizarProducto($_POST['id'], $datos);
    }
  }
}

if (isset($_GET['eliminar'])) {
  $controlador->eliminarProducto($_GET['eliminar']);
}

?>
