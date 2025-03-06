<?php
require_once(__DIR__ . "/../../connections/db.php");
require_once __DIR__ . '/../../utils/php/utils.php';
require_once __DIR__ . '/../api_header.php';

if ($post['endpoint'] == 'getList') {
  $query = 'SELECT v.id_venta AS cod,v.* FROM pro_2venta v ORDER BY v.id_venta DESC';
  $rs = prepareRS($conexion, $query, []);
  resultResponse($rs, 'all');
}

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

if ($post['endpoint'] == 'getVenta') {
  $query = 'SELECT cl.razon_social,DATE(c.registro) AS fregistro,
  TIME_FORMAT(TIME(c.registro), "%H:%i") AS tregistro,
  DATE(c.fecha_venta) AS fvencimiento,c.*
  FROM pro_2venta c JOIN pro_1cliente cl ON c.id_cliente = cl.id_cliente
  WHERE c.id_venta = ?';
  $rs = prepareRS($conexion, $query, [$post['cod']]);
  $dataResponse =  $rs->fetch(PDO::FETCH_ASSOC);

  $queryDetail = 'SELECT * FROM pro_3dventa dc
  JOIN pro_2producto p ON dc.cod_producto = p.cod_producto
  WHERE id_venta = ?';
  $rsd = prepareRS($conexion, $queryDetail, [$post['cod']]);
  $dataDetail = $rsd->fetchAll(PDO::FETCH_ASSOC);
  if (!empty($dataResponse)) {
    responseJSON([
      'status' => 200,
      'message' => 'Información encontrada exitosamente',
      'result' => [
        'venta' => $dataResponse,
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
    $post['optcliente'],
    $post['desc'],
    $session['user'],
    $post['id'],
  );

  $queryUpdate = 'UPDATE pro_2venta SET fecha_venta = ?, id_cliente = ?, descripcion = ?, log_user = ? WHERE id_venta = ?';
  $rs = prepareRS($conexion, $queryUpdate, $params);

  if (!$rs) {
    responseJSON(['status' => 400, 'message' => 'Error al intentar Modificar 1']);
  } else {

    if (!prepareRS($conexion, 'DELETE FROM pro_3dventa WHERE id_venta = ?', [$post['ncot']])) {
      responseJSON(['status' => 400, 'message' => 'Error al intentar Modificar2']);
    } else {
      $values = array();
      $params2 = array();
      $int_query = 'INSERT INTO pro_3dventa (id_venta,cod_producto,cant,monto) VALUES ';
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
        #setBitacora('ventaES', 'MODIFICAR venta: ' . $post['fact'], $params, $_SESSION['pro']['usr']['user']);
      } else {
        responseJSON(['status' => 200, 'message' => 'venta Modificada Correctamente']);
      }
    }
    #setBitacora('INVENTARIO','MODIFICAR PRODUCTO',$params,$_SESSION['pro']['usr']['user']);
  }
}

if ($post['endpoint'] === 'delete') {
  $query = "DELETE FROM pro_2venta WHERE id_venta IN ";
  $delItems = implode("','", $post['list']);
  $query .= "('{$delItems}')";

  if (!prepareRS($conexion, $query, [])):
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
    responseJSON(['status' => 400, 'error' => $error]);
  else:
    responseJSON(['status' => 200, 'message' => "Venta(s) '{$delItems}' Borrada(s) Correctamente"]);
  #setBitacora('INVENTARIO','BORRAR PRODUCTO',$params,$_SESSION['pro']['usr']['user']);
  endif;
}

responseJSON(['status' => 400, 'message' => 'Endpoint not found']);
