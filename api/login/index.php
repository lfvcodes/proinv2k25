<?php

require_once __DIR__ . "/../../connections/db.php";
require_once __DIR__ . '/../../utils/php/utils.php';
require_once __DIR__ . '/../api_header.php';

if ($post['endpoint'] === 'enter') {

  $usr = filter_var($post['log'], FILTER_SANITIZE_STRING);
  $psw = filter_var($post['pass'], FILTER_SANITIZE_STRING);
  $query = 'SELECT * FROM v_usuario WHERE log_user = ? AND activo = ? LIMIT 1';

  $rs = prepareRS($conexion, $query, [$usr, 'S']);

  if ($rs->rowCount() > 0) {
    $userData = $rs->fetch();
    $storedPsw = $userData['psw'];
    if (password_verify($psw, $storedPsw)) {
      unset($userData['psw']);
      $cookie = generateToken($userData);
      #setBitacora('LOGIN', "INICIAR SESION", array(), $row['log_user']);
      responseJSON(['status' => 200, 'message' => 'OK']);
    } else {
      responseJSON(['status' => 400, 'message' => 'Datos Incorrectos']);
    }
  } else {
    responseJSON(['status' => 400, 'message' => 'Datos Incorrectos']);
  }
}
