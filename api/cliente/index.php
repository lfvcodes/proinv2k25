<?php

require_once(__DIR__ . "/../../connections/db.php");
require_once __DIR__ . '/../../utils/php/utils.php';
require_once __DIR__ . '/../api_header.php';

if ($post['endpoint'] == 'getList') {
   $query = 'SELECT sig_idcliente AS nac, c.id_cliente AS id, razon_social AS razon, nom_contacto AS cont,
   c.cod_estado AS optestado,dir_cliente AS dir, email_cliente AS email, telf_cliente AS tel,
    CONCAT(v.nombre_vendedor," ",v.apellido_vendedor) AS vend, v.id_vendedor AS optvendedor
    FROM pro_1cliente c
    LEFT JOIN pro_1vendedor v ON c.id_vendedor = v.id_vendedor
   WHERE ? AND c.activo = 1 ORDER BY razon_social ASC';

   $rs = prepareRS($conexion, $query, [1]);
   resultResponse($rs, 'all');
}

if ($post['endpoint'] == 'getOptionEstado') {
   $query = 'SELECT cod_estado AS id, 
   nom_estado AS nombre FROM glo_1estado WHERE ? ORDER BY cod_estado ASC';
   $rs = prepareRS($conexion, $query, [1]);
   resultResponse($rs, 'all');
}

if ($post['endpoint'] == 'add') {

   $query = "INSERT INTO pro_1cliente VALUES (?,?,?,?,?,?,?,?,?,?)";
   $params = [
      $post['nac'],
      $post['id'],
      $post['optvendedor'],
      $post['razon'],
      $post['contacto'],
      $post['optestado'],
      $post['email'],
      $post['tel'],
      $post['dir'],
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
   $query = "UPDATE pro_1cliente SET sig_idcliente = ?, id_cliente = ?,
   id_vendedor = ?, razon_social = ?,nom_contacto = ?,cod_estado = ?,
   email_cliente = ?,telf_cliente = ?,dir_cliente = ? 
   WHERE CONCAT(sig_idcliente,'-',id_cliente) = ?";

   $oldId = $post['nac'] . "-" . $post['id'];
   $params = [
      $post['nac'],
      $post['id'],
      $post['optvendedor'],
      $post['razon'],
      $post['contacto'],
      $post['optestado'],
      $post['email'],
      $post['tel'],
      $post['dir'],
      $oldId
   ];
   if (!prepareRS($conexion, $query, $params)):
      $error = $_SESSION['error'];
      unset($_SESSION['error']);
      responseJSON(['status' => 400, 'error' => $error]);
   else:
      responseJSON(['status' => 200, 'message' => 'Cliente Editado Correctamente']);
   endif;
}

if ($post['endpoint'] == 'delete') {
   $query = "DELETE FROM pro_1cliente WHERE CONCAT(sig_idcliente,'-',id_cliente) IN ";
   $delItems = implode("','", $post['list']);
   $query .= "('{$delItems}')";

   if (!prepareRS($conexion, $query, [])):
      $error = $_SESSION['error'];
      unset($_SESSION['error']);
      responseJSON(['status' => 400, 'error' => $error]);
   else:
      responseJSON(['status' => 200, 'message' => 'Cliente(es) Borrados Correctamente']);
   endif;
}
