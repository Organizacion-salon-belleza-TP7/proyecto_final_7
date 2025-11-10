<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');

class ModeloVenta {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function obtenerProductos() {
        $sql = "SELECT * FROM inventario WHERE stock > 0";
        $result = $this->conn->query($sql);

        if (!$result) {
            throw new Exception("Error al obtener productos: " . $this->conn->error);
        }

        $productos = [];
        while ($row = $result->fetch_assoc()) {
            $productos[] = $row;
        }

        return $productos;
    }

    public function agregarAlCarrito($id_sesion, $id_producto, $cantidad) {
        // Obtener producto
        $stmt = $this->conn->prepare("SELECT precio, stock FROM inventario WHERE id_inventario = ?");
        $stmt->bind_param('i', $id_producto);
        $stmt->execute();
        $result = $stmt->get_result();
        $producto = $result->fetch_assoc();
        $stmt->close();

        if (!$producto) return false;

        if ($cantidad > $producto['stock']) $cantidad = $producto['stock'];
        $precio = $producto['precio'];
        $subtotal = $precio * $cantidad;

        // Verificar si ya existe en el carrito
        $stmt = $this->conn->prepare("SELECT * FROM carrito WHERE id_sesion = ? AND id_inventario = ?");
        $stmt->bind_param('si', $id_sesion, $id_producto);
        $stmt->execute();
        $check = $stmt->get_result();
        $stmt->close();

        if ($check->num_rows > 0) {
            $stmt = $this->conn->prepare("UPDATE carrito SET cantidad = cantidad + ?, subtotal = subtotal + ? 
                                          WHERE id_sesion = ? AND id_inventario = ?");
            $stmt->bind_param('iisi', $cantidad, $subtotal, $id_sesion, $id_producto);
        } else {
            $stmt = $this->conn->prepare("INSERT INTO carrito (id_sesion, id_inventario, cantidad, precio_unitario, subtotal)
                                          VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param('siiid', $id_sesion, $id_producto, $cantidad, $precio, $subtotal);
        }
        
        $success = $stmt->execute();
        if (!$success) {
            throw new Exception("Error al agregar al carrito: " . $stmt->error);
        }
        $stmt->close();

        // Actualizar stock
        $stmt = $this->conn->prepare("UPDATE inventario SET stock = stock - ? WHERE id_inventario = ?");
        $stmt->bind_param('ii', $cantidad, $id_producto);
        $success = $stmt->execute();
        if (!$success) {
            throw new Exception("Error al actualizar stock: " . $stmt->error);
        }
        $stmt->close();

        return true;
    }

    public function obtenerCarrito($id_sesion) {
        $stmt = $this->conn->prepare("
            SELECT c.*, i.nombre 
            FROM carrito c 
            INNER JOIN inventario i ON c.id_inventario = i.id_inventario
            WHERE c.id_sesion = ?");
        $stmt->bind_param('s', $id_sesion);
        $stmt->execute();
        $result = $stmt->get_result();

        $carrito = [];
        while ($row = $result->fetch_assoc()) {
            $carrito[] = $row;
        }
        $stmt->close();

        return $carrito;
    }

    public function obtenerMetodosPago() {
        $sql = "SELECT id_metodo_pago, metodo_pago, incremento, decremento, activo 
                FROM metodos_pagos WHERE activo = 1";
        $result = $this->conn->query($sql);

        if (!$result) {
            throw new Exception("Error al obtener métodos de pago: " . $this->conn->error);
        }

        $metodos = [];
        while ($row = $result->fetch_assoc()) {
            $metodos[] = $row;
        }

        return $metodos;
    }

    public function eliminarDelCarrito($id_sesion, $id_producto) {
        // Recuperar cantidad antes de eliminar
        $stmt = $this->conn->prepare("SELECT cantidad FROM carrito WHERE id_sesion = ? AND id_inventario = ?");
        $stmt->bind_param('si', $id_sesion, $id_producto);
        $stmt->execute();
        $result = $stmt->get_result();
        $res = $result->fetch_assoc();
        $stmt->close();

        if ($res) {
            $cantidad = $res['cantidad'];

            // Devolver stock
            $stmt = $this->conn->prepare("UPDATE inventario SET stock = stock + ? WHERE id_inventario = ?");
            $stmt->bind_param('ii', $cantidad, $id_producto);
            $success = $stmt->execute();
            if (!$success) {
                throw new Exception("Error al devolver stock: " . $stmt->error);
            }
            $stmt->close();

            // Eliminar del carrito
            $stmt = $this->conn->prepare("DELETE FROM carrito WHERE id_sesion = ? AND id_inventario = ?");
            $stmt->bind_param('si', $id_sesion, $id_producto);
            $success = $stmt->execute();
            if (!$success) {
                throw new Exception("Error al eliminar del carrito: " . $stmt->error);
            }
            $stmt->close();
        }
    }

    public function vaciarCarrito($id_sesion) {
        // Recuperar productos
        $stmt = $this->conn->prepare("SELECT id_inventario, cantidad FROM carrito WHERE id_sesion = ?");
        $stmt->bind_param('s', $id_sesion);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $stmt2 = $this->conn->prepare("UPDATE inventario SET stock = stock + ? WHERE id_inventario = ?");
            $stmt2->bind_param('ii', $row['cantidad'], $row['id_inventario']);
            $success = $stmt2->execute();
            if (!$success) {
                throw new Exception("Error al devolver stock: " . $stmt2->error);
            }
            $stmt2->close();
        }

        $stmt->close();

        // Vaciar carrito
        $stmt = $this->conn->prepare("DELETE FROM carrito WHERE id_sesion = ?");
        $stmt->bind_param('s', $id_sesion);
        $success = $stmt->execute();
        if (!$success) {
            throw new Exception("Error al vaciar carrito: " . $stmt->error);
        }
        $stmt->close();
    }

    public function finalizarCompra($id_sesion, $id_metodo_pago) {
        $items = $this->obtenerCarrito($id_sesion);
        if (empty($items)) return null;

        $total = 0;
        foreach ($items as $item) {
            $total += $item['subtotal'];
        }

        $stmt = $this->conn->prepare("SELECT * FROM metodos_pagos WHERE id_metodo_pago = ?");
        $stmt->bind_param('i', $id_metodo_pago);
        $stmt->execute();
        $result = $stmt->get_result();
        $metodo = $result->fetch_assoc();
        $stmt->close();

        if (!$metodo) {
            throw new Exception("Método de pago no encontrado");
        }

        if ($metodo['incremento']) $total += $total * $metodo['incremento'] / 100;
        if ($metodo['decremento']) $total -= $total * $metodo['decremento'] / 100;

        // Insertar venta
        $stmt = $this->conn->prepare("INSERT INTO caja_product (fecha_venta, monto_total) VALUES (NOW(), ?)");
        $stmt->bind_param('d', $total);
        $success = $stmt->execute();
        if (!$success) {
            throw new Exception("Error al insertar venta: " . $stmt->error);
        }
        $id_caja = $stmt->insert_id;
        $stmt->close();

        // Detalle de venta - Insertar cada producto del carrito
        $stmt = $this->conn->prepare("INSERT INTO detalle_caja_product (id_caja_product, id_inventario, cantidad, precio_unitario, subtotal, id_multiple_pago) VALUES (?, ?, ?, ?, ?, ?)");
        
        foreach ($items as $item) {
            $stmt->bind_param('iiiidi', $id_caja, $item['id_inventario'], $item['cantidad'], $item['precio_unitario'], $item['subtotal'], $id_metodo_pago);
            $success = $stmt->execute();
            if (!$success) {
                throw new Exception("Error al insertar detalle de venta: " . $stmt->error);
            }
        }
        $stmt->close();

        // Vaciar carrito
        $this->vaciarCarrito($id_sesion);

        return [
            'id_caja' => $id_caja,
            'total' => $total,
            'metodo' => $metodo,
            'items' => $items
        ];
    }
}
?>