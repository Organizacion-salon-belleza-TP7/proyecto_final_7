<?php
include_once("conexion.php"); // más seguro

class InventarioModelo {
    private $conexion;

    public function __construct() {
        $this->conexion = Conexion::conectar();
    }

    public function listar() {
        $result = $this->conexion->query("SELECT i.*, p.nombre_proveedor
            FROM inventario i
            JOIN proveedores p ON i.id_proveedor = p.id_proveedor");

        return $result->fetch_all(MYSQLI_ASSOC); // ← esta línea es clave para que el inventario funcione
    }

    public function agregar($nombre, $stock, $vencimiento, $precio_compra, $precio_venta, $imagen, $proveedor) {
        $stmt = $this->conexion->prepare("INSERT INTO inventario(nombre_producto, stock, vencimiento, precio_producto, precio_venta, imagen_producto, id_proveedor) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sissdsi", $nombre, $stock, $vencimiento, $precio_compra, $precio_venta, $imagen, $proveedor);
        return $stmt->execute();
    }

    public function obtener($id) {
        return $this->conexion->query("SELECT * FROM inventario WHERE id_inventario = $id")->fetch_assoc();
    }

    public function modificar($id, $nombre, $stock, $vencimiento, $precio_compra, $precio_venta, $imagen, $proveedor) {
        $stmt = $this->conexion->prepare("UPDATE inventario SET nombre_producto=?, stock=?, vencimiento=?, precio_producto=?, precio_venta=?, imagen_producto=?, id_proveedor=? WHERE id_inventario=?");
        $stmt->bind_param("sissdsii", $nombre, $stock, $vencimiento, $precio_compra, $precio_venta, $imagen, $proveedor, $id);
        return $stmt->execute();
    }

    public function eliminar($id) {
        return $this->conexion->query("DELETE FROM inventario WHERE id_inventario = $id");
    }

    public function buscar($termino) {
        $stmt = $this->conexion->prepare("SELECT * FROM inventario WHERE nombre_producto LIKE ?");
        $busqueda = "%$termino%";
        $stmt->bind_param("s", $busqueda);
        $stmt->execute();
        return $stmt->get_result();
    }
}
