<?php
require_once "conexion.php";
require_once "fpdf/fpdf.php";

$id_caja = $_GET['id_caja'] ?? null;
if (!$id_caja) die("Falta el ID de la compra.");

$conn = Conexion::conectar();
$venta = $conn->query("SELECT * FROM caja_product WHERE id_caja_product = $id_caja")->fetch_assoc();
$detalle = $conn->query("
    SELECT d.id_multiple_pago, p.nombre, dc.hash_identificacion, dc.id_caja_product
    FROM detalle_caja_product dc
    INNER JOIN carrito c ON 1
    INNER JOIN productos p ON p.id_producto = 1
")->fetch_all(MYSQLI_ASSOC); // Opcional: si quieres mostrar productos

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Ticket de Compra', 0, 1, 'C');
$pdf->Ln(10);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, 'Fecha: ' . $venta['fecha_venta'], 0, 1);
$pdf->Cell(0, 10, 'Total pagado: $' . number_format($venta['monto_total'], 2), 0, 1);
$pdf->Ln(10);
$pdf->Cell(0, 10, 'Gracias por su compra!', 0, 1, 'C');
$pdf->Output();
?>
