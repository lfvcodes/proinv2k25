<?php

require_once(__DIR__ . "/../../connections/db.php");
require_once __DIR__ . '/../../utils/php/utils.php';
require_once __DIR__ . '/../api_header.php';

if ($post['endpoint'] == 'getList') {
  $query = 'SELECT sig_idconductor AS nac,id_conductor AS id, 
   nombre_conductor AS nom,apellido_conductor AS ape,
    dir_conductor AS dir,email_conductor AS email,telf_conductor AS tel
    FROM pro_1conductor WHERE activo = ? ORDER BY nombre_conductor,apellido_conductor ASC';
  $rs = prepareRS($conexion, $query, [1]);
  resultResponse($rs, 'all');
}

if ($post['endpoint'] == 'getOptionConductor') {
  $query = 'SELECT id_conductor AS id, 
  CONCAT(nombre_conductor," ",apellido_conductor) AS nombre
   FROM pro_1conductor WHERE activo = ? ORDER BY nombre_conductor,apellido_conductor ASC';
  $rs = prepareRS($conexion, $query, [1]);
  resultResponse($rs, 'all');
}

if ($post['endpoint'] == 'add') {

  $query = "INSERT INTO pro_1conductor VALUES (?,?,?,?,?,?,?,?)";
  $params = [
    $post['nac'],
    $post['id'],
    $post['nom'],
    $post['ape'],
    $post['dir'],
    $post['email'],
    $post['tel'],
    1
  ];

  if (!prepareRS($conexion, $query, $params)):
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
    responseJSON(['status' => 400, 'error' => $error]);
  else:
    responseJSON(['status' => 200, 'message' => "Se ha Guardado el Registro {$post['id']} Correctamente"]);
  endif;
}

if ($post['endpoint'] == 'update') {
  $query = "UPDATE pro_1conductor SET sig_idconductor = ?, id_conductor = ?,
   nombre_conductor = ?,apellido_conductor = ?,dir_conductor = ?,email_conductor = ?,
   telf_conductor = ? WHERE CONCAT(sig_idconductor,'-',id_conductor) = ?";

  $params = [
    $post['nac'],
    $post['id'],
    $post['nom'],
    $post['ape'],
    $post['dir'],
    $post['email'],
    $post['tel'],
    $post['oldId'],
  ];

  if (!prepareRS($conexion, $query, $params)):
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
    responseJSON(['status' => 400, 'error' => $error]);
  else:
    responseJSON(['status' => 200, 'message' => 'Conductor(es) Editado(s) Correctamente']);
  endif;
}

if ($post['endpoint'] == 'delete') {
  $query = "DELETE FROM pro_1conductor WHERE CONCAT(sig_idconductor,'-',id_conductor) IN ";
  $delItems = implode("','", $post['list']);
  $query .= "('{$delItems}')";

  if (!prepareRS($conexion, $query, [])):
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
    responseJSON(['status' => 400, 'error' => $error]);
  else:
    responseJSON(['status' => 200, 'message' => 'Conductor(es) Borrado(s) Correctamente']);
  endif;
}
