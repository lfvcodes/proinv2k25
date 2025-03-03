<?php
require_once(__DIR__ . "/../../connections/db.php");
require_once __DIR__ . '/../../utils/php/utils.php';
require_once __DIR__ . '/../api_header.php';

if ($post['endpoint'] === 'getDetail') {
  $query = 'SELECT *,
  (SELECT fvencimiento FROM pro_2cxc WHERE id_venta = c.id_venta) AS vence
  FROM pro_3dventa c
  JOIN pro_2producto p ON c.cod_producto = p.cod_producto
  WHERE c.id_venta = ?
  ORDER BY id_detalle ASC';
  $rs = prepareRS($conexion, $query, [$post['id']]);
  resultResponse($rs, 'all');
}

if ($post['endpoint'] === 'setVenta') {

  $params = array(
    $post['optcli'],
    $post['freg'],
    $post['desc'],
    $post['fact'],
    $post['tasa'],
    $post['tventa'],
    'admin',
    implode(",", $post['prod[]']),
    implode(",", $post['cant[]']),
    implode(",", $post['monto[]']),
  );
  $query = 'CALL pro_5setVenta (?,?,?,?,?,?,?,?,?,?)';
  $rs = prepareRS($conexion, $query, $params);
  if ($rs) {
    #setBitacora('VENTAS', 'AGREGAR VENTA: ' . $post['fact'], $params, $session['user']);
    responseJSON($rs->fetch(PDO::FETCH_ASSOC));
  } else {
    // Manejar el error aquí, por ejemplo, mostrar un mensaje de error
    responseJSON(array('error' => $_SESSION['error']));
  }
}

responseJSON(['status' => 400, 'message' => 'Endpoint not found']);
