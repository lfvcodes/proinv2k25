<?php

/* Definir el nombre de la cookie del proyecto */
if (!defined('COOKIE_LOGIN')) {
  $encoded = base64_encode('proinv2K25');
  $encoded_without_padding = rtrim($encoded, '='); /* Elimina el padding de la cadena Base64 */
  define('COOKIE_LOGIN', $encoded_without_padding); /* <base64> Ej. 'ZGFjZS1yZWd1bGFyZXM' */
}

/* Definir si el servidor acepta una cookies seguras o no */
if (!defined('COOKIE_SECURE')) {
  define('COOKIE_SECURE', true); /* true o false */
}

/* Definir el código secreto del JWT */
if (!defined('JWT_SECRET')) {
  define('JWT_SECRET', 'c8f669e961b2a9263ebd22bd985f8dd541f3cd77e29c148'); /* <base64> Ej. 'SldUX1NFQ1JFVA0K' */
}

if (!defined('USER_ADMIN')) {
  define('USER_ADMIN', 'admin');
}

if (!defined('APP_PATH')) {
  define('APP_PATH', '../../');
}

if (!defined('VIEW_PATH')) {
  define('VIEW_PATH', '../');
}

$MODO = isset($_COOKIE['modo_estilo']) ? $_COOKIE['modo_estilo'] : 'claro';
