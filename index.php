<?php
require_once 'utils/php/utils.php';

$session = verifySession();
if (!$session) {
    header('Location: views/login/');
} else {
    header('Location: views/inicio/');
}
