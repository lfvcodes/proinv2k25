<?php
require_once(__DIR__ . "/../../connections/db.php");
require_once __DIR__ . '/../../utils/php/utils.php';
require_once __DIR__ . '/../api_header.php';

if ($post['endpoint'] === 'getList') {
  $query = 'SELECT id_cxp AS cod,id_compra AS compra, r.cod_nota AS nota, monto,
   c.id_proveedor AS idp, p.razon_social AS nom, concepto,
    fvencimiento AS fecha,DATE_FORMAT(fvencimiento, "%d/%m/%Y") AS fven,
     r.fecha_compra AS fechac,estado 
    FROM pro_2cxp c 
    JOIN pro_2compra r ON c.id_compra = r.id_pago 
    JOIN pro_1proveedor p ON c.id_proveedor = p.id_proveedor 
    WHERE estado <> ? ORDER BY id_cxp DESC';
  $rs = prepareRS($conexion, $query, ['S']);
  resultResponse($rs, 'all');
}

if ($post['endpoint'] === 'getCxpSolvent') {
  $query = 'SELECT v.id_pago AS compra,v.cod_nota AS nota, v.cod_factura AS fact,c.razon_social AS prov, cx.monto,  DATE_FORMAT(cx.fecha_cobro, "%d/%m/%Y") AS cobro FROM pro_2cxp cx JOIN pro_2compra v ON cx.id_compra = v.id_pago JOIN pro_1proveedor c ON cx.id_proveedor = c.id_proveedor WHERE estado = ? ORDER BY fecha_cobro DESC';
  $rs = prepareRS($conexion, $query, ['S']);
  resultResponse($rs, 'all');
}

if ($post['endpoint'] === 'revertCxpSolvent') {
  $query = 'UPDATE pro_2cxp SET estado = ?, fecha_cobro = ? WHERE id_compra = ?';
  $idCompra = $post['idCompra'];
  if (!prepareRS($conexion, $query, ['P', null, $idCompra])):
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
    responseJSON(['status' => 400, 'error' => $error]);
  else:
    responseJSON(['status' => 200, 'message' => "Cuenta por Pagar {$idCompra} Revertida Correctamente"]);
  endif;
}
