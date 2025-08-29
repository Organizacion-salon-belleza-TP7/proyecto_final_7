<?php
require_once("modelo/InventarioModelo.php");

class InventarioControlador {
  private $modelo;

  public function __construct($conexion) {
    $this->modelo = new InventarioModelo($conexion);
  }

  public function mostrarInventario() {
    return $this->modelo->obtenerInventario();
  }
}
public function agregarProducto($datos) {
  return $this->modelo->agregarProducto($datos);
}

public function actualizarProducto($id, $datos) {
  return $this->modelo->actualizarProducto($id, $datos);
}

public function eliminarProducto($id) {
  return $this->modelo->eliminarProducto($id);
}

?>
