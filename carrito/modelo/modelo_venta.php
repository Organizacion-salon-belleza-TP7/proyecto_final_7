<?php
require_once __DIR__ . '/../conexion.php';

class ModeloVenta {

    /** 🔹 Obtiene todos los productos disponibles con stock */
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

    /** 🔹 Agrega productos al carrito y descuenta del stock */
    public static function agregarAlCarrito($id_sesion, $id_producto, $cantidad) {
        $conn = Conexion::conectar();

        // Validar producto existente
        $sql = "SELECT precio, stock FROM productos WHERE id_producto = $id_producto";
        $producto = $conn->query($sql)->fetch_assoc();
        if (!$producto) return;

        // Limitar cantidad a stock disponible
        if ($cantidad > $producto['stock']) $cantidad = $producto['stock'];

        $precio = $producto['precio'];
        $subtotal = $precio * $cantidad;

        // Verificar si ya existe el producto en el carrito
        $check = $conn->query("SELECT * FROM carrito WHERE id_sesion='$id_sesion' AND id_producto=$id_producto");
        if ($check->num_rows > 0) {
            $conn->query("
                UPDATE carrito 
                SET cantidad = cantidad + $cantidad, subtotal = subtotal + $subtotal 
                WHERE id_sesion='$id_sesion' AND id_producto=$id_producto
            ");
        } else {
            $conn->query("
                INSERT INTO carrito (id_sesion, id_producto, cantidad, precio_unitario, subtotal)
                VALUES ('$id_sesion', $id_producto, $cantidad, $precio, $subtotal)
            ");
        }

        // Actualizar stock del producto
        $conn->query("UPDATE productos SET stock = stock - $cantidad WHERE id_producto = $id_producto");
    }

    /** 🔹 Obtiene todos los productos del carrito actual */
    public static function obtenerCarrito($id_sesion) {
        $conn = Conexion::conectar();
        $sql = "
            SELECT c.*, p.nombre 
            FROM carrito c 
            INNER JOIN productos p ON c.id_producto = p.id_producto
            WHERE c.id_sesion = '$id_sesion'
        ";
        $result = $conn->query($sql);
        $carrito = [];
        while ($row = $result->fetch_assoc()) {
            $carrito[] = $row;
        }
        return $carrito;
    }

    /** 🔹 Obtiene los métodos de pago activos */
    public static function obtenerMetodosPago() {
        $conn = Conexion::conectar();
        $result = $conn->query("
            SELECT id_metodo_pago, metodo_pago, incremento, decremento, activo 
            FROM metodos_pagos 
            WHERE activo = 1
        ");
        $metodos = [];
        while ($row = $result->fetch_assoc()) {
            $metodos[] = $row;
        }
        return $metodos;
    }

    /** 🔹 Elimina un producto del carrito y restaura el stock */
    public static function eliminarDelCarrito($id_sesion, $id_producto) {
        $conn = Conexion::conectar();

        $sql = "SELECT cantidad FROM carrito WHERE id_sesion='$id_sesion' AND id_producto=$id_producto";
        $res = $conn->query($sql)->fetch_assoc();

        if ($res) {
            $cantidad = $res['cantidad'];
            // Restaurar stock
            $conn->query("UPDATE productos SET stock = stock + $cantidad WHERE id_producto=$id_producto");
            // Eliminar del carrito
            $conn->query("DELETE FROM carrito WHERE id_sesion='$id_sesion' AND id_producto=$id_producto");
        }
    }

    /** 🔹 Vacía completamente el carrito (restaurando stock) */
    public static function vaciarCarrito($id_sesion) {
        $conn = Conexion::conectar();
        $sql = "SELECT id_producto, cantidad FROM carrito WHERE id_sesion='$id_sesion'";
        $result = $conn->query($sql);

        while ($row = $result->fetch_assoc()) {
            $conn->query("
                UPDATE productos 
                SET stock = stock + {$row['cantidad']} 
                WHERE id_producto={$row['id_producto']}
            ");
        }

        $conn->query("DELETE FROM carrito WHERE id_sesion='$id_sesion'");
    }

    /** 🔹 Finaliza la compra y la registra en la caja */
    public static function finalizarCompra($id_sesion, $id_metodo_pago) {
        $conn = Conexion::conectar();
        $items = self::obtenerCarrito($id_sesion);
        if (empty($items)) return null;

        $total = 0;
        foreach ($items as $item) {
            $total += $item['subtotal'];
        }

        // Aplicar descuentos o recargos según método de pago
        $metodo = $conn->query("SELECT * FROM metodos_pagos WHERE id_metodo_pago = $id_metodo_pago")->fetch_assoc();
        if ($metodo['incremento']) $total += $total * $metodo['incremento'] / 100;
        if ($metodo['decremento']) $total -= $total * $metodo['decremento'] / 100;

        // Registrar en caja
        $conn->query("INSERT INTO caja_product (fecha_venta, monto_total) VALUES (NOW(), $total)");
        $id_caja = $conn->insert_id;

        // Registrar detalle de pago
        foreach ($items as $item) {
            $hash = uniqid('compra_');
            $conn->query("
                INSERT INTO detalle_caja_product (id_caja_product, id_multiple_pago, hash_identificacion)
                VALUES ($id_caja, $id_metodo_pago, '$hash')
            ");
        }

        // Vaciar carrito luego de completar compra
        self::vaciarCarrito($id_sesion);

        return [
            'id_caja' => $id_caja,
            'total' => $total,
            'metodo' => $metodo,
            'items' => $items
        ];
    }
}
?>
