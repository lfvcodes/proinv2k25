<?php
if (!isset($_GET['rc']) && empty($_GET['rc'])) {
	header("Location: ../../e404.html");
} else {

	require_once(__DIR__ . "/../../libraries/php/fpdf/fpdf.php");
	require_once(__DIR__ . "/../../connections/db.php");
	require_once __DIR__ . '/../../utils/php/utils.php';

	$colorTheme = HexToRgb("#007bff");
	$pdf = new FPDF('P', 'mm', 'letter');
	$get = filter_var_array($_GET, FILTER_SANITIZE_STRING);

	$query = 'SELECT * FROM pro_2cotizacion c JOIN pro_1cliente p ON c.id_cliente = p.id_cliente WHERE id_cotizacion = ?';
	$result = prepareRS($conexion, $query, [$get['rc']]);
	$rs = $result->fetch(PDO::FETCH_ASSOC);

	$pdf->AliasNbPages();
	$pdf->AddPage();
	$pdf->SetFont('Arial', 'B', 15);
	$pdf->image('../../assets/img/logo.png', 6, 2, 48, 28);
	$pdf->Ln(10);
	// Mover a la derecha
	$pdf->Cell(80);
	$pdf->Cell(30, 6, utf8_decode('COTIZACIÓN'), 0, 0, 'C');
	// Salto de línea
	$pdf->Ln(20);

	// Datos del cliente y los productos
	$queryClient = 'SELECT * FROM pro_3dcotizacion c JOIN pro_2producto p ON c.cod_producto = p.cod_producto WHERE c.id_cotizacion = ?';
	$result = prepareRS($conexion, $queryClient, [$get['rc']]);
	$productos = $result->fetchAll(PDO::FETCH_ASSOC);

	// Generar el contenido del reporte
	$cliente = array(
		'id' => $rs['id_cliente'],
		'razon' => $rs['razon_social'],
		'email' => $rs['email_cliente'],
		'telf' => $rs['telf_cliente'],
	);

	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(0, 6, utf8_decode('NÚMERO: ' . $rs['cod_nota']), 0, 1);
	$pdf->Cell(0, 6, utf8_decode('FECHA: ' . Date("d/m/Y", strtotime($rs['fecha_cotizacion']))), 0, 1);
	$pdf->SetFont('Arial', '', 12);
	// Agregar información del cliente
	$pdf->Cell(0, 6, 'CLIENTE: ' . utf8_decode($cliente['razon']) . " " . $cliente['id'], 0, 1);
	if (!empty($cliente['telf'])):
		$pdf->Cell(0, 6, utf8_decode('TELÉFONO: ' . $cliente['telf']), 0, 1);
	endif;
	if (!empty($cliente['email'])):
		$pdf->Cell(0, 6, utf8_decode('CORREO: ' . $cliente['email']), 0, 1);
	endif;
	// Salto de línea
	$pdf->Ln(6);
	// Agregar tabla de productos
	$pdf->setFillColor($colorTheme['red'], $colorTheme['green'], $colorTheme['blue']);
	$pdf->SetTextColor(255, 255, 255);
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(30, 6, 'CANTIDAD', 1, 0, 'C', true);
	$pdf->Cell(100, 6, 'PRODUCTO', 1, 0, 'C', true);
	$pdf->Cell(30, 6, 'PRECIO', 1, 0, 'C', true);
	$pdf->Cell(40, 6, 'TOTAL', 1, 1, 'C', true);
	//$pdf->Ln();
	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 8);
	$total = 0;
	foreach ($productos as $producto) {
		//$pdf->Cell(30,6,$producto['cod_producto'],1,0,'C');
		$pdf->Cell(30, 6, $producto['cant'], 1, 0, 'C');
		$pdf->Cell(100, 6, utf8_decode($producto['nom_producto']), 1, 0, 'C');
		$pdf->Cell(30, 6, number_format(($producto['monto']), 2, ",", "."), 1, 0, 'C');

		$pdf->Cell(40, 6, number_format(($producto['monto'] * $producto['cant']), 2, ",", "."), 1, 0, 'C');
		$total += ($producto['monto'] * $producto['cant']);
		$pdf->Ln();
	}
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(160, 6, 'TOTAL GENERAL', 0, 0, 'R');
	$pdf->Cell(40, 6, number_format($total, 2, ",", "."), 0, 1, 'C');
	$pdf->Ln(10);
	// Salida del PDF
	$pdf->Output();
}
