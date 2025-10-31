<?php
require_once __DIR__ . '/../conexion.php';

class ModeloVenta {

    public static function obtenerProductos() {
        $conn = Conexion::conectar();
        $sql = "SELECT * FROM productos WHERE stock > 0";
        $result = $conn->query($sql);
        $productos = [];
        while ($row = $result->fetch_assoc()) {
            $productos[] = $row;
        }
        return $productos;
    }

    public static function agregarAlCarrito($id_sesion, $id_producto, $cantidad) {
        $conn = Conexion::conectar();

        $sql = "SELECT precio, stock FROM productos WHERE id_producto = $id_producto";
        $producto = $conn->query($sql)->fetch_assoc();
        if (!$producto) return;

        if ($cantidad > $producto['stock']) $cantidad = $producto['stock'];
        $precio = $producto['precio'];
        $subtotal = $precio * $cantidad;

        // Ver si ya existe en el carrito
        $check = $conn->query("SELECT * FROM carrito WHERE id_sesion='$id_sesion' AND id_producto=$id_producto");
        if ($check->num_rows > 0) {
            $conn->query("UPDATE carrito SET cantidad = cantidad + $cantidad, subtotal = subtotal + $subtotal 
                          WHERE id_sesion='$id_sesion' AND id_producto=$id_producto");
        } else {
            $conn->query("INSERT INTO carrito (id_sesion, id_producto, cantidad, precio_unitario, subtotal)
                          VALUES ('$id_sesion', $id_producto, $cantidad, $precio, $subtotal)");
        }

        $conn->query("UPDATE productos SET stock = stock - $cantidad WHERE id_producto = $id_producto");
    }

    public static function obtenerCarrito($id_sesion) {
        $conn = Conexion::conectar();
        $sql = "SELECT c.*, p.nombre 
                FROM carrito c 
                INNER JOIN productos p ON c.id_producto = p.id_producto
                WHERE c.id_sesion = '$id_sesion'";
        $result = $conn->query($sql);
        $carrito = [];
        while ($row = $result->fetch_assoc()) {
            $carrito[] = $row;
        }
        return $carrito;
    }

    public static function obtenerMetodosPago() {
        $conn = Conexion::conectar();
        $result = $conn->query("SELECT id_metodo_pago, metodo_pago, incremento, decremento, activo FROM metodos_pagos WHERE activo = 1");
        $metodos = [];
        while ($row = $result->fetch_assoc()) {
            $metodos[] = $row;
        }
        return $metodos;
    }

    public static function eliminarDelCarrito($id_sesion, $id_producto) {
        $conn = Conexion::conectar();
        $sql = "SELECT cantidad FROM carrito WHERE id_sesion='$id_sesion' AND id_producto=$id_producto";
        $res = $conn->query($sql)->fetch_assoc();
        if ($res) {
            $cantidad = $res['cantidad'];
            $conn->query("UPDATE productos SET stock = stock + $cantidad WHERE id_producto=$id_producto");
            $conn->query("DELETE FROM carrito WHERE id_sesion='$id_sesion' AND id_producto=$id_producto");
        }
    }

    public static function vaciarCarrito($id_sesion) {
        $conn = Conexion::conectar();
        $sql = "SELECT id_producto, cantidad FROM carrito WHERE id_sesion='$id_sesion'";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            $conn->query("UPDATE productos SET stock = stock + {$row['cantidad']} WHERE id_producto={$row['id_producto']}");
        }
        $conn->query("DELETE FROM carrito WHERE id_sesion='$id_sesion'");
    }

    public static function finalizarCompra($id_sesion, $id_metodo_pago) {
        $conn = Conexion::conectar();
        $items = self::obtenerCarrito($id_sesion);
        if (empty($items)) return null;

        $total = 0;
        foreach ($items as $item) {
            $total += $item['subtotal'];
        }

        $metodo = $conn->query("SELECT * FROM metodos_pagos WHERE id_metodo_pago = $id_metodo_pago")->fetch_assoc();
        if ($metodo['incremento']) $total += $total * $metodo['incremento']/100;
        if ($metodo['decremento']) $total -= $total * $metodo['decremento']/100;

        $conn->query("INSERT INTO caja_product (fecha_venta, monto_total) VALUES (NOW(), $total)");
        $id_caja = $conn->insert_id;

        foreach ($items as $item) {
            $hash = uniqid();
            $conn->query("INSERT INTO detalle_caja_product (id_caja_product, id_multiple_pago, hash_identificacion)
                          VALUES ($id_caja, $id_metodo_pago, '$hash')");
        }

        self::vaciarCarrito($id_sesion);
        return ['id_caja' => $id_caja, 'total' => $total, 'metodo' => $metodo, 'items' => $items];
    }
}
?>
