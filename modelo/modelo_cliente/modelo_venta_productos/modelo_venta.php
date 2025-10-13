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
        $sql = "SELECT * FROM productos WHERE stock > 0";
        $result = $this->conn->query($sql);
        $productos = [];

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $productos[] = $row;
            }
        }

        return $productos;
    }

    public function agregarAlCarrito($id_sesion, $id_producto, $cantidad) {
        $id_producto = intval($id_producto);
        $id_sesion = $this->conn->real_escape_string($id_sesion);

        $sql = "SELECT precio, stock FROM productos WHERE id_producto = $id_producto";
        $producto = $this->conn->query($sql)->fetch_assoc();
        if (!$producto) return;

        if ($cantidad > $producto['stock']) $cantidad = $producto['stock'];
        $precio = $producto['precio'];
        $subtotal = $precio * $cantidad;

        $check = $this->conn->query("SELECT * FROM carrito WHERE id_sesion='$id_sesion' AND id_producto=$id_producto");

        if ($check && $check->num_rows > 0) {
            $this->conn->query("
                UPDATE carrito 
                SET cantidad = cantidad + $cantidad, subtotal = subtotal + $subtotal 
                WHERE id_sesion='$id_sesion' AND id_producto=$id_producto
            ");
        } else {
            $this->conn->query("
                INSERT INTO carrito (id_sesion, id_producto, cantidad, precio_unitario, subtotal)
                VALUES ('$id_sesion', $id_producto, $cantidad, $precio, $subtotal)
            ");
        }

        $this->conn->query("UPDATE productos SET stock = stock - $cantidad WHERE id_producto = $id_producto");
    }

    public function obtenerCarrito($id_sesion) {
        $id_sesion = $this->conn->real_escape_string($id_sesion);

        $sql = "SELECT c.*, p.nombre 
                FROM carrito c 
                INNER JOIN productos p ON c.id_producto = p.id_producto
                WHERE c.id_sesion = '$id_sesion'";
        $result = $this->conn->query($sql);
        $carrito = [];

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $carrito[] = $row;
            }
        }

        return $carrito;
    }

    public function obtenerMetodosPago() {
        $sql = "SELECT id_metodo_pago, metodo_pago, incremento, decremento, activo 
                FROM metodos_pagos 
                WHERE activo = 1";
        $result = $this->conn->query($sql);
        $metodos = [];

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $metodos[] = $row;
            }
        }

        return $metodos;
    }

    public function eliminarDelCarrito($id_sesion, $id_producto) {
        $id_producto = intval($id_producto);
        $id_sesion = $this->conn->real_escape_string($id_sesion);

        $sql = "SELECT cantidad FROM carrito WHERE id_sesion='$id_sesion' AND id_producto=$id_producto";
        $res = $this->conn->query($sql)->fetch_assoc();
        if ($res) {
            $cantidad = $res['cantidad'];
            $this->conn->query("UPDATE productos SET stock = stock + $cantidad WHERE id_producto=$id_producto");
            $this->conn->query("DELETE FROM carrito WHERE id_sesion='$id_sesion' AND id_producto=$id_producto");
        }
    }

    public function vaciarCarrito($id_sesion) {
        $id_sesion = $this->conn->real_escape_string($id_sesion);

        $sql = "SELECT id_producto, cantidad FROM carrito WHERE id_sesion='$id_sesion'";
        $result = $this->conn->query($sql);

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $this->conn->query("UPDATE productos SET stock = stock + {$row['cantidad']} WHERE id_producto={$row['id_producto']}");
            }
        }

        $this->conn->query("DELETE FROM carrito WHERE id_sesion='$id_sesion'");
    }

    public function finalizarCompra($id_sesion, $id_metodo_pago) {
        $id_metodo_pago = intval($id_metodo_pago);
        $id_sesion = $this->conn->real_escape_string($id_sesion);

        $items = $this->obtenerCarrito($id_sesion);
        if (empty($items)) return null;

        $total = 0;
        foreach ($items as $item) {
            $total += $item['subtotal'];
        }

        $metodo = $this->conn->query("SELECT * FROM metodos_pagos WHERE id_metodo_pago = $id_metodo_pago")->fetch_assoc();
        if (!$metodo) return null;

        if ($metodo['incremento']) $total += $total * $metodo['incremento'] / 100;
        if ($metodo['decremento']) $total -= $total * $metodo['decremento'] / 100;

        $this->conn->query("INSERT INTO caja_product (fecha_venta, monto_total) VALUES (NOW(), $total)");
        $id_caja = $this->conn->insert_id;

        foreach ($items as $item) {
            $hash = uniqid();
            $this->conn->query("
                INSERT INTO detalle_caja_product (id_caja_product, id_metodo_pago, id_producto, cantidad, subtotal, hash_identificacion)
                VALUES ($id_caja, $id_metodo_pago, {$item['id_producto']}, {$item['cantidad']}, {$item['subtotal']}, '$hash')
            ");
        }

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
