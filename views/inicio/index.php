<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../utils/php/utils.php';
$session = verifySession();
if (!$session) {
  header("Location: ../login/");
}

$title = 'Inicio';

require_once __DIR__ . '/../../includes/head.php';
require_once __DIR__ . '/../../includes/header.php';

if ($session['nivel'] === 1):
  include_once 'adminPanel.php';
else:
  echo '<img class="img-fluid" src="../../assets/img/logo.png">';
endif;

require_once __DIR__ . '/../../includes/footer.php';
