<?php

require_once(__DIR__ . "/../../connections/db.php");
require_once __DIR__ . '/../../utils/php/utils.php';
require_once __DIR__ . '/../api_header.php';

if ($post['endpoint'] == 'getListCxc') {
   $query = 'SELECT id_cxc AS cod,c.id_venta AS venta, r.cod_nota AS nota, monto, p.razon_social AS nom, c.id_cliente AS idc,
    concepto, fvencimiento AS fecha,DATE_FORMAT(fvencimiento, "%d/%m/%Y") AS fven,
     r.fecha_venta AS fechav, estado, null AS accion FROM pro_2cxc c
      JOIN pro_2venta r ON c.id_venta = r.id_venta
      JOIN pro_1cliente p ON c.id_cliente = p.id_cliente
      WHERE estado <> ? ORDER BY id_cxc DESC';

   $rs = prepareRS($conexion, $query, array('S'));
   resultResponse($rs, 'all');
}

if ($post['endpoint'] == 'getDetailAbono') {
   $query = 'SELECT * FROM pro_3dcxc_abono WHERE id_cxc = ? ORDER BY id_abono ASC';
   $rs = prepareRS($conexion, $query, [$post['id']]);
   resultResponse($rs, 'all');
}

if ($post['endpoint'] == 'getCxcSolvent') {
   $query = 'SELECT v.id_venta AS venta,v.cod_nota AS nota, v.cod_factura AS fact,
   c.razon_social AS cli,cx.monto, CONCAT(vd.nombre_vendedor," ",vd.apellido_vendedor) AS vendedor,
    DATE_FORMAT(cx.fecha_cobro, "%d/%m/%Y")  AS cobro FROM pro_2cxc cx
    JOIN pro_2venta v ON cx.id_venta = v.id_venta JOIN pro_1cliente c ON cx.id_cliente = c.id_cliente
    JOIN pro_1vendedor vd ON c.id_vendedor = vd.id_vendedor
    WHERE estado = ? ORDER BY fecha_cobro DESC';

   $rs = prepareRS($conexion, $query, array('S'));
   resultResponse($rs, 'all');
}
