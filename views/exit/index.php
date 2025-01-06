<?php
require_once __DIR__ . '/../../config.php';
if (isset($_COOKIE[COOKIE_LOGIN])) {
   unset($_COOKIE[COOKIE_LOGIN]);
   setcookie(COOKIE_LOGIN, '', time() - 3600, '/');
}
header('Location: ../login/');
