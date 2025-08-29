<?php
class InventarioModelo {
  private $conn;

  public function __construct($conexion) {
    $this->conn = $conexion;
  }

  public function obtenerInventario() {
    $sql = "SELECT id_inventario, nombre_producto, stock, vencimiento, precio_producto, precio_venta, imagen_producto, id_proveedor FROM inventario";
    $resultado = $this->conn->query($sql);

    $datos = [];
    while($fila = $resultado->fetch_assoc()) {
      $datos[] = $fila;
    }
    return $datos;
  }
}
// Agregar producto
public function agregarProducto($datos) {
  $stmt = $this->conn->prepare("INSERT INTO inventario (nombre_producto, stock, vencimiento, precio_producto, precio_venta, imagen_producto, id_proveedor) VALUES (?, ?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("sissdsi", $datos['nombre'], $datos['stock'], $datos['vencimiento'], $datos['precio'], $datos['venta'], $datos['imagen'], $datos['proveedor']);
  return $stmt->execute();
}

// Editar producto
public function actualizarProducto($id, $datos) {
  $stmt = $this->conn->prepare("UPDATE inventario SET nombre_producto=?, stock=?, vencimiento=?, precio_producto=?, precio_venta=?, imagen_producto=?, id_proveedor=? WHERE id_inventario=?");
  $stmt->bind_param("sissdsii", $datos['nombre'], $datos['stock'], $datos['vencimiento'], $datos['precio'], $datos['venta'], $datos['imagen'], $datos['proveedor'], $id);
  return $stmt->execute();
}

// Eliminar producto
public function eliminarProducto($id) {
  $stmt = $this->conn->prepare("DELETE FROM inventario WHERE id_inventario=?");
  $stmt->bind_param("i", $id);
  return $stmt->execute();
}

?>
