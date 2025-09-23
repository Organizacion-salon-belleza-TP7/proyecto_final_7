<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../config/db.php');

class Inventario {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Mostrar todo el inventario
    public function mostrar_inventario() {
        $sql = "SELECT i.id_inventario, i.nombre_producto, i.stock, i.vencimiento, 
                       i.precio_producto, i.precio_venta, i.imagen_producto, 
                       p.nombre_proveedor 
                FROM inventario i
                INNER JOIN proveedores p ON p.id_proveedor = i.id_proveedor";

        $resultado = $this->conn->query($sql);
        return $resultado;
    }

    // Detalle de producto
    public function detalle_producto($id_inventario) {
        $stmt = $this->conn->prepare("SELECT i.*, p.nombre_proveedor 
                                      FROM inventario i
                                      INNER JOIN proveedores p ON p.id_proveedor = i.id_proveedor
                                      WHERE i.id_inventario = ?");
        $stmt->bind_param("i", $id_inventario);
        $stmt->execute();
        return $stmt->get_result();
    }

    // Formulario agregar producto
    public function formulario_agregar_producto() {
        $proveedores = $this->conn->query("SELECT id_proveedor, nombre_proveedor FROM proveedores");
        return $proveedores;
    }

    // Agregar nuevo producto
    public function agregar_producto($nombre, $stock, $vencimiento, $precio_compra, $precio_venta, $imagen, $proveedor) {
        $stmt = $this->conn->prepare("INSERT INTO inventario(nombre_producto, stock, vencimiento, precio_producto, precio_venta, imagen_producto, id_proveedor) 
                                      VALUES (?,?,?,?,?,?,?)");
        $stmt->bind_param("sissdsi", $nombre, $stock, $vencimiento, $precio_compra, $precio_venta, $imagen, $proveedor);

        if ($stmt->execute()) {
            return $this->conn->insert_id;
        } else {
            echo '<script>
                    alert("Hubo un fallo al agregar el producto");
                    self.location = "' . BASE_URL . '/vista/vista_adm/inventario/vista_inventario.php"
                  </script>';
            exit;
        }
    }

    // Formulario modificar producto
    public function formulario_modificar_producto($id_inventario) {
        $stmt = $this->conn->prepare("SELECT * FROM inventario WHERE id_inventario = ?");
        $stmt->bind_param("i", $id_inventario);
        $stmt->execute();
        $producto = $stmt->get_result()->fetch_assoc();

        $proveedores = $this->conn->query("SELECT id_proveedor, nombre_proveedor FROM proveedores");

        return [
            'producto' => $producto,
            'proveedores' => $proveedores
        ];
    }

    // Modificar producto
    public function modificar_producto($id, $nombre, $stock, $vencimiento, $precio_compra, $precio_venta, $imagen, $proveedor) {
        $stmt = $this->conn->prepare("UPDATE inventario 
                                      SET nombre_producto=?, stock=?, vencimiento=?, precio_producto=?, precio_venta=?, imagen_producto=?, id_proveedor=? 
                                      WHERE id_inventario=?");
        $stmt->bind_param("sissdsii", $nombre, $stock, $vencimiento, $precio_compra, $precio_venta, $imagen, $proveedor, $id);
        return $stmt->execute();
    }

    // Eliminar producto físicamente
    public function eliminar_producto($id_inventario) {
        $stmt = $this->conn->prepare("DELETE FROM inventario WHERE id_inventario = ?");
        $stmt->bind_param("i", $id_inventario);
        return $stmt->execute();
}


    // Buscar producto por nombre
    public function buscar_producto($termino) {
        $stmt = $this->conn->prepare("SELECT * FROM inventario WHERE nombre_producto LIKE ?");
        $busqueda = "%$termino%";
        $stmt->bind_param("s", $busqueda);
        $stmt->execute();
        return $stmt->get_result();
    }
}
