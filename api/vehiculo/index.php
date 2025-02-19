<?php

require_once(__DIR__ . "/../../connections/db.php");
require_once __DIR__ . '/../../utils/php/utils.php';
require_once __DIR__ . '/../api_header.php';

if ($post['endpoint'] == 'getList') {
  $query = 'SELECT id_vehiculo AS idvehiculo, placa,descripcion FROM pro_1vehiculo WHERE activo = ? ORDER BY id_vehiculo ASC';
  $rs = prepareRS($conexion, $query, ['S']);
  resultResponse($rs, 'all');
}

if ($post['endpoint'] == 'getOptionVehiculo') {
  $query = 'SELECT id_vehiculo AS id,descripcion AS nombre 
  FROM pro_1vehiculo WHERE activo = ? ORDER BY descripcion ASC';
  $rs = prepareRS($conexion, $query, ['S']);
  resultResponse($rs, 'all');
}

if ($post['endpoint'] == 'add') {

  $query = "INSERT INTO pro_1vehiculo (placa,descripcion) VALUES (?,?)";
  $params = [
    $post['placa'],
    $post['descripcion'],
  ];

  if (!prepareRS($conexion, $query, $params)):
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
    responseJSON(['status' => 400, 'error' => $error]);
  else:
    responseJSON(['status' => 200, 'message' => "Se ha Guardado el Registro de Vehiculo {$post['placa']} Correctamente"]);
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
    responseJSON(['status' => 200, 'message' => 'Vehiculo(s) Editado(s) Correctamente']);
  endif;
}

if ($post['endpoint'] == 'delete') {
  $query = "DELETE FROM pro_1vehiculo WHERE id_vehiculo IN ";
  $delItems = implode("','", $post['list']);
  $query .= "('{$delItems}')";

  if (!prepareRS($conexion, $query, [])):
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
    responseJSON(['status' => 400, 'error' => $error]);
  else:
    responseJSON(['status' => 200, 'message' => 'Vehiculo(s) Borrado(s) Correctamente']);
  endif;
}
