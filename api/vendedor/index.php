<?php

require_once(__DIR__ . "/../../connections/db.php");
require_once __DIR__ . '/../../utils/php/utils.php';
require_once __DIR__ . '/../api_header.php';

if ($post['endpoint'] == 'getList') {
   $query = 'SELECT sig_idvendedor AS nac,id_vendedor AS id, 
   nombre_vendedor AS nom,apellido_vendedor AS ape,
    cod_estado AS optestado,dir_vendedor AS dir,email_vendedor AS email,telf_vendedor AS tel
    FROM pro_1vendedor WHERE activo = ? ORDER BY nombre_vendedor,apellido_vendedor ASC';
   $rs = prepareRS($conexion, $query, [1]);
   resultResponse($rs, 'all');
}

if ($post['endpoint'] == 'getOptionEstado') {
   $query = 'SELECT cod_estado AS id, 
   nom_estado AS nombre FROM glo_1estado WHERE ? ORDER BY cod_estado ASC';
   $rs = prepareRS($conexion, $query, [1]);
   resultResponse($rs, 'all');
}

if ($post['endpoint'] == 'getOption') {
   $query = 'SELECT id_vendedor AS id, 
   CONCAT(nombre_vendedor," ",apellido_vendedor) AS nombre
    FROM pro_1vendedor WHERE activo = ? ORDER BY nombre_vendedor,apellido_vendedor ASC';
   $rs = prepareRS($conexion, $query, [1]);
   resultResponse($rs, 'all');
}

if ($post['endpoint'] == 'add') {

   $query = "INSERT INTO pro_1vendedor VALUES (?,?,?,?,?,?,?,?,?)";
   $params = [
      $post['nac'],
      $post['id'],
      $post['nom'],
      $post['ape'],
      $post['optestado'],
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
   $query = "UPDATE pro_1vendedor SET sig_idvendedor = ?, id_vendedor = ?,
   nombre_vendedor = ?,apellido_vendedor = ?,cod_estado = ?,dir_vendedor = ?,email_vendedor = ?,
   telf_vendedor = ? WHERE CONCAT(sig_idvendedor,'-',id_vendedor) = ?";

   $oldId = $post['nac'] . "-" . $post['id'];
   $params = [
      $post['nac'],
      $post['id'],
      $post['nom'],
      $post['ape'],
      $post['optestado'],
      $post['dir'],
      $post['email'],
      $post['tel'],
      $oldId,
   ];

   if (!prepareRS($conexion, $query, $params)):
      $error = $_SESSION['error'];
      unset($_SESSION['error']);
      responseJSON(['status' => 400, 'error' => $error]);
   else:
      responseJSON(['status' => 200, 'message' => 'Vendedor Editado Correctamente']);
   endif;
}

if ($post['endpoint'] == 'delete') {
   $query = "DELETE FROM pro_1vendedor WHERE CONCAT(sig_idvendedor,'-',id_vendedor) IN ";
   $delItems = implode("','", $post['list']);
   $query .= "('{$delItems}')";

   if (!prepareRS($conexion, $query, [])):
      $error = $_SESSION['error'];
      unset($_SESSION['error']);
      responseJSON(['status' => 400, 'error' => $error]);
   else:
      responseJSON(['status' => 200, 'message' => 'Vendedor(es) Borrado(s) Correctamente']);
   endif;
}
