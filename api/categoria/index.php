<?php

require_once(__DIR__ . "/../../connections/db.php");
require_once __DIR__ . '/../../utils/php/utils.php';
require_once __DIR__ . '/../api_header.php';

if ($post['endpoint'] == 'getList') {
  $query = 'SELECT c.id_categoria AS id,c.nom_categoria AS nom
    FROM pro_1categoria c
   WHERE c.activo = ? ORDER BY c.nom_categoria ASC';

  $rs = prepareRS($conexion, $query, ['S']);
  resultResponse($rs, 'all');
}

if ($post['endpoint'] == 'add') {

  $query = "INSERT INTO pro_1categoria VALUES (?,?)";
  $params = [
    $post['nom'],
    'S'
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
  $query = "UPDATE pro_1categoria SET id_categoria = ?,nom_categoria = ? WHERE id_categoria = ?";

  $params = [
    $post['id'],
    $post['nom'],
    $post['oldId']
  ];
  if (!prepareRS($conexion, $query, $params)):
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
    responseJSON(['status' => 400, 'error' => $error]);
  else:
    responseJSON(['status' => 200, 'message' => 'Categoria(s) Editada(s) Correctamente']);
  endif;
}

if ($post['endpoint'] == 'delete') {
  $query = "DELETE FROM pro_1categoria WHERE id_categoria IN ";
  $delItems = implode("','", $post['list']);
  $query .= "('{$delItems}')";

  if (!prepareRS($conexion, $query, [])):
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
    responseJSON(['status' => 400, 'error' => $error]);
  else:
    responseJSON(['status' => 200, 'message' => 'Categoria(as) Borrada(s) Correctamente']);
  endif;
}