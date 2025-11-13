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
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    }

    public function obtenerProductos() {
        $sql = "SELECT * FROM inventario WHERE stock > 0 ORDER BY nombre_producto";
        $result = $this->conn->query($sql);
        $productos = [];
        while ($row = $result->fetch_assoc()) {
            $productos[] = $row;
        }
        return $productos;
    }

    public function agregarAlCarrito($id_sesion, $id_producto, $cantidad) {
        $stmt = $this->conn->prepare("SELECT precio_venta AS precio, stock FROM inventario WHERE id_inventario = ?");
        $stmt->bind_param('i', $id_producto);
        $stmt->execute();
        $result = $stmt->get_result();
        $producto = $result->fetch_assoc();
        $stmt->close();

        if (!$producto || $cantidad > $producto['stock']) {
            $cantidad = $producto['stock'] ?? 0;
        }
        if ($cantidad <= 0) return false;

        $precio = $producto['precio'];
        $subtotal = $precio * $cantidad;

        $stmt = $this->conn->prepare("SELECT id_carrito, cantidad, subtotal FROM carrito WHERE id_sesion = ? AND id_inventario = ?");
        $stmt->bind_param('si', $id_sesion, $id_producto);
        $stmt->execute();
        $check = $stmt->get_result();
        $stmt->close();

        if ($check->num_rows > 0) {
            $existente = $check->fetch_assoc();
            $nueva_cantidad = $existente['cantidad'] + $cantidad;
            $nuevo_subtotal = $existente['subtotal'] + $subtotal;

            $stmt = $this->conn->prepare("UPDATE carrito SET cantidad = ?, subtotal = ?, precio_unitario = ? WHERE id_sesion = ? AND id_inventario = ?");
            $stmt->bind_param('iddsi', $nueva_cantidad, $nuevo_subtotal, $precio, $id_sesion, $id_producto);
        } else {
            $stmt = $this->conn->prepare("INSERT INTO carrito (id_sesion, id_inventario, cantidad, precio_unitario, subtotal) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param('siidd', $id_sesion, $id_producto, $cantidad, $precio, $subtotal);
        }
        $stmt->execute();
        $stmt->close();

        $stmt = $this->conn->prepare("UPDATE inventario SET stock = stock - ? WHERE id_inventario = ?");
        $stmt->bind_param('ii', $cantidad, $id_producto);
        $stmt->execute();
        $stmt->close();

        return true;
    }

    public function obtenerCarrito($id_sesion) {
        $sql = "SELECT c.*, i.nombre_producto, i.precio_venta FROM carrito c 
                INNER JOIN inventario i ON c.id_inventario = i.id_inventario 
                WHERE c.id_sesion = ? ORDER BY c.Id_carrito DESC";
        $stmt = $this->conn->prepare($sql);
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
        $sql = "SELECT * FROM metodos_pagos WHERE activo = 1";
        $result = $this->conn->query($sql);
        $metodos = [];
        while ($row = $result->fetch_assoc()) {
            $metodos[] = $row;
        }
        return $metodos;
    }

    public function eliminarDelCarrito($id_sesion, $id_producto) {
        $stmt = $this->conn->prepare("SELECT cantidad FROM carrito WHERE id_sesion = ? AND id_inventario = ?");
        $stmt->bind_param('si', $id_sesion, $id_producto);
        $stmt->execute();
        $result = $stmt->get_result();
        $res = $result->fetch_assoc();
        $stmt->close();

        if ($res) {
            $cantidad = $res['cantidad'];
            $stmt = $this->conn->prepare("UPDATE inventario SET stock = stock + ? WHERE id_inventario = ?");
            $stmt->bind_param('ii', $cantidad, $id_producto);
            $stmt->execute();
            $stmt->close();

            $stmt = $this->conn->prepare("DELETE FROM carrito WHERE id_sesion = ? AND id_inventario = ?");
            $stmt->bind_param('si', $id_sesion, $id_producto);
            $stmt->execute();
            $stmt->close();
        }
    }

    public function vaciarCarrito($id_sesion) {
        $stmt = $this->conn->prepare("SELECT id_inventario, cantidad FROM carrito WHERE id_sesion = ?");
        $stmt->bind_param('s', $id_sesion);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $stmt2 = $this->conn->prepare("UPDATE inventario SET stock = stock + ? WHERE id_inventario = ?");
            $stmt2->bind_param('ii', $row['cantidad'], $row['id_inventario']);
            $stmt2->execute();
            $stmt2->close();
        }
        $stmt->close();

        $stmt = $this->conn->prepare("DELETE FROM carrito WHERE id_sesion = ?");
        $stmt->bind_param('s', $id_sesion);
        $stmt->execute();
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
        $metodo = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        $totalFinal = $total;
        if ($metodo['incremento'] > 0) $totalFinal += $totalFinal * $metodo['incremento'] / 100;
        if ($metodo['decremento'] > 0) $totalFinal -= $totalFinal * $metodo['decremento'] / 100;

        $stmt = $this->conn->prepare("INSERT INTO caja_product (fecha_venta, monto_total) VALUES (NOW(), ?)");
        $stmt->bind_param('d', $totalFinal);
        $stmt->execute();
        $id_caja = $stmt->insert_id;
        $stmt->close();

        $stmt = $this->conn->prepare("INSERT INTO detalle_caja_product 
            (id_caja_product, id_producto, cantidad, precio_unitario, subtotal, id_multiple_pago) 
            VALUES (?, ?, ?, ?, ?, ?)");

        foreach ($items as $item) {
            $stmt->bind_param('iiiddi',
                $id_caja,
                $item['id_inventario'],
                $item['cantidad'],
                $item['precio_unitario'],
                $item['subtotal'],
                $id_metodo_pago
            );
            $stmt->execute();
        }
        $stmt->close();

        $this->vaciarCarrito($id_sesion);

        return [
            'id_caja' => $id_caja,
            'total' => $totalFinal,
            'metodo' => $metodo,
            'items' => $items
        ];
    }
}
?>