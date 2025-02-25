<?php
require_once(__DIR__ . "/../../connections/db.php");
require_once __DIR__ . '/../../utils/php/utils.php';
require_once __DIR__ . '/../api_header.php';

if ($post['endpoint'] == 'getList') {
  $query = 'SELECT c.id_cotizacion AS cod,c.* FROM pro_2cotizacion c';
  $rs = prepareRS($conexion, $query, []);
  resultResponse($rs, 'all');
}

if ($post['endpoint'] == 'getCotizacion') {
  $query = 'SELECT cl.razon_social,DATE(c.registro) AS fregistro,
  TIME_FORMAT(TIME(c.registro), "%H:%i") AS tregistro,
  DATE(c.fecha_cotizacion) AS fvencimiento,c.*
  FROM pro_2cotizacion c JOIN pro_1cliente cl ON c.id_cliente = cl.id_cliente
  WHERE c.id_cotizacion = ?';
  $rs = prepareRS($conexion, $query, [$post['idCot']]);
  $dataResponse =  $rs->fetch(PDO::FETCH_ASSOC);

  $queryDetail = 'SELECT * FROM pro_3dcotizacion dc
  JOIN pro_2producto p ON dc.cod_producto = p.cod_producto
  WHERE id_cotizacion = ?';
  $rsd = prepareRS($conexion, $queryDetail, [$post['idCot']]);
  $dataDetail = $rsd->fetchAll(PDO::FETCH_ASSOC);
  if (!empty($dataResponse)) {
    responseJSON([
      'status' => 200,
      'message' => 'Información encontrada exitosamente',
      'result' => [
        'cotizacion' => $dataResponse,
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

if ($post['endpoint'] == 'getDetail') {
  $query = 'SELECT cl.razon_social,DATE(c.registro) AS fregistro,
  TIME_FORMAT(TIME(c.registro), "%H:%i") AS tregistro,
  DATE(c.fecha_cotizacion) AS fvencimiento,c.*
  FROM pro_2cotizacion c JOIN pro_1cliente cl ON c.id_cliente = cl.id_cliente
  WHERE c.id_cotizacion = ?';
  $rs = prepareRS($conexion, $query, [$post['id']]);
  $dataResponse =  $rs->fetch(PDO::FETCH_ASSOC);

  $queryDetail = 'SELECT * FROM pro_3dcotizacion dc
  JOIN pro_2producto p ON dc.cod_producto = p.cod_producto
  WHERE id_cotizacion = ?';
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

if ($post['endpoint'] == 'getTasa') {
  $query = 'SELECT tasa FROM pro_4tasa WHERE 1 ORDER BY id_act DESC LIMIT 1';
  $rs = prepareRS($conexion, $query, []);
  resultResponse($rs, 'single');
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

  $query = 'CALL pro_5setCotizacion (?,?,?,?,?,?,?,?)';
  $rs = prepareRS($conexion, $query, $params);
  responseJSON($rs->fetch(PDO::FETCH_ASSOC));
  #setBitacora('COTIZACIONES', 'AGREGAR COTIZACION: ' . $post['fact'], $params, $_SESSION['pro']['usr']['user']);

}

if ($post['endpoint'] === 'update') {

  $end = sizeof($post['cant']);
  $params = array(
    $post['fregc'] . ' ' . $post['ftime'],
    $post['optcliente'],
    $post['desc'],
    $session['user'],
    $post['ncot'],
  );

  $queryUpdate = 'UPDATE pro_2cotizacion SET fecha_cotizacion = ?, id_cliente = ?, descripcion = ?, log_user = ? WHERE id_cotizacion = ?';
  $rs = prepareRS($conexion, $queryUpdate, $params);

  if (!$rs) {
    responseJSON(['status' => 400, 'message' => 'Error al intentar Modificar 1']);
  } else {

    if (!prepareRS($conexion, 'DELETE FROM pro_3dcotizacion WHERE id_cotizacion = ?', [$post['ncot']])) {
      responseJSON(['status' => 400, 'message' => 'Error al intentar Modificar2']);
    } else {
      $values = array();
      $params2 = array();
      $int_query = 'INSERT INTO pro_3dcotizacion (id_cotizacion,cod_producto,cant,monto) VALUES ';
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
        #setBitacora('COTIZACIONES', 'MODIFICAR COTIZACION: ' . $post['fact'], $params, $_SESSION['pro']['usr']['user']);
      } else {
        responseJSON(['status' => 200, 'message' => 'Cotizacion Modificada Correctamente']);
      }
    }
    #setBitacora('INVENTARIO','MODIFICAR PRODUCTO',$params,$_SESSION['pro']['usr']['user']);
  }
}

if ($post['endpoint'] === 'delete') {
  $query = "DELETE FROM pro_2cotizacion WHERE id_cotizacion IN ";
  $delItems = implode("','", $post['list']);
  $query .= "('{$delItems}')";

  if (!prepareRS($conexion, $query, [])):
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
    responseJSON(['status' => 400, 'error' => $error]);
  else:
    responseJSON(['status' => 200, 'message' => "Cotizacion(es) '{$delItems}' Borrada(s) Correctamente"]);
  #setBitacora('INVENTARIO','BORRAR PRODUCTO',$params,$_SESSION['pro']['usr']['user']);
  endif;
}
