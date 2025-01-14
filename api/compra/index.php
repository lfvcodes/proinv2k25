<?php
require_once(__DIR__ . "/../../connections/db.php");
require_once __DIR__ . '/../../utils/php/utils.php';
require_once __DIR__ . '/../api_header.php';


if ($post['endpoint'] === 'getDetailTable') {
  $query = 'SELECT *,
  (SELECT fvencimiento FROM pro_2cxp WHERE id_compra = c.id_pago) AS vence FROM pro_3dcompra c 
  JOIN pro_2producto p ON c.cod_producto = p.cod_producto WHERE id_pago = ?
  ORDER BY id_detalle ASC';
  $rs = prepareRS($conexion, $query, [$post['id']]);
  resultResponse($rs, 'all');
}
