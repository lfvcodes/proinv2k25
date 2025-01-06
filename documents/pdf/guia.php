<?php
if (!isset($_GET['rc']) && empty($_GET['rc'])) {
  //header("Location: /index");
  echo 'no hay break';
} else {
  session_start();
  require('../../libraries/php/fpdf/fpdf.php');
  require('../../connections/db.php');
  include('../../utils/php/utils.php');

  $get = filter_var_array($_GET, FILTER_SANITIZE_STRING);
  $nguia = $get['rc'];
  $pdf = new FPDF('P', 'mm', 'letter');
  $pdf->AliasNbPages();
  $pdf->AddPage();
  $pdf->SetTitle(utf8_decode("Guía de despacho #" . $get['rc']));
  $pdf->SetDrawColor(0, 80, 172);
  $pdf->SetTextColor(0, 0, 0);
  $pdf->SetXY((($pdf->GetPageWidth() / 2) + 45), 19);
  $pdf->SetFont('Arial', 'B', 16);
  $pdf->Text(($pdf->GetPageWidth() / 2.5), 15, "TRANSPORTE");
  $pdf->SetFont('Arial', 'B', 8);
  $pdf->Text(($pdf->GetPageWidth() / 1.24), 15, "N.GUIA DE CARGA: " . $get['rc']);
  $pdf->SetXY(10, 5);
  $pdf->image('../../assets/img/logo.png', 10, 2, 48, 16);
  $pdf->Ln(16);
  $queryGuia = 'SELECT fecha_guia AS fecha, vehiculo,placa,
	CONCAT(c.sig_idconductor,"-",c.id_conductor) AS ci,
	CONCAT(c.nombre_conductor," ",c.apellido_conductor) AS conductor
	FROM pro_2guia g
	JOIN pro_1conductor c ON g.id_conductor = c.id_conductor
	WHERE id_guia = ?';
  $guia = prepareRS($conexion, $queryGuia, array($nguia));

  $pdf->SetFont('Arial', '', 8);
  $pdf->Cell(50, 5, "FECHA: " . date('d/m/Y', strtotime($guia['fecha'])), 1, 0, 'L');
  $pdf->Cell(40, 5, "EMPRESA: ", 1, 0, 'R');
  #$pdf->Cell(115, 5, strtoupper($_SESSION['pro']['empresa']['nom_empresa']), 1, 1, 'L');
  $pdf->Cell(50, 5, "VEHICULO: " . strtoupper($guia['vehiculo']), 1, 0, 'L');
  $pdf->Cell(40, 5, "CI CONDUCTOR : ", 1, 0, 'R');
  $pdf->Cell(115, 5, $guia['ci'], 1, 1, 'L');
  $pdf->Cell(50, 5, "PLACA: " . strtoupper($guia['placa']), 1, 0, 'L');
  $pdf->Cell(40, 5, "CONDUCTOR: ", 1, 0, 'R');
  $pdf->Cell(115, 5, strtoupper(utf8_decode(strtolower($guia['conductor']))), 1, 1, 'L');
  $pdf->SetFillColor(18, 59, 137);
  $pdf->SetTextColor(256, 256, 256);
  $pdf->Cell(25, 10, "N.E / FACT", 1, 0, 'C', true);
  $pdf->Cell(25, 10, "MONTO ", 1, 0, 'C', true);
  $pdf->Cell(40, 10, "NRO. DE RIF", 1, 0, 'C', true);
  $pdf->Cell(65, 10, "RAZON SOCIAL", 1, 0, 'C', true);
  $pdf->Cell(20, 10, "ITEMS", 1, 0, 'C', true);
  $pdf->Cell(30, 10, "DESTINO", 1, 1, 'C', true);
  $pdf->SetTextColor(0, 0, 0);
  /*
  $query = 'SELECT v.id_venta, 
	IF(ISNULL(v.cod_factura),CONCAT("N# ",v.cod_nota),CONCAT("F# ",v.cod_factura)) AS txtventa,
	(SELECT SUM(cant) FROM pro_3dventa dv WHERE dv.id_venta = v.id_venta) AS items, 
	(SELECT SUM(monto) FROM pro_3dventa dv WHERE dv.id_venta = v.id_venta) AS montoVenta,
	CONCAT(c.sig_idcliente,"-",c.id_cliente) AS rif,
	c.razon_social AS razon,
	e.nom_estado AS estado
	FROM pro_3dguia g JOIN pro_2venta v ON g.id_venta = v.id_venta 
	JOIN pro_1cliente c ON v.id_cliente = c.id_cliente 
	JOIN glo_1estado e ON c.cod_estado = e.cod_estado
	WHERE g.id_guia = ? ORDER BY id_detalle ASC';

  $lst = $bd->prepareAll($query, array($nguia));
  $end = sizeof($lst);
  $tmonto = $tcant = 0;
  for ($i = 0; $i < $end; $i++) {
    $pdf->Cell(25, 6, $lst[$i]['txtventa'], 1, 0, 'C');
    $pdf->Cell(25, 6, $lst[$i]['montoVenta'], 1, 0, 'C');
    $pdf->Cell(40, 6, $lst[$i]['rif'], 1, 0, 'C');
    $pdf->Cell(65, 6, strtoupper(utf8_decode(strtolower($lst[$i]['razon']))), 1, 0, 'C');
    $pdf->Cell(20, 6, $lst[$i]['items'], 1, 0, 'C');
    $pdf->Cell(30, 6, $lst[$i]['estado'], 1, 1, 'C');
    $tmonto += $lst[$i]['montoVenta'];
    $tcant += $lst[$i]['items'];
  }*/
  $tmonto = $tcant = 0;
  $pdf->SetTextColor(256, 256, 256);
  $pdf->Cell(25, 6, "TOTAL", 1, 0, 'C', true);
  $pdf->Cell(25, 6, $tmonto, 1, 0, 'C', true);
  $pdf->Cell(40, 6, " ", 1, 0, 'C', true);
  $pdf->Cell(65, 6, " ", 1, 0, 'C', true);
  $pdf->Cell(20, 6, $tcant, 1, 0, 'C', true);
  $pdf->Cell(30, 6, " ", 1, 1, 'C', true);
  $pdf->Output();
}
