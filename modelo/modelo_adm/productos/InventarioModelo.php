<?php
require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');

class InventarioModelo {
    private $conn;

    public function __construct($conexion) {
        $this->conn = $conexion;
    }

    // === MOSTRAR TODO EL INVENTARIO (con JOIN a proveedor) ===
    public function mostrar_inventario() {
        $sql = "SELECT i.*, p.nombre_proveedor 
                FROM inventario i 
                LEFT JOIN proveedores p ON i.id_proveedor = p.id_proveedor 
                ORDER BY i.id_inventario DESC";
        return $this->conn->query($sql);
    }

    // === OBTENER PROVEEDORES PARA FILTRO ===
    public function obtener_proveedores() {
        $sql = "SELECT DISTINCT id_proveedor, nombre_proveedor 
                FROM proveedores 
                WHERE nombre_proveedor IS NOT NULL 
                ORDER BY nombre_proveedor";
        $result = $this->conn->query($sql);
        $proveedores = [];
        while ($row = $result->fetch_assoc()) {
            $proveedores[] = $row;
        }
        return $proveedores;
    }

    // === CONTAR PRODUCTOS CON FILTROS ===
    public function contar_productos_filtrados($busqueda = '', $proveedor = '') {
        $sql = "SELECT COUNT(*) as total FROM inventario i WHERE 1=1";
        $params = [];
        $types = '';

        if ($busqueda !== '') {
            $busqueda = "%" . $this->conn->real_escape_string($busqueda) . "%";
            $sql .= " AND (i.nombre_producto LIKE ? OR CAST(i.id_inventario AS CHAR) LIKE ? OR i.id_proveedor IN (SELECT id_proveedor FROM proveedores WHERE nombre_proveedor LIKE ?))";
            $params[] = $busqueda; $types .= 's';
            $params[] = $busqueda; $types .= 's';
            $params[] = $busqueda; $types .= 's';
        }

        if ($proveedor !== '') {
            $proveedor = (int)$proveedor;
            $sql .= " AND i.id_proveedor = ?";
            $params[] = $proveedor; $types .= 'i';
        }

        $stmt = $this->conn->prepare($sql);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()['total'];
    }

    // === BUSCAR CON FILTROS Y PAGINACIÓN ===
    public function buscar_inventario_paginado($busqueda = '', $proveedor = '', $limit = 10, $offset = 0) {
        $sql = "SELECT i.*, p.nombre_proveedor 
                FROM inventario i 
                LEFT JOIN proveedores p ON i.id_proveedor = p.id_proveedor 
                WHERE 1=1";
        $params = [];
        $types = '';

        if ($busqueda !== '') {
            $busqueda = "%" . $this->conn->real_escape_string($busqueda) . "%";
            $sql .= " AND (i.nombre_producto LIKE ? OR CAST(i.id_inventario AS CHAR) LIKE ? OR p.nombre_proveedor LIKE ?)";
            $params[] = $busqueda; $types .= 's';
            $params[] = $busqueda; $types .= 's';
            $params[] = $busqueda; $types .= 's';
        }

        if ($proveedor !== '') {
            $proveedor = (int)$proveedor;
            $sql .= " AND i.id_proveedor = ?";
            $params[] = $proveedor; $types .= 'i';
        }

        $sql .= " ORDER BY i.id_inventario DESC LIMIT ? OFFSET ?";
        $params[] = $limit; $types .= 'i';
        $params[] = $offset; $types .= 'i';

        $stmt = $this->conn->prepare($sql);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        return $stmt->get_result();
    }

    // === AGREGAR PRODUCTO ===
    public function agregarProducto($datos) {
        $stmt = $this->conn->prepare("INSERT INTO inventario (nombre_producto, stock, vencimiento, precio_producto, precio_venta, imagen_producto, id_proveedor) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sisdssi", 
            $datos['nombre'], 
            $datos['stock'], 
            $datos['vencimiento'], 
            $datos['precio'], 
            $datos['venta'], 
            $datos['imagen'], 
            $datos['proveedor']
        );
        return $stmt->execute();
    }

    // === ACTUALIZAR PRODUCTO ===
    public function actualizarProducto($id, $datos) {
        $stmt = $this->conn->prepare("UPDATE inventario SET nombre_producto=?, stock=?, vencimiento=?, precio_producto=?, precio_venta=?, imagen_producto=?, id_proveedor=? WHERE id_inventario=?");
        $stmt->bind_param("sisdssii", 
            $datos['nombre'], 
            $datos['stock'], 
            $datos['vencimiento'], 
            $datos['precio'], 
            $datos['venta'], 
            $datos['imagen'], 
            $datos['proveedor'], 
            $id
        );
        return $stmt->execute();
    }

    // === ELIMINAR PRODUCTO ===
    public function eliminarProducto($id) {
        $stmt = $this->conn->prepare("DELETE FROM inventario WHERE id_inventario = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
    public function detalle_producto($id) {
    $stmt = $this->conn->prepare("
        SELECT i.*, p.nombre_proveedor 
        FROM inventario i 
        LEFT JOIN proveedores p ON i.id_proveedor = p.id_proveedor 
        WHERE i.id_inventario = ?
    ");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result();
}

}
?>