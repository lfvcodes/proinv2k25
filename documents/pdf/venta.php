<?php
if (!isset($_GET['v']) && empty($_GET['v'])) {
	header("Location: /e404.html");
} else {

	require_once(__DIR__ . "/../../libraries/php/fpdf/fpdf.php");
	require_once(__DIR__ . "/../../connections/db.php");
	require_once __DIR__ . '/../../utils/php/utils.php';

	$session = verifySession();
	if (!$session) {
		responseJSON(['status' => 400, 'error' => 'Estimado usuario, su sesión ha expirado. Por favor, inicie sesión nuevamente.']);
	}

	$get = filter_var_array($_GET, FILTER_SANITIZE_STRING);
	$orientation = ($get['t'] == 'F' || $get['t'] == 'FD') ? 'L' : 'P';
	$pdf = new FPDF($orientation, 'mm', 'letter');

	$query = 'SELECT * FROM pro_2venta c JOIN pro_1cliente p ON c.id_cliente = p.id_cliente ';
	if ($get['t'] === 'C' || $get['t'] === 'FC') {
		$query .= 'JOIN pro_2cxc cx ON c.id_venta = cx.id_venta ';
	}

	$query .= 'WHERE c.id_venta = ?';

	$result = prepareRS($conexion, $query, [$get['v']]);
	$rs = $result->fetch(PDO::FETCH_ASSOC);

	$pdf->AliasNbPages();
	$pdf->AddPage();

	$tt = ($get['t'] == 'C' || $get['t'] == 'D') ? 'Nota de Entrega #' . $rs['cod_nota'] : 'Factura #' . $rs['cod_factura'];
	$tp = ($get['t'] == 'C' || $get['t'] == 'D') ? 'NOTA DE ENTREGA' : 'FACTURA';
	$pdf->SetFont('Arial', 'B', 9);
	$pdf->SetTitle(utf8_decode($tt));

	if ($get['t'] === 'C' || $get['t'] === 'D'):

		if ($get['t'] === 'C') {
			$pdf->SetDrawColor(0, 80, 172);
		}

		$pdf->SetXY((($pdf->GetPageWidth() / 2) + 45), 10);
		$pdf->Cell(80, 4, $tp, 0, 1, 'C');

		$pdf->SetFont('Arial', 'B', 9);
		$pdf->SetXY((($pdf->GetPageWidth() / 2) + 50), 4);
		$pdf->Text($pdf->GetX() + 20, $pdf->GetY() + 12, utf8_decode('EMISIÓN: ') . Date('d/m/Y', strtotime($rs['fecha_venta'])));

		if ($get['t'] === 'C') {
			$fechaInicio = $rs['fecha_venta'];
			$fechaFin = $rs['fvencimiento'];
			$diferencia = strtotime($fechaFin) - strtotime($fechaInicio);
			$nd = floor($diferencia / (60 * 60 * 24));
			$pdf->Text($pdf->GetX() + 20, $pdf->GetY() + 15, utf8_decode('CREDITO (' . $nd . ' Días)'));
		}

		$pdf->SetTextColor(250, 0, 0);
		$pdf->SetXY((($pdf->GetPageWidth() / 2) + 45), 19);
		$pdf->SetFont('Arial', 'B', 8);
		$nroid = ($get['t'] === 'C' || $get['t'] === 'D') ? $rs['cod_nota'] : $rs['cod_factura'];
		$pdf->Cell(80, 4, utf8_decode('N° ') . $nroid, 0, 1, 'C');
		$pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', '', 8);

		$pdf->SetXY(10, 5);
		$pdf->image('../../assets/img/logo.png', 10, 2, 42, 16);
		$pdf->Ln(13);
		$pdf->MultiCell(100, 3, utf8_decode($session['direccion'] . "\n" . "Teléfono: " . $session['telf'] . " " . "Email: " . $session['email']), 0, 'L');
		$pdf->Ln(1);

		// Datos del cliente y los productos
		$queryClient = 'SELECT * FROM pro_3dventa c JOIN pro_2producto p ON c.cod_producto = p.cod_producto WHERE c.id_venta = ?';
		$result = prepareRS($conexion, $queryClient, [$get['v']]);
		$productos = $result->fetchAll(PDO::FETCH_ASSOC);


		// Generar el contenido del reporte
		$cliente = array(
			'id' => $rs['sig_idcliente'] . "-" . $rs['id_cliente'],
			'razon' => $rs['razon_social'],
			'email' => $rs['email_cliente'],
			'telf' => $rs['telf_cliente'],
			'dir' => $rs['dir_cliente'],
			'precio' => $rs['tipo_precio'] ?? '',
		);

		$pdf->SetFont('Arial', '', 8);
		$pdf->MultiCell(40, 4, utf8_decode("Nombre ó Razón Social:"), 0, 'L');
		$pdf->setXY($pdf->getX() + 50, $pdf->GetY() - 4);
		$pdf->Cell(230, 3, utf8_decode($cliente['razon']), 0, 1, "L", false);
		$pdf->setXY($pdf->getX() + 50, $pdf->GetY() + 3);
		$pdf->MultiCell(260, 4, ' ', 0, 'L');
		$pdf->setXY($pdf->getX(), $pdf->GetY() - 7);
		$pdf->MultiCell(40, 4, utf8_decode("Domicilio Fiscal:"), 0, 'L');
		$pdf->setXY($pdf->getX() + 50, $pdf->GetY() - 4);
		$pdf->Cell(230, 4, utf8_decode($cliente['dir']), 0, 1);
		$pdf->MultiCell(260, 8, ' ', 0, 'L');
		$pdf->setXY($pdf->getX(), $pdf->GetY() - 9);
		$pdf->MultiCell(40, 4, utf8_decode("RIF:"), 0, 'L');
		$pdf->setXY($pdf->getX() + 50, $pdf->GetY() - 4);
		$pdf->Cell(230, 4, utf8_decode($cliente['id']), 0, 1);

		// Salto de línea
		$pdf->Ln(2);
		// Agregar tabla de productos

		$pdf->SetFont('Arial', 'B', 9);
		$pdf->Cell(18, 4, 'CANTIDAD', 'B', 0, 'C');
		if ($get['t'] == 'F'):
			$pdf->Cell(120, 4, utf8_decode('DESCRIPCIÓN'), 'B', 0, 'L');
			$pdf->Cell(10, 4, 'E', 'B', 0, 'C');
		else:
			$pdf->Cell(130, 4, utf8_decode('DESCRIPCIÓN'), 'B', 0, 'L');
		endif;
		$pdf->Cell(10, 4, 'CAPAC.', 'B', 0, 'C');
		$pdf->Cell(20, 4, 'PRECIO U.', 'B', 0, 'C');
		$pdf->Cell(20, 4, 'MONTO', 'B', 1, 'C');
		//$pdf->Ln();
		$pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', '', 9);
		$total = 0;
		$it = 0;
		foreach ($productos as $producto) {
			//$pdf->Cell(30,10,$producto['cod_producto'],1,0,'C');
			$umedida = '';
			switch ($producto['u_medida']) {
				case 'BD':
					$umedida = 'Bidon';
					break;
				case 'BU':
					$umedida = 'Bulto';
					break;
				case 'G':
					$umedida = 'Galon';
					break;
				case 'L':
					$umedida = 'Litro';
					break;
				case 'C':
					$umedida = 'Caja';
					break;
				case 'P':
					$umedida = 'Paquete';
					break;
				case 'U':
					$umedida = 'Unidad';
					break;
			}

			$pdf->Cell(18, 4, $producto['cant'], 0, 0, 'C');
			if ($get['t'] == 'F'):
				$pdf->Cell(120, 4, utf8_decode($producto['nom_producto']), 0, 0, 'L');
				$pdf->Cell(10, 4, " ", 0, 0, 'C');
			else:
				$pdf->Cell(130, 4, utf8_decode($producto['nom_producto']), 0, 0, 'L');
			endif;
			$pdf->Cell(10, 4, $umedida, 0, 0, 'C');
			$pdf->Cell(20, 4, number_format($producto['monto'], 2, ",", "."), 0, 0, 'C');
			#$pdf->Cell(20,4,number_format($producto['monto'] / $producto['cant'],2,",","."),0,0,'C');
			$pdf->Cell(20, 4, number_format(($producto['monto'] * $producto['cant']), 2, ",", "."), 0, 0, 'C');
			$total += ($producto['monto'] * $producto['cant']);
			$pdf->Ln();
			$it++;
		}

		$lineas = ($rs['iva'] == 1) ? 17 : 20;
		for ($i = $it; $i < $lineas; $i++) {
			$pdf->Cell(198, 4, '', 0, 1, 'C');
		}

		$pdf->SetFont('Arial', 'B', 8.5);
		if ($rs['iva'] == 1) {
			$pdf->Cell(178, 3.5, 'SUBTOTAL: ', 0, 0, 'R');
			$pdf->Cell(20, 3.5, number_format($total, 2, ",", "."), 0, 1, 'C');
			$pdf->Cell(178, 3.5, 'IVA 16%', 0, 0, 'R');
			$pdf->Cell(20, 3.5, number_format($total * 0.16, 2, ",", "."), 0, 1, 'C');
			$pdf->Cell(178, 3.5, 'TOTAL GENERAL :', 0, 0, 'R');
			$pdf->Cell(20, 3.5, number_format(($total + ($total * 0.16)), 2, ",", "."), 0, 1, 'C');
		} else {
			$pdf->Cell(178, 3.5, 'TOTAL GENERAL: ', 0, 0, 'R');
			$pdf->Cell(20, 3.5, number_format($total, 2, ",", "."), 0, 1, 'C');
		}
		$pdf->Ln(10);

		$spx = $pdf->GetX() - 1;
		$spy = $pdf->GetY();
		$pdf->SetDrawColor(0, 0, 0);
		$pdf->Line($spx - 10, $spy - 2, 280, $spy - 2);
		$pdf->Ln(3);
		$spy = $pdf->GetY();
		##################################################################################

		if ($get['t'] === 'C') {
			$pdf->SetDrawColor(0, 80, 172);
		}

		$pdf->SetFont('Arial', 'B', 9);
		$pdf->setXY((($pdf->GetPageWidth() / 2) + 40), $spy - 2);
		$pdf->Cell(80, 4, $tp, 0, 1, 'C');
		$pdf->SetXY((($pdf->GetPageWidth() / 2) + 45), $spy + 12);
		$pdf->Text($pdf->GetX() + 20, $spy + 4, utf8_decode('EMISIÓN: ') . Date('d/m/Y', strtotime($rs['fecha_venta'])));
		if ($get['t'] == 'C') {
			$fechaInicio = $rs['fecha_venta'];
			$fechaFin = $rs['fvencimiento'];
			$diferencia = strtotime($fechaFin) - strtotime($fechaInicio);
			$nd = floor($diferencia / (60 * 60 * 24));
			$pdf->Text($pdf->GetX() + 20, $spy + 7, utf8_decode('CREDITO (' . $nd . ' Días)'));
		}

		$pdf->SetTextColor(250, 0, 0);
		$pdf->SetXY((($pdf->GetPageWidth() / 2) + 40), $spy + 8);
		$pdf->SetFont('Arial', 'B', 9);
		$nroid = ($get['t'] == 'C' || $get['t'] == 'D') ? $rs['cod_nota'] : $rs['cod_factura'];
		$pdf->Cell(80, 3, utf8_decode('N° ' . $nroid), 0, 1, 'C');
		$pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', '', 8);

		#$pdf->SetXY(10,$pdf->GetY());
		$pdf->image('../../assets/img/logo.png', 10, $spy - 3, 42, 16);
		$pdf->Ln(2);
		$pdf->MultiCell(100, 3, utf8_decode($session['direccion'] . "\n" . "Teléfono: " . $session['telf'] . " " . "Email: " . $session['email']), 0, 'L');
		$pdf->Ln(2);

		// Datos del cliente y los productos
		$pdf->SetFont('Arial', '', 8);

		$pdf->MultiCell(40, 4, utf8_decode("Nombre ó Razón Social:"), 0, 'L');
		$pdf->setXY($pdf->getX() + 50, $pdf->GetY() - 4);
		$pdf->Cell(230, 3, utf8_decode($cliente['razon']), 0, 1, "L", false);
		$pdf->setXY($pdf->getX() + 50, $pdf->GetY() + 3);
		$pdf->MultiCell(260, 4, ' ', 0, 'L');
		$pdf->setXY($pdf->getX(), $pdf->GetY() - 6);
		$pdf->MultiCell(40, 4, utf8_decode("Domicilio Fiscal:"), 0, 'L');
		$pdf->setXY($pdf->getX() + 50, $pdf->GetY() - 7);
		$pdf->Cell(230, 10, utf8_decode($cliente['dir']), 0, 1);
		$pdf->MultiCell(260, 4, ' ', 0, 'L');
		$pdf->setXY($pdf->getX(), $pdf->GetY() - 7);
		$pdf->MultiCell(40, 3, utf8_decode("RIF:"), 0, 'L');
		$pdf->setXY($pdf->getX() + 50, $pdf->GetY() - 3);
		$pdf->Cell(230, 3, utf8_decode($cliente['id']), 0, 1);

		// Salto de línea
		$pdf->Ln(2);
		// Agregar tabla de productos

		$pdf->SetFont('Arial', 'B', 9);
		$pdf->Cell(18, 4, 'CANTIDAD', 'B', 0, 'C');
		if ($get['t'] == 'F'):
			$pdf->Cell(120, 4, utf8_decode('DESCRIPCIÓN'), 'B', 0, 'L');
			$pdf->Cell(10, 4, 'E', 1, 0, 'C');
		else:
			$pdf->Cell(130, 4, utf8_decode('DESCRIPCIÓN'), 'B', 0, 'L');
		endif;
		$pdf->Cell(10, 4, 'CAPAC.', 'B', 0, 'C');
		$pdf->Cell(20, 4, 'PRECIO U.', 'B', 0, 'C');
		$pdf->Cell(20, 4, 'MONTO', 'B', 1, 'C');
		//$pdf->Ln();
		$pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', '', 9);
		$total = 0;
		$it = 0;
		foreach ($productos as $producto) {
			//$pdf->Cell(30,10,$producto['cod_producto'],1,0,'C');
			$umedida = '';
			switch ($producto['u_medida']) {
				case 'BD':
					$umedida = 'Bidon';
					break;
				case 'BU':
					$umedida = 'Bulto';
					break;
				case 'G':
					$umedida = 'Galon';
					break;
				case 'L':
					$umedida = 'Litro';
					break;
				case 'C':
					$umedida = 'Caja';
					break;
				case 'P':
					$umedida = 'Paquete';
					break;
				case 'U':
					$umedida = 'Unidad';
					break;
			}

			$pdf->Cell(18, 4, $producto['cant'], 0, 0, 'C');
			if ($get['t'] == 'F'):
				$pdf->Cell(120, 4, utf8_decode($producto['nom_producto']), 0, 0, 'L');
				$pdf->Cell(10, 4, " ", 0, 0, 'C');
			else:
				$pdf->Cell(130, 4, utf8_decode($producto['nom_producto']), 0, 0, 'L');
			endif;
			$pdf->Cell(10, 4, $umedida, 0, 0, 'C');

			$pdf->Cell(20, 4, number_format($producto['monto'], 2, ",", "."), 0, 0, 'C');

			$pdf->Cell(20, 4, number_format(($producto['monto'] * $producto['cant']), 2, ",", "."), 0, 0, 'C');
			$total += ($producto['monto'] * $producto['cant']);
			$pdf->Ln();
			$it++;
		}

		$lineas = ($rs['iva'] == 1) ? 17 : 20;
		for ($i = $it; $i < $lineas; $i++) {
			$pdf->Cell(198, 4, '', 0, 1, 'C');
		}

		$pdf->SetFont('Arial', 'B', 8.5);
		if ($rs['iva'] == 1) {
			$pdf->Cell(178, 3.5, 'SUBTOTAL: ', 0, 0, 'R');
			$pdf->Cell(20, 3.5, number_format($total, 2, ",", "."), 0, 1, 'C');
			$pdf->Cell(178, 3.5, 'IVA 16%', 0, 0, 'R');
			$pdf->Cell(20, 3.5, number_format($total * 0.16, 2, ",", "."), 0, 1, 'C');
			$pdf->Cell(178, 3.5, 'TOTAL GENERAL :', 0, 0, 'R');
			$pdf->Cell(20, 3.5, number_format(($total + ($total * 0.16)), 2, ",", "."), 0, 1, 'C');
		} else {
			$pdf->Cell(178, 3.5, 'TOTAL GENERAL: ', 0, 0, 'R');
			$pdf->Cell(20, 3.5, number_format($total, 2, ",", "."), 0, 1, 'C');
		}
		$spx = $pdf->GetX();
		$spy = $pdf->GetY() + 10;
		$pdf->Ln(10);
		$pdf->Line($spx - 10, $spy - 2, 280, $spy - 2);
	//$pdf->Ln();

	else:
		#FACTURA
		$pdf->SetXY((($pdf->GetPageWidth() / 2) + 50), 10);
		$pdf->Cell(80, 4, $tp, 1, 1, 'C');
		$pdf->SetXY((($pdf->GetPageWidth() / 2) + 50), 14);
		$pdf->Cell(10, 4, 'D', 1, 0, 'C');
		$pdf->Cell(10, 4, 'D', 1, 0, 'C');
		$pdf->Cell(10, 4, 'M', 1, 0, 'C');
		$pdf->Cell(10, 4, 'M', 1, 0, 'C');
		$pdf->Cell(10, 4, 'A', 1, 0, 'C');
		$pdf->Cell(10, 4, 'A', 1, 0, 'C');
		$pdf->Cell(10, 4, 'A', 1, 0, 'C');
		$pdf->Cell(10, 4, 'A', 1, 1, 'C');
		$pdf->SetFont('Arial', '', 10);
		$pdf->SetXY((($pdf->GetPageWidth() / 2) + 50), 18);
		$fecha = Date("dmY", strtotime($rs['fecha_venta']));
		$digitos = str_split($fecha);
		foreach ($digitos as $digito) {
			$pdf->Cell(10, 4, $digito, 1, 0, 'C');
		}
		$pdf->SetFont('Arial', 'B', 10);

		$pdf->Text($pdf->GetX() - 80, $pdf->GetY() + 14, utf8_decode('EMISIÓN: ') . Date('d/m/Y', strtotime($rs['fecha_venta'])));
		if ($get['t'] == 'F') {
			$fechaInicio = $rs['fecha_venta'];
			$fechaFin = $rs['fvencimiento'];
			$diferencia = strtotime($fechaFin) - strtotime($fechaInicio);
			$nd = floor($diferencia / (60 * 60 * 24));
			$pdf->Text($pdf->GetX() - 80, $pdf->GetY() + 18, utf8_decode('CREDITO (' . $nd . ' Días)'));
		}

		$pdf->SetTextColor(250, 0, 0);
		$pdf->SetXY((($pdf->GetPageWidth() / 2) + 50), 22);
		$pdf->SetFont('Arial', 'B', 10);
		$nroid = $rs['cod_factura'];
		$pdf->Cell(80, 4, utf8_decode('N° ' . $nroid), 1, 1, 'C');
		$pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', '', 10);

		$pdf->SetXY(10, 12);
		$pdf->image('../../assets/img/logo.png', 10, 2, 54, 20);
		$pdf->Ln(16);
		$pdf->MultiCell(80, 4, utf8_decode($session['direccion'] . "\n" . "Teléfono: " . $session['telf'] . "\n" . "Email: " . $session['email']), 0, 'C');
		$pdf->Ln(5);

		// Datos del cliente y los productos
		$queryClient = 'SELECT * FROM pro_3dventa c JOIN pro_2producto p ON c.cod_producto = p.cod_producto WHERE c.id_venta = ?';
		$result = prepareRS($conexion, $queryClient, [$get['v']]);
		$productos = $result->fetchAll(PDO::FETCH_ASSOC);

		// Generar el contenido del reporte
		$cliente = array(
			'id' => $rs['sig_idcliente'] . "-" . $rs['id_cliente'],
			'razon' => $rs['razon_social'],
			'email' => $rs['email_cliente'],
			'telf' => $rs['telf_cliente'],
			'dir' => $rs['dir_cliente'],
			'precio' => $rs['tipo_precio'] ?? '',
		);

		$pdf->SetFont('Arial', '', 11);
		// Agregar información del cliente
		$pdf->MultiCell(260, 10, ' ', 1, 'L');
		$pdf->setXY($pdf->getX(), $pdf->GetY() - 10);
		$pdf->MultiCell(40, 5, utf8_decode("Nombre y Apellido\n O Razón Social:"), 0, 'L');
		$pdf->setXY($pdf->getX() + 50, $pdf->GetY() - 9);
		$pdf->Cell(230, 9, utf8_decode($cliente['razon']), 0, 1);

		$pdf->MultiCell(260, 8, ' ', 1, 'L');
		$pdf->setXY($pdf->getX(), $pdf->GetY() - 6);
		$pdf->MultiCell(40, 4, utf8_decode("Domicilio Fiscal:"), 0, 'L');
		$pdf->setXY($pdf->getX() + 50, $pdf->GetY() - 8);
		$pdf->Cell(230, 10, utf8_decode($cliente['dir']), 0, 1);
		$pdf->MultiCell(260, 8, ' ', 1, 'L');
		$pdf->setXY($pdf->getX(), $pdf->GetY() - 6);
		$pdf->MultiCell(40, 4, utf8_decode("RIF:"), 0, 'L');
		$pdf->setXY($pdf->getX() + 50, $pdf->GetY() - 4);
		$pdf->Cell(230, 6, utf8_decode($cliente['id']), 0, 1);

		// Salto de línea
		$pdf->Ln(6);
		// Agregar tabla de productos

		$pdf->SetFont('Arial', 'B', 12);
		$pdf->Cell(15, 8, 'CANT', 1, 0, 'C');
		if ($get['t'] == 'D'):
			$pdf->Cell(140, 8, utf8_decode('DESCRIPCIÓN'), 1, 0, 'C');
			$pdf->Cell(10, 8, 'E', 1, 0, 'C');
		else:
			$pdf->Cell(150, 8, utf8_decode('DESCRIPCIÓN'), 1, 0, 'C');
		endif;
		$pdf->Cell(20, 8, 'CAPAC.', 1, 0, 'C');
		$pdf->Cell(35, 8, 'PRECIO UNIT.', 1, 0, 'C');
		$pdf->Cell(40, 8, 'MONTO', 1, 1, 'C');
		//$pdf->Ln();
		$pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', '', 11);
		$total = 0;
		$it = 0;
		foreach ($productos as $producto) {
			//$pdf->Cell(30,10,$producto['cod_producto'],1,0,'C');
			$umedida = '';
			switch ($producto['u_medida']) {
				case 'BD':
					$umedida = 'Bidon';
					break;
				case 'BU':
					$umedida = 'Bulto';
					break;
				case 'G':
					$umedida = 'Galon';
					break;
				case 'L':
					$umedida = 'Litro';
					break;
				case 'C':
					$umedida = 'Caja';
					break;
				case 'P':
					$umedida = 'Paquete';
					break;
				case 'U':
					$umedida = 'Unidad';
					break;
			}

			$pdf->Cell(15, 10, $producto['cant'], 1, 0, 'C');
			if ($get['t'] == 'D'):
				$pdf->Cell(140, 10, utf8_decode($producto['nom_producto']), 1, 0, 'C');
				$pdf->Cell(10, 10, " ", 1, 0, 'C');
			else:
				$pdf->Cell(150, 10, utf8_decode($producto['nom_producto']), 1, 0, 'C');
			endif;
			$pdf->Cell(20, 10, $umedida, 1, 0, 'C');

			$pdf->Cell(35, 10, number_format($producto['p_venta'], 2, ",", "."), 1, 0, 'C');

			$pdf->Cell(40, 10, number_format(($producto['monto'] * $producto['cant']), 2, ",", "."), 1, 0, 'C');
			$total += ($producto['monto'] * $producto['cant']);
			$pdf->Ln();
			$it++;
		}

		$lineas = 18;
		for ($i = $it; $i < $lineas; $i++) {
			$pdf->Cell(198, 4, '', 0, 1, 'C');
		}
		$pdf->SetFont('Arial', 'B', 10);
		if ($rs['iva'] == 1) {
			$pdf->Cell(220, 8, 'SUBTOTAL: ', 0, 0, 'R');
			$pdf->Cell(40, 8, number_format($total, 2, ",", "."), 0, 1, 'C');
			$pdf->Cell(220, 8, 'IVA 16%', 0, 0, 'R');
			$pdf->Cell(40, 8, number_format($total * 0.16, 2, ",", "."), 0, 1, 'C');
			$pdf->Cell(220, 8, 'TOTAL GENERAL :', 0, 0, 'R');
			$pdf->Cell(40, 8, number_format(($total + ($total * 0.16)), 2, ",", "."), 0, 1, 'C');
		} else {
			$pdf->Cell(220, 8, 'TOTAL GENERAL: ', 0, 0, 'R');
			$pdf->Cell(40, 8, number_format($total, 2, ",", "."), 0, 1, 'C');
		}
	endif;
	// Salida del PDF
	$pdf->Output();
}
