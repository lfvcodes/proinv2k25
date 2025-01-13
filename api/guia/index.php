<?php
require_once(__DIR__ . "/../../connections/db.php");
require_once __DIR__ . '/../../utils/php/utils.php';
require_once __DIR__ . '/../api_header.php';

if ($post['endpoint'] == 'getList') {
  $query = 'SELECT id_guia AS cod,DATE(fecha_guia) AS fecha,i.id_conductor AS optcond,
  c.nombre_conductor AS conductor,i.id_vehiculo AS vehiculo,
  v.descripcion AS cvehiculo, log_user AS logu FROM pro_2guia i 
  JOIN pro_1conductor c ON i.id_conductor = c.id_conductor
  JOIN pro_1vehiculo v ON i.id_vehiculo = v.id_vehiculo
  WHERE ? ORDER BY registro DESC';
  $rs = prepareRS($conexion, $query, [1]);
  resultResponse($rs, 'all');
}

if ($post['endpoint'] == 'getListOptionDetail') {
  $query = "SELECT id_venta AS id, CONCAT( IF(ISNULL(v.cod_factura),CONCAT('N#',v.cod_nota),CONCAT('F#',v.cod_factura)),' - ',c.razon_social, ' (',e.nom_estado,')') AS text FROM pro_2venta v JOIN pro_1cliente c JOIN glo_1estado e ON c.cod_estado = e.cod_estado ON v.id_cliente = c.id_cliente WHERE ? ORDER BY v.id_venta DESC";
  $rs = prepareRS($conexion, $query, [1]);
  resultResponse($rs, 'all');
}

if ($post['endpoint'] == 'add') {

  $end = sizeof($post['vent']);

  $ext_query = 'INSERT INTO pro_2guia (id_guia,fecha_guia,id_conductor,id_vehiculo,log_user) VALUES (?,?,?,?,?)';
  $params = array(
    $post['nguia'],
    $post['freg'] . ' ' . $post['ftime'],
    $post['optcond'],
    $post['vehiculo'],
    $session['log_user']
  );


  if (!prepareRS($conexion, $ext_query, $params)):
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
    responseJSON(['status' => 400, 'error' => $error]);
  else:
    $values = $params2 = array();
    $int_query = 'INSERT INTO pro_3dguia (id_guia,id_venta) VALUES ';
    for ($i = 0; $i < $end; $i++) {
      $values[] = '(?,?)';
      $params2[] = $post['nguia'];
      $params2[] = $post['vent'][$i];
    }
    $valuesString = implode(',', $values);
    $int_query .= $valuesString;

    if (prepareRS($conexion, $int_query, $params2)) {
      responseJSON(['status' => 200, 'message' => "Guía de despacho Agregada Correctamente"]);
      #setBitacora('GUIAS','AGREGAR GUIA: '.$post['nguia'],$params,$_SESSION['pro']['usr']['user']);
    } else {
      $error = $_SESSION['error'];
      unset($_SESSION['error']);
      responseJSON(['status' => 400, 'error' => $error]);
    }
  endif;
}

if ($post['endpoint'] == 'update') {
  $query = "UPDATE pro_1vehiculo SET placa = ?, descripcion = ? WHERE id_vehiculo = ?";

  $params = [
    $post['placa'],
    $post['descripcion'],
    $post['idvehiculo'],
  ];

  if (!prepareRS($conexion, $query, $params)):
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
    responseJSON(['status' => 400, 'error' => $error]);
  else:
    responseJSON(['status' => 200, 'message' => 'Vehiculo(s) Editado(s) Correctamente ' . json_encode($post)]);
  endif;
}

if ($post['endpoint'] == 'delete') {
  $query = "DELETE FROM pro_2guia WHERE id_guia IN ";
  $delItems = implode("','", $post['list']);
  $query .= "('{$delItems}')";

  if (!prepareRS($conexion, $query, [])):
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
    responseJSON(['status' => 400, 'error' => $error]);
  else:
    responseJSON(['status' => 200, 'message' => 'Guia de Despacho Borrada Correctamente']);
  endif;
}
