<?php
require_once 'conexion.php';

class ControladorVenta {

    public static function mostrarInventario() {
        $pdo = Conexion::conectar();
        $sql = "SELECT id_inventario, nombre_producto, precio_venta, stock FROM inventario WHERE stock > 0 ORDER BY nombre_producto";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function mostrarCarrito() {
        $pdo = Conexion::conectar();
        $sesion = session_id();
        $sql = "SELECT c.*, i.nombre_producto, i.precio_venta 
                FROM carrito c 
                JOIN inventario i ON c.id_inventario = i.id_inventario 
                WHERE c.id_sesion = ? 
                ORDER BY c.Id_carrito";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$sesion]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function mostrarMetodosPago() {
        $pdo = Conexion::conectar();
        $sql = "SELECT * FROM metodos_pagos WHERE activo = 1";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function agregarAlCarrito($id_inventario, $cantidad) {
        $pdo = Conexion::conectar();
        $sesion = session_id();

        // Validar stock
        $sql = "SELECT stock, precio_venta, nombre_producto FROM inventario WHERE id_inventario = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id_inventario]);
        $item = $stmt->fetch();

        if (!$item || $item['stock'] < $cantidad) return false;

        $precio = $item['precio_venta'];
        $subtotal = $precio * $cantidad;

        // Ver si ya existe
        $sql = "SELECT * FROM carrito WHERE id_sesion = ? AND id_inventario = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$sesion, $id_inventario]);
        if ($stmt->rowCount() > 0) {
            $sql = "UPDATE carrito SET cantidad = cantidad + ?, subtotal = subtotal + ? WHERE id_sesion = ? AND id_inventario = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$cantidad, $subtotal, $sesion, $id_inventario]);
        } else {
            $sql = "INSERT INTO carrito (id_sesion, id_inventario, cantidad, precio_unitario, subtotal) VALUES (?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$sesion, $id_inventario, $cantidad, $precio, $subtotal]);
        }
        return true;
    }

    public static function eliminarDelCarrito($id_inventario) {
        $pdo = Conexion::conectar();
        $sesion = session_id();
        $sql = "DELETE FROM carrito WHERE id_sesion = ? AND id_inventario = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$sesion, $id_inventario]);
    }

    public static function vaciarCarrito() {
        $pdo = Conexion::conectar();
        $sesion = session_id();
        $sql = "DELETE FROM carrito WHERE id_sesion = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$sesion]);
    }

    public static function confirmarCompra($id_metodo_pago) {
        $pdo = Conexion::conectar();
        $sesion = session_id();

        try {
            $pdo->beginTransaction();

            // Obtener carrito
            $carrito = self::mostrarCarrito();
            if (empty($carrito)) return false;

            $total = 0;
            foreach ($carrito as $c) {
                $total += $c['subtotal'];
            }

            // Aplicar descuento/recargo
            $metodo = $pdo->query("SELECT * FROM metodos_pagos WHERE id_metodo_pago = $id_metodo_pago")->fetch();
            if ($metodo['decremento'] > 0) $total -= $total * $metodo['decremento'] / 100;
            if ($metodo['incremento'] > 0) $total += $total * $metodo['incremento'] / 100;

            // Registrar venta en caja_product
            $sql = "INSERT INTO caja_product (fecha_venta, monto_total) VALUES (NOW(), ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$total]);
            $id_caja_product = $pdo->lastInsertId();

            // Registrar cada item
            foreach ($carrito as $c) {
                $sql = "INSERT INTO detalle_caja_product (id_caja_product, id_multiple_pago) 
                        VALUES (?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$id_caja_product, $id_metodo_pago]);

                // Restar stock
                $sql = "UPDATE inventario SET stock = stock - ? WHERE id_inventario = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$c['cantidad'], $c['id_inventario']]);
            }

            // Limpiar carrito
            self::vaciarCarrito();

            $pdo->commit();

            return [
                'id_caja_product' => $id_caja_product,
                'total' => $total,
                'metodo' => $metodo,
                'items' => $carrito
            ];
        } catch (Exception $e) {
            $pdo->rollBack();
            error_log("Error en compra: " . $e->getMessage());
            return false;
        }
    }
}