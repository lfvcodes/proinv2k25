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

if ($post['endpoint'] === 'add') {

  $params = array(
    $post['optcliente'],
    $post['fregc'] . ' ' . $post['ftime'],
    $post['ncot'],
    $post['desc'],
    'admin',
    implode(",", $post['prod']),
    implode(",", $post['cant']),
    implode(",", $post['monto'])
  );

  $query = 'CALL pro_5setVenta (?,?,?,?,?,?,?,?)';
  $rs = prepareRS($conexion, $query, $params);
  responseJSON($rs->fetch(PDO::FETCH_ASSOC));
  #setBitacora('VENAS', 'AGREGAR VENTA: ' . $post['fact'], $params, $_SESSION['pro']['usr']['user']);

}
