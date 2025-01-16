<?php
require_once(__DIR__ . "/../../connections/db.php");
require_once __DIR__ . '/../../utils/php/utils.php';
require_once __DIR__ . '/../api_header.php';

if ($post['endpoint'] === 'getList') {
  $query = 'SELECT id_cxc AS cod,c.id_venta AS venta, r.cod_nota AS nota, monto,
   c.id_cliente AS idp, p.razon_social AS nom, concepto,
    fvencimiento AS fecha,DATE_FORMAT(fvencimiento, "%d/%m/%Y") AS fven,
     r.fecha_venta AS fechac,estado 
    FROM pro_2cxc c 
    JOIN pro_2venta r ON c.id_venta = r.id_venta
    JOIN pro_1cliente p ON c.id_cliente = p.id_cliente 
    WHERE estado <> ? ORDER BY id_cxc DESC';
  $rs = prepareRS($conexion, $query, ['S']);
  resultResponse($rs, 'all');
}

if ($post['endpoint'] === 'confirmVenta') {

  $params = array(
    $post['tasa'],
    $post['desc'],
    'S',
    $post['fcobro'],
    $post['id'],
  );

  $query = "CALL pro_5confirmarCxc (?,?,?,?,?)";
  $rs = prepareRS($conexion, $query, $params);
  responseJSON($rs->fetch(PDO::FETCH_ASSOC));
  #setBitacora('INVENTARIO','AGREGAR PRODUCTO',$params,$_SESSION['pro']['usr']['user']);
}

if ($post['endpoint'] === 'getCxcSolvent') {
  $query = 'SELECT v.id_venta AS venta,v.cod_nota AS nota, v.cod_factura AS fact,
  c.razon_social AS cli, cx.monto,
  DATE_FORMAT(cx.fecha_cobro, "%d/%m/%Y") AS cobro
  FROM pro_2cxc cx JOIN pro_2venta v ON cx.id_venta = v.id_venta 
  JOIN pro_1cliente c ON cx.id_cliente = c.id_cliente 
  WHERE estado = ? ORDER BY fecha_cobro DESC';
  $rs = prepareRS($conexion, $query, ['S']);
  resultResponse($rs, 'all');
}

if ($post['endpoint'] === 'revertCxcSolvent') {
  $query = 'UPDATE pro_2cxc SET estado = ?, fecha_cobro = ? WHERE id_venta = ?';
  $idventa = $post['idventa'];
  if (!prepareRS($conexion, $query, ['P', null, $idventa])):
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
    responseJSON(['status' => 400, 'error' => $error]);
  else:
    responseJSON(['status' => 200, 'message' => "Cuenta por Cobrar {$idventa} Revertida Correctamente"]);
  endif;
}

if ($post['endpoint'] === 'setAbono') {

  $query = 'DELETE FROM pro_3dcxc_abono WHERE id_cxc = ?';
  if (!prepareRS($conexion, $query, [$post['id']])):
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
    responseJSON(['status' => 400, 'error' => $error]);
  else:

    $params = $values = [];
    $query = 'REPLACE INTO pro_3dcxc_abono (id_abono,id_cxc,fecha_abono,monto_abono,concepto_abono) VALUES ';
    $end = sizeof($post['monto[]']);
    if ($end > 0 && !is_null($post['monto[]'])) {
      for ($i = 0; $i < $end; $i++) {
        $values[] = '(?,?,?,?,?)';
        $params[] = ($i + 1);
        $params[] = $post['id'];
        $params[] = ($end == 1) ? $post['fec[]'] : $post['fec[]'][$i];
        $params[] = ($end == 1) ? $post['monto[]'] : $post['monto[]'][$i];
        $params[] = ($end == 1) ? $post['concepto[]'] : $post['concepto[]'][$i];
      }
      $valuesString = implode(',', $values);
      $query .= $valuesString;

      if (!prepareRS($conexion, $query, $params)):
        $error = $_SESSION['error'];
        unset($_SESSION['error']);
        responseJSON(['status' => 400, 'error' => $error]);
      else:
        responseJSON(['status' => 200, 'message' => "Cuenta por Cobrar Actualizada Correctamente"]);
      endif;
    } else {
      responseJSON(['status' => 200, 'message' => "Abono(s) de Cuenta(s) por Cobrar Borrado(s) Correctamente"]);
    }
  endif;
}

if ($post['endpoint'] === 'getDetailAbono') {
  $query = 'SELECT * FROM pro_3dcxc_abono WHERE id_cxc = ? ORDER BY id_abono ASC';
  $rs = prepareRS($conexion, $query, [$post['id']]);
  resultResponse($rs, 'all');
}

if ($post['endpoint'] === 'delete') {

  $query = "DELETE FROM pro_2cxc WHERE id_cxc IN ";
  $delItems = implode("','", $post['list']);
  $query .= "('{$delItems}')";

  if (!prepareRS($conexion, $query, [])):
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
    responseJSON(['status' => 400, 'error' => $error]);
  else:
    responseJSON(['status' => 200, 'message' => "Cuentas por Cobrar " . $post['list'] . " Borrada(s) Correctamente"]);
  #setBitacora('CXc','BORRAR CXc',$params,$_SESSION['pro']['usr']['user']);
  endif;
}
