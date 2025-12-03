<?php

class ModeloHistorialVentas {

    private $conn;

    public function __construct($c) {
        $this->conn = $c;
    }

    /* =======================
       VENTAS PRINCIPALES
       ======================= */
    public function obtenerVentas($limite = 500) {
        $sql = "SELECT 
                    id_caja,
                    fecha_venta,
                    monto,
                    monto_total
                FROM caja
                ORDER BY id_caja DESC
                LIMIT $limite";
        return $this->conn->query($sql);
    }

    /* =======================
       DETALLE DE VENTA
       ======================= */
    public function obtenerDetalleVenta($id) {
        $sql = "SELECT 
                    dc.cantidad,
                    dc.precio_unitario,
                    dc.subtotal,
                    dc.id_metodo_pago,
                    s.nombre AS servicio_nombre,
                    co.nombre AS combo_nombre
                FROM detalle_caja dc
                LEFT JOIN servicios s ON s.id_servicios = dc.id_servicios
                LEFT JOIN combos co ON co.id_combos = dc.id_combos
                WHERE dc.id_caja = $id";

        return $this->conn->query($sql);
    }

    /* =======================
       TOP ITEMS
       ======================= */
    public function topItemsVendidos($limite = 8) {
        $sql = "SELECT 
                    s.nombre AS nombre,
                    SUM(dc.cantidad) AS total_cantidad
                FROM detalle_caja dc
                LEFT JOIN servicios s ON s.id_servicios = dc.id_servicios
                WHERE dc.id_servicios IS NOT NULL
                GROUP BY dc.id_servicios
                ORDER BY total_cantidad DESC
                LIMIT $limite";
        return $this->conn->query($sql);
    }

    /* =======================
       MÉTODOS DE PAGO
       ======================= */
    public function mediosPagoEstadisticas() {
        $sql = "SELECT 
                    id_metodo_pago AS metodo,
                    SUM(subtotal) AS monto
                FROM detalle_caja
                GROUP BY id_metodo_pago";

        $res = $this->conn->query($sql);
        $arr = [];
        while ($r = $res->fetch_assoc()) {
            $arr[] = $r;
        }
        return $arr;
    }

    /* =======================
       CAJA PRODUCT
       ======================= */
    public function ventasProductos() {
        $sql = "SELECT 
                    id_caja_product AS id,
                    fecha_venta,
                    monto_total
                FROM caja_product
                ORDER BY id_caja_product DESC";
        $res = $this->conn->query($sql);
        $arr = [];
        while ($r = $res->fetch_assoc()) $arr[] = $r;
        return $arr;
    }

    /* =======================
       CAJA PROMOCIONES
       ======================= */
    public function ventasPromociones() {
        $sql = "SELECT 
                    id_caja_promociones AS id,
                    fecha_venta,
                    monto_total
                FROM caja_promociones
                ORDER BY id_caja_promociones DESC";
        $res = $this->conn->query($sql);
        $arr = [];
        while ($r = $res->fetch_assoc()) $arr[] = $r;
        return $arr;
    }
}
?>
