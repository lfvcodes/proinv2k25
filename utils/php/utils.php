<?php

include_once 'importModals.php';

require_once __DIR__ . '/../../libraries/php/php-jwt/php-jwt.php';
require_once __DIR__ . '/../../config.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

/* generamos el token de la sesión del usuario */

function generateToken($user)
{
  /* Calcula la marca de tiempo para 1 hora en el futuro */
  $time_iat = time();
  $time_exp = $time_iat + (60 * 60);

  $cook = array(
    'iat' => $time_iat,
    'exp' => $time_exp
  );

  $payload = $cook + $user;

  $token = JWT::encode($payload, JWT_SECRET, 'HS256');

  /* Eliminar la cookie existente si está configurada */
  if (isset($_COOKIE[COOKIE_LOGIN])) {
    unset($_COOKIE[COOKIE_LOGIN]);
    setcookie(COOKIE_LOGIN, '', $time_exp, '/');
  }

  if (defined('COOKIE_SECURE') && COOKIE_SECURE) {
    setcookie(
      COOKIE_LOGIN,
      $token,
      array(
        'expires' => $time_exp,
        'path' => '/',
        'secure' => true,     /* or false */
        'httponly' => true,    /* or false */
        'samesite' => 'Strict' /* None || Lax  || Strict */
      )
    );
  } else {
    setcookie(
      COOKIE_LOGIN,
      $token,
      $time_exp,
      '/',
      '',
      false,
      true
    );
  }
  return $token;
}

/*
verificamos si la sesión del usuario esta activa y en caso de ser asi,
regeneramos el token para actualizar el tiempo de expiracion
*/

function verifySession()
{
  if (isset($_COOKIE[COOKIE_LOGIN])) {
    $token = $_COOKIE[COOKIE_LOGIN];

    try {
      $decoded = JWT::decode($token, new Key(JWT_SECRET, 'HS256'));

      /* Verificar si el token ha expirado */
      if ($decoded->exp < time()) {
        /* Token expirado */
        return false;
      }

      /* generamos un nuevo token para dar continuidad a la sesión del usuario */
      $data = (array) $decoded;
      $newToken = generateToken($data);
      $data = JWT::decode($newToken, new Key(JWT_SECRET, 'HS256'));
      return (array) $data; /* La sesión es válida */
    } catch (Exception $e) {
      /* Error al decodificar el token, sesión inválida */
      return false;
    }
  }

  return false; /* La cookie no está presente, sesión no válida */
}

function responseJSON($responseArr)
{
  $responseArr['status'] = (int) $responseArr['status'];
  if (!$responseArr['status']) {
    $reponseArr['status'] = 500;
  }
  ob_end_clean();
  header('Content-type: application/json; charset=utf-8');
  echo json_encode($responseArr, http_response_code($responseArr['status']));
  exit();
}

function copyFile($from, $to)
{
  if (copy($from, $to)) {
    return true;
  } else {
    return false;
  }
}

function dateDifferences($fi, $ff)
{
  $diferencia = strtotime($ff) - strtotime($fi);
  $nd = floor($diferencia / (60 * 60 * 24));
  return $nd;
}

function ExtractValues​​ExcludingKeys($OriginalArray, $excludeKeys)
{
  $resultArray = [];

  foreach ($OriginalArray as $clave => $valor) {
    if (!in_array($clave, $excludeKeys)) {
      $resultArray[$clave] = $valor;
    }
  }

  return $resultArray;
}

function HexToRgb($color)
{
  $color = ltrim($color, '#'); // Elimina el símbolo '#' si está presente
  $rgb = [];

  if (strlen($color) == 3) {
    // Convierte el formato corto de 3 caracteres a formato completo de 6 caracteres
    $color = str_repeat(substr($color, 0, 1), 2) . str_repeat(substr($color, 1, 1), 2) . str_repeat(substr($color, 2, 1), 2);
  }

  if (strlen($color) == 6) {
    // Divide el color en componentes rojo, verde y azul
    $rgb['red'] = hexdec(substr($color, 0, 2));
    $rgb['green'] = hexdec(substr($color, 2, 2));
    $rgb['blue'] = hexdec(substr($color, 4, 2));
  }

  return $rgb;
}

function getNameMonth($n)
{
  $tmes = '';
  switch ($n) {
    case '1':
      $tmes = 'ENERO';
      break;
    case '2':
      $tmes = 'FEBRERO';
      break;
    case '3':
      $tmes = 'MARZO';
      break;
    case '4':
      $tmes = 'ABRIL';
      break;
    case '5':
      $tmes = 'MAYO';
      break;
    case '6':
      $tmes = 'JUNIO';
      break;
    case '7':
      $tmes = 'JULIO';
      break;
    case '8':
      $tmes = 'AGOSTO';
      break;
    case '9':
      $tmes = 'SEPTIEMBRE';
      break;
    case '10':
      $tmes = 'OCTUBRE';
      break;
    case '11':
      $tmes = 'NOVIEMBRE';
      break;
    case '12':
      $tmes = 'DICIEMBRE';
      break;
    default:
      break;
  }
  return $tmes;
}

function getFirstAndEndDate($mes)
{
  $yearActual = date('Y');
  $fDay = new DateTime($yearActual . '-' . $mes . '-01');
  $lDay = new DateTime($fDay->format('Y-m-t'));
  return array(
    'initDate' => $fDay->format('Y-m-d'),
    'lastDate' => $lDay->format('Y-m-d')
  );
}

function displayAlert()
{
  if (isset($_SESSION['pro_alert'])):
    $str = (string) $_SESSION['pro_alert'];
    eval($str);
    unset($_SESSION['pro_alert']);
  endif;
}

function alert($type, $text)
{

  $altype = '';
  switch ($type) {
    case 'success':
      $altype = 'alert-success';
      break;
    case 'warning':
      $altype = 'alert-warning';
      break;
    case 'info':
      $altype = 'alert-info';
      break;
    default:
      break;
  }

  print '
		<div onclick="$(this).remove();"
		 class="mt-2 alert ' . $altype . ' text-dark" role="alert">
		 	<div class="row">
				<span class="col">' . $text . '</span>
				<span class="col text-end"><i class="text-dark bx-sm bi bi-x"></i></span>
			</div>
		</div>
	';
}

function setBitacora($conexion, $modulo, $accion, $params, $usr)
{
  if (is_null($conexion)) {
    require_once __DIR__ . '/../../connections/db.php';
  }
  $query = 'INSERT INTO pro_bitacora.pro_2bitacora (modulo,accion,params,log_usuario) VALUES (?,?,?,?)';
  $p = implode(",", $params);
  prepareRS($conexion, $query, array($modulo, $accion, $p, $usr));
}

function reducirNombres($nombreCompleto)
{
  $nombres = explode(" / ", $nombreCompleto); // Dividir los nombres por " / "

  foreach ($nombres as &$nombre) {
    $partes = explode(" ", $nombre); // Dividir el nombre en partes

    if (count($partes) > 2) {
      $nombre = $partes[0] . " " . $partes[count($partes) - 1]; // Tomar el primer nombre y el último apellido
    }
  }

  return implode(" / ", $nombres); // Unir los nombres reducidos con " / "
}
