<?php
require_once(__DIR__ . "/../../connections/db.php");
require_once __DIR__ . '/../../utils/php/utils.php';
require_once __DIR__ . '/../api_header.php';


if ($post['endpoint'] === 'getDetailTable') {
  $query = 'SELECT *,
  (SELECT fvencimiento FROM pro_2cxc WHERE id_venta = c.id_venta) AS vence 
  FROM pro_3dventa c 
  JOIN pro_2producto p ON c.cod_producto = p.cod_producto 
  WHERE c.id_venta = ?
  ORDER BY id_detalle ASC';
  $rs = prepareRS($conexion, $query, [$post['id']]);
  resultResponse($rs, 'all');
}
