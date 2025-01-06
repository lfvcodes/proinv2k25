<?php
require_once __DIR__ . "/../../connections/db.php";
require_once __DIR__ . '/../../utils/php/utils.php';
require_once __DIR__ . '/../api_header.php';

if ($post['endpoint'] === 'refresh') {
   $currentUser = verifySession();

   if (!$currentUser) {
      responseJSON([
         'status' => 404,
         'error' => 'No se pudo actualizar la sesión'
      ]);
   } else {
      responseJSON([
         'status' => 200,
         'message' => 'Se actualizó con éxito la sesión',
         'time' => $currentUser['exp'] - time()
      ]);
   }
}
