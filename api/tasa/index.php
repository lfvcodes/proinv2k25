<?php
require_once(__DIR__ . "/../../connections/db.php");
require_once __DIR__ . '/../../utils/php/utils.php';
require_once __DIR__ . '/../api_header.php';

if ($post['endpoint'] == 'getTasaToday') {

	$fechaActual = date('Y-m-d');
	$query = 'SELECT DATE(fecha) AS ultimaTasa FROM pro_bitacora.pro_2bitacora WHERE modulo = ? ORDER BY fecha DESC LIMIT 1';
	$result = prepareRS($conexion, $query, array('ACTUALIZAR TASA'));
	$start = $result->fetch();

	if ($start['ultimaTasa'] !== $fechaActual) {

		$queryTasaLast = 'SELECT tasa,fecha_tasa AS ultimaTasa FROM pro_4tasa WHERE ? ORDER BY DATE(fecha_tasa) DESC LIMIT 1';
		$resultTasa = prepareRS($conexion, $queryTasaLast, array(1));

		if ($resultTasa->rowCount() > 0) {
			$row = $resultTasa->fetch();
			$fechaObtenida = $row['ultimaTasa'];
			if ($fechaObtenida < $fechaActual):
				responseJSON(['status' => 200, 'result' => 'Actualizar Tasa']);
			endif;
		} else {
			responseJSON(['status' => 200, 'result' => 'Actualizar Tasa']);
		}
	} else {
		responseJSON(['status' => 200, 'result' => 'No Actualizar']);
	}
}

if ($post['endpoint'] == 'getList') {
	$query = 'SELECT id_act AS id,tasa,fecha_tasa AS fecha, log_user AS log FROM pro_4tasa ORDER BY (id_act) DESC';
	$rs = prepareRS($conexion, $query, []);
	resultResponse($rs, 'all');
}

if ($post['endpoint'] == 'add') {

	$query = "INSERT INTO pro_4tasa (tasa,log_user) VALUES (?,?)";
	$params = [$post['tasa'], $session['log_user']];
	if (!prepareRS($conexion, $query, $params)):
		$error = $_SESSION['error'];
		unset($_SESSION['error']);
		responseJSON(['status' => 400, 'error' => $error]);
	else:
		$usrData = ExtractValues​​ExcludingKeys($session, ['iat', 'exp']);
		$usrData['tasa'] = $post['tasa'];
		generateToken($usrData);
		#setBitacora('TASA', "AGREGAR TASA", $params, json_encode($usrData));
		responseJSON(['status' => 200, 'message' => "Se ha Actualizado la Nueva Tasa de Cambio Correctamente"]);
	endif;
}

if ($post['endpoint'] == 'update') {
	$query = "UPDATE pro_4tasa SET tasa = ? WHERE id_act = ?";

	$params = [$post['tasa'], $post['id']];

	if (!prepareRS($conexion, $query, $params)):
		$error = $_SESSION['error'];
		unset($_SESSION['error']);
		responseJSON(['status' => 400, 'error' => $error]);
	else:
		responseJSON(['status' => 200, 'message' => 'Tasa(s) Editada Correctamente']);
	endif;
}

if ($post['endpoint'] == 'delete') {
	$query = "DELETE FROM pro_4tasa WHERE id_act IN ";
	$delItems = implode("','", $post['list']);
	$query .= "('{$delItems}')";

	if (!prepareRS($conexion, $query, [])):
		$error = $_SESSION['error'];
		unset($_SESSION['error']);
		responseJSON(['status' => 400, 'error' => $error]);
	else:
		responseJSON(['status' => 200, 'message' => 'Tasa(s) de Cambio Borrada(s) Correctamente']);
	endif;
}
