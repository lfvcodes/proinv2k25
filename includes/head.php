<?php
require_once __DIR__ . '/../utils/php/importmap.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="Content-Type" content="text/html;" charset="utf-8" />
  <meta charset="UTF-8">
  <meta http-equiv="content-language" content="es">
  <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7,IE=8,IE=9,IE=10,IE=11" />
  <meta name="viewport"
    content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
  <meta name="author" content="VELFCODES" />

  <link rel="icon" type="image/png" href="<?= APP_PATH; ?>assets/img/logo.png?r=<?= substr(rand(), 0, 4); ?>">
  <link rel="stylesheet" href="<?= APP_PATH; ?>assets/fonts/boxicons.css" />

  <?php if ($MODO === 'claro'): ?>
    <link rel="stylesheet" href="<?= APP_PATH; ?>assets/css/stylel.css?r=<?= substr(rand(), 0, 4); ?>"
      class="template-customizer-core-css" />
  <?php else: ?>
    <link rel=" stylesheet" href="<?= APP_PATH; ?>assets/css/styled.css?r=<?= substr(rand(), 0, 4); ?>"
      class="template-customizer-core-css" />
  <?php endif; ?>

  <link rel="stylesheet" href="<?= APP_PATH; ?>assets/css/config.css?r=<?= substr(rand(), 0, 4); ?>" />

  <link rel="stylesheet" href="<?= APP_PATH; ?>assets/css/bundle.base.css">
  <link rel="stylesheet" href="<?= APP_PATH; ?>libraries/js/bootstrap/bi.css" />

  <?php if ($title !== 'Login' || $title !== 'Inicio'): ?>

    <link rel="stylesheet"
      href="<?= APP_PATH; ?>libraries/js/dataTables/dataTables.min.css" />

    <link rel="stylesheet"
      href="<?= APP_PATH; ?>libraries/js/dataTables/dataTables.bootstrap5.min.css" />

    <link rel="stylesheet"
      href="<?= APP_PATH; ?>libraries/js/dataTables/buttons.bootstrap5.min.css" />

    <link rel="stylesheet"
      href="<?= APP_PATH; ?>libraries/js/dataTables/responsive.bootstrap5.min.css" />

    <link rel="stylesheet" href="<?= APP_PATH; ?>libraries/js/select2/select2.min.css" />
    <link rel="stylesheet"
      href="<?= APP_PATH; ?>libraries/js/select2/select2.b5theme.css" />
  <?php endif; ?>

  <title>ProInv | <?= $title ?></title>
  <?php
  $jsFolder = __DIR__ . '/../utils/js/';
  $jsFolderDOM = '../../utils/js/';
  importmap($jsFolder, $jsFolderDOM, substr(rand(), 0, 4));
  ?>
</head>

<body class="sidebar-icon-only theme-<?= ($MODO == 'oscuro') ? 'dark' : 'light' ?>">