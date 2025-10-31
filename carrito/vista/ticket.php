<?php
require_once "conexion.php";
require_once "fpdf/fpdf.php";

$id_caja = isset($_GET['id_caja']) ? (int)$_GET['id_caja'] : null;
if (!$id_caja) die("Falta el ID de la compra.");

$conn = Conexion::conectar();

// obtener venta
$stmt = $conn->prepare("SELECT * FROM caja_product WHERE id_caja_product = ?");
stmt_bind: // avoid linter false positive
$stmt->bind_param("i", $id_caja);
$stmt->execute();
$venta = $stmt->get_result()->fetch_assoc();
if (!$venta) die("Compra no encontrada.");

// obtener items desde detalle_caja_items
$stmt2 = $conn->prepare("SELECT id_inventario, nombre, cantidad, precio_unitario, subtotal FROM detalle_caja_items WHERE id_caja_product = ?");
$stmt2->bind_param("i", $id_caja);
$stmt2->execute();
$items = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);

// crear PDF
$pdf = new FPDF('P','mm','A4');
$pdf->AddPage();
$pdf->SetFont('Arial','B',14);
$pdf->Cell(0,10,"Ticket de Compra - Nro $id_caja",0,1,'C');
$pdf->Ln(2);

$pdf->SetFont('Arial','',11);
$pdf->Cell(0,6,"Fecha: " . $venta['fecha_venta'],0,1);
$pdf->Cell(0,6,"Total: $" . number_format($venta['monto_total'],2),0,1);
$pdf->Ln(6);

// tabla de items
$pdf->SetFont('Arial','B',11);
$pdf->Cell(90,7,'Producto',1,0);
$pdf->Cell(25,7,'Cant.',1,0,'C');
$pdf->Cell(35,7,'P.Unit',1,0,'R');
$pdf->Cell(40,7,'Subtotal',1,1,'R');

$pdf->SetFont('Arial','',11);
foreach ($items as $it) {
    $pdf->Cell(90,7,utf8_decode($it['nombre']),1,0);
    $pdf->Cell(25,7,$it['cantidad'],1,0,'C');
    $pdf->Cell(35,7,number_format($it['precio_unitario'],2),1,0,'R');
    $pdf->Cell(40,7,number_format($it['subtotal'],2),1,1,'R');
}

$pdf->Ln(6);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,7,'Total final: $' . number_format($venta['monto_total'],2),0,1,'R');

$pdf->Ln(8);
$pdf->SetFont('Arial','',10);
$pdf->Cell(0,6,'Gracias por su compra!',0,1,'C');

$pdf->Output("I","ticket_$id_caja.pdf");
exit;
?>
