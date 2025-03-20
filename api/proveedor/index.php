<?php

require_once(__DIR__ . "/../../connections/db.php");
require_once __DIR__ . '/../../utils/php/utils.php';
require_once __DIR__ . '/../api_header.php';

if ($post['endpoint'] == 'getList') {
   $query = 'SELECT sig_idproveedor AS nac,id_proveedor AS id, razon_social AS razon,nombre_contacto AS contacto,
    email_proveedor AS email, dir_proveedor AS dir,cod_estado AS optestado,telf_proveedor AS tel
    FROM pro_1proveedor WHERE activo = ? ORDER BY razon_social ASC';
   $rs = prepareRS($conexion, $query, [1]);
   resultResponse($rs, 'all');
}

if ($post['endpoint'] == 'add') {

   $query = "INSERT INTO pro_1proveedor VALUES (?,?,?,?,?,?,?,?,?)";
   $params = [
      $post['nac'],
      $post['id'],
      $post['razon'],
      $post['contacto'],
      $post['email'],
      $post['tel'],
      $post['optestado'],
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
   $query = "UPDATE pro_1proveedor SET sig_idproveedor = ?, id_proveedor = ?,
   razon_social = ?,nombre_contacto = ?,email_proveedor = ?,telf_proveedor = ?,
   cod_estado = ?,dir_proveedor = ? WHERE CONCAT(sig_idproveedor,'-',id_proveedor) = ?";

   $oldId = $post['nac'] . "-" . $post['id'];
   $params = [
      $post['nac'],
      $post['id'],
      $post['razon'],
      $post['contacto'],
      $post['email'],
      $post['tel'],
      $post['optestado'],
      $post['dir'],
      $oldId,
   ];

   if (!prepareRS($conexion, $query, $params)):
      $error = $_SESSION['error'];
      unset($_SESSION['error']);
      responseJSON(['status' => 400, 'error' => $error]);
   else:
      responseJSON(['status' => 200, 'message' => 'Provedor Editado Correctamente']);
   endif;
}

if ($post['endpoint'] == 'delete') {
   $query = "DELETE FROM pro_1proveedor WHERE CONCAT(sig_idproveedor,'-',id_proveedor) IN ";
   $delItems = implode("','", $post['list']);
   $query .= "('{$delItems}')";

   if (!prepareRS($conexion, $query, [])):
      $error = $_SESSION['error'];
      unset($_SESSION['error']);
      responseJSON(['status' => 400, 'error' => $error]);
   else:
      responseJSON(['status' => 200, 'message' => 'Provedor(es) Borrados Correctamente']);
   endif;
}

if ($post['endpoint'] == 'getListOptionProveedor') {
   $lk = (!isset($post['lk'])) ? null : $post['lk'];
   $query = "SELECT id_proveedor AS id,razon_social AS text FROM pro_1proveedor WHERE activo = 1 AND CONCAT(id_proveedor,razon_social) LIKE '%" . $lk . "%' ORDER BY razon_social ASC";
   $rs = prepareRS($conexion, $query, []);
   echo ($rs->rowCount() > 0) ? json_encode($rs->fetchAll()) : json_encode(`<span>No se encontraron resultados</span>`);
}
