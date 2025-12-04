<?php

class ModeloDashboard {

    private $conn;

    public function __construct($c) {
        $this->conn = $c;
    }

    /* =====================================================
        VENTAS POR DÍA (SUMA DE MONTO TOTAL)
    ===================================================== */
    public function ventasPorDia() {
        $sql = "SELECT fecha_venta, SUM(monto_total) AS total
                FROM caja
                GROUP BY fecha_venta
                ORDER BY fecha_venta ASC";

        return $this->conn->query($sql);
    }

    /* =====================================================
        TOP SERVICIOS MÁS VENDIDOS
    ===================================================== */
    public function topServicios() {
        $sql = "SELECT 
                    s.nombre AS nombre,
                    SUM(dc.cantidad) AS total
                FROM detalle_caja dc
                LEFT JOIN servicios s ON s.id_servicios = dc.id_servicios
                WHERE dc.id_servicios IS NOT NULL
                GROUP BY dc.id_servicios
                ORDER BY total DESC
                LIMIT 10";

        return $this->conn->query($sql);
    }

    /* =====================================================
        MÉTODOS DE PAGO
    ===================================================== */
    public function metodosPago() {
        $sql = "SELECT 
                    id_metodo_pago AS metodo,
                    SUM(subtotal) AS total
                FROM detalle_caja
                GROUP BY id_metodo_pago";

        return $this->conn->query($sql);
    }

    /* =====================================================
        VENTAS DE PRODUCTOS
    ===================================================== */
    public function ventasProductos() {
        $sql = "SELECT 
                    id_caja_product AS id,
                    monto_total
                FROM caja_product
                ORDER BY id_caja_product ASC";

        return $this->conn->query($sql);
    }

    /* =====================================================
        VENTAS DE PROMOCIONES
    ===================================================== */
    public function ventasPromos() {
        $sql = "SELECT 
                    id_caja_promociones AS id,
                    monto_total
                FROM caja_promociones
                ORDER BY id_caja_promociones ASC";

        return $this->conn->query($sql);
    }

    /* =====================================================
        TOTAL GENERAL
    ===================================================== */
    public function totalGeneral() {
        $sql = "SELECT 
                    (SELECT SUM(monto_total) FROM caja) +
                    (SELECT SUM(monto_total) FROM caja_product) +
                    (SELECT SUM(monto_total) FROM caja_promociones) AS total";

        return $this->conn->query($sql);
    }

    /* =====================================================
        RESUMEN GLOBAL PARA RADAR
    ===================================================== */
    public function resumenGlobal() {
        $sql = "
            SELECT 'Servicios' AS tipo, SUM(monto_total) AS total FROM caja
            UNION ALL
            SELECT 'Productos', SUM(monto_total) FROM caja_product
            UNION ALL
            SELECT 'Promociones', SUM(monto_total) FROM caja_promociones
        ";

        return $this->conn->query($sql);
    }
}
?>
