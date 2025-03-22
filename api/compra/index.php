<?php
require_once(__DIR__ . "/../../connections/db.php");
require_once __DIR__ . '/../../utils/php/utils.php';
require_once __DIR__ . '/../api_header.php';

if ($post['endpoint'] == 'getList') {
  $query = 'SELECT v.id_pago AS cod,COALESCE(cod_factura,cod_nota) AS comprobante,tipo_compra,fecha_compra,id_proveedor,descripcion,forma_pago,tasa,log_user
  FROM pro_2compra v ORDER BY v.id_pago DESC';
  $rs = prepareRS($conexion, $query, []);
  resultResponse($rs, 'all');
}

if ($post['endpoint'] === 'getDetail') {

  $query = 'SELECT pv.razon_social,DATE(c.registro) AS fregistro,
  TIME_FORMAT(TIME(c.registro), "%H:%i") AS tregistro,
  c.fecha_compra AS freg,c.*
  FROM pro_2compra c JOIN pro_1proveedor pv ON c.id_proveedor = pv.id_proveedor
  WHERE c.id_pago = ?';
  $rs = prepareRS($conexion, $query, [$post['id']]);
  $dataResponse =  $rs->fetch(PDO::FETCH_ASSOC);

  $queryDetail = 'SELECT * FROM pro_3dcompra dc
  JOIN pro_2producto p ON dc.cod_producto = p.cod_producto
  WHERE dc.id_pago = ?';
  $rsd = prepareRS($conexion, $queryDetail, [$post['id']]);
  $dataDetail = $rsd->fetchAll(PDO::FETCH_ASSOC);

  if (!empty($dataResponse)) {
    responseJSON([
      'status' => 200,
      'message' => 'Información encontrada exitosamente',
      'result' => [
        'master' => $dataResponse,
        'detail' => $dataDetail
      ]
    ]);
  } else {
    responseJSON([
      'status' => 400,
      'message' => 'No se encontro la información',
    ]);
  }
}

if ($post['endpoint'] === 'setcompra' || $post['endpoint'] === 'add') {

  $params = array(
    $post['optprov'],
    $post['freg'] . $post['ftime'],
    $post['desc'],
    $post['fact'],
    $post['tasa'],
    $post['tcompra'],
    $session['user'],
    implode(",", $post['prod[]']) ?? $post['prod[]'],
    implode(",", $post['cant[]']) ?? $post['cant[]'],
    implode(",", $post['monto[]']) ?? $post['monto[]'],
    $post['flimite'] ?? null
  );

  responseJSON(['status' => 200, 'result' => $params]);

  $query = 'CALL pro_5setcompra (?,?,?,?,?,?,?,?,?,?,?)';
  $rs = prepareRS($conexion, $query, $params);
  if ($rs) {
    #setBitacora('compraS', 'AGREGAR Compra: ' . $post['fact'], $params, $session['user']);
    responseJSON($rs->fetch(PDO::FETCH_ASSOC));
  } else {
    // Manejar el error aquí, por ejemplo, mostrar un mensaje de error
    responseJSON(array('error' => $_SESSION['error']));
  }
}

if ($post['endpoint'] == 'getcompra') {
  $query = 'SELECT cl.razon_social,DATE(c.registro) AS fregistro,
  TIME_FORMAT(TIME(c.registro), "%H:%i") AS tregistro,
  DATE(c.fecha_compra) AS fvencimiento,c.*
  FROM pro_2compra c JOIN pro_1proveedor cl ON c.id_proveedor = cl.id_proveedor
  WHERE c.id_pago = ?';
  $rs = prepareRS($conexion, $query, [$post['cod']]);
  $dataResponse =  $rs->fetch(PDO::FETCH_ASSOC);

  $queryDetail = 'SELECT * FROM pro_3dcompra dc
  JOIN pro_2producto p ON dc.cod_producto = p.cod_producto
  WHERE id_pago = ?';
  $rsd = prepareRS($conexion, $queryDetail, [$post['cod']]);
  $dataDetail = $rsd->fetchAll(PDO::FETCH_ASSOC);
  if (!empty($dataResponse)) {
    responseJSON([
      'status' => 200,
      'message' => 'Información encontrada exitosamente',
      'result' => [
        'compra' => $dataResponse,
        'detail' => $dataDetail
      ]
    ]);
  } else {
    responseJSON([
      'status' => 400,
      'message' => 'No se encontro la información',
    ]);
  }
}

if ($post['endpoint'] === 'update') {

  $end = sizeof($post['cant']);
  $params = array(
    $post['fregc'] . ' ' . $post['ftime'],
    $post['optproveedor'],
    $post['desc'],
    $session['user'],
    $post['id'],
  );

  $queryUpdate = 'UPDATE pro_2compra SET fecha_compra = ?, id_proveedor = ?, descripcion = ?, log_user = ? WHERE id_compra = ?';
  $rs = prepareRS($conexion, $queryUpdate, $params);

  if (!$rs) {
    responseJSON(['status' => 400, 'message' => 'Error al intentar Modificar 1']);
  } else {

    if (!prepareRS($conexion, 'DELETE FROM pro_3dcompra WHERE id_compra = ?', [$post['ncot']])) {
      responseJSON(['status' => 400, 'message' => 'Error al intentar Modificar2']);
    } else {
      $values = array();
      $params2 = array();
      $int_query = 'INSERT INTO pro_3dcompra (id_compra,cod_producto,cant,monto) VALUES ';
      $mtotal = 0;
      for ($i = 0; $i < $end; $i++) {
        $values[] = '(?,?,?,?)';
        $params2[] = $post['ncot'];
        $params2[] = $post['prod'][$i];
        $params2[] = $post['cant'][$i];
        $params2[] = $post['monto'][$i];
        $mtotal += ($post['monto'][$i] * $post['cant'][$i]);
      }
      $valuesString = implode(',', $values);
      $int_query .= $valuesString;
      if (!prepareRS($conexion, $int_query, $params2)) {
        responseJSON(['status' => 400, 'message' => 'Error al intentar Modificar3']);
        #setBitacora('compraES', 'MODIFICAR compra: ' . $post['fact'], $params, $_SESSION['pro']['usr']['user']);
      } else {
        responseJSON(['status' => 200, 'message' => 'compra Modificada Correctamente']);
      }
    }
    #setBitacora('INcompraRIO','MODIFICAR PRODUCTO',$params,$_SESSION['pro']['usr']['user']);
  }
}

if ($post['endpoint'] === 'delete') {
  $query = "DELETE FROM pro_2compra WHERE id_compra IN ";
  $delItems = implode("','", $post['list']);
  $query .= "('{$delItems}')";

  if (!prepareRS($conexion, $query, [])):
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
    responseJSON(['status' => 400, 'error' => $error]);
  else:
    responseJSON(['status' => 200, 'message' => "compra(s) '{$delItems}' Borrada(s) Correctamente"]);
  #setBitacora('INcompraRIO','BORRAR PRODUCTO',$params,$_SESSION['pro']['usr']['user']);
  endif;
}

responseJSON(['status' => 400, 'message' => 'Endpoint not found']);
