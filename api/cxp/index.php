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

if ($post['endpoint'] === 'confirmCompra') {

  $params = array(
    $post['tasa'],
    $post['desc'],
    'S',
    $post['fcobro'],
    $post['id'],
  );

  $query = "CALL pro_5confirmarCxp (?,?,?,?,?)";
  $rs = prepareRS($conexion, $query, $params);
  responseJSON($rs->fetch(PDO::FETCH_ASSOC));
  #setBitacora('INVENTARIO','AGREGAR PRODUCTO',$params,$_SESSION['pro']['usr']['user']);
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

if ($post['endpoint'] === 'setAbono') {

  $query = 'DELETE FROM pro_3dcxp_abono WHERE id_cxp = ?';
  if (!prepareRS($conexion, $query, [$post['id']])):
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
    responseJSON(['status' => 400, 'error' => $error]);
  else:

    $params = $values = [];
    $query = 'REPLACE INTO pro_3dcxp_abono (id_abono,id_cxp,fecha_abono,monto_abono,concepto_abono) VALUES ';
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
        responseJSON(['status' => 200, 'message' => "Cuenta por Pagar Actualizada Correctamente"]);
      endif;
    } else {
      responseJSON(['status' => 200, 'message' => "Abono(s) de Cuenta(s) por Pagar Borrado(s) Correctamente"]);
    }
  endif;
}

if ($post['endpoint'] === 'getDetailAbono') {
  $query = 'SELECT * FROM pro_3dcxp_abono WHERE id_cxp = ? ORDER BY id_abono ASC';
  $rs = prepareRS($conexion, $query, [$post['id']]);
  resultResponse($rs, 'all');
}

if ($post['endpoint'] === 'delete') {

  $query = "DELETE FROM pro_2cxp WHERE id_cxp IN ";
  $delItems = implode("','", $post['list']);
  $query .= "('{$delItems}')";

  if (!prepareRS($conexion, $query, [])):
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
    responseJSON(['status' => 400, 'error' => $error]);
  else:
    responseJSON(['status' => 200, 'message' => "Cuentas por Pagar " . $post['list'] . " Borrada(s) Correctamente"]);
  #setBitacora('CXP','BORRAR CXP',$params,$_SESSION['pro']['usr']['user']);
  endif;
}
