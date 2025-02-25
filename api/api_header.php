<?php
if ($_SERVER['CONTENT_TYPE'] === 'application/json; charset=UTF-8') {
   $_POST = json_decode(file_get_contents('php://input'), true);
} else {
   $_POST = $_REQUEST;
}

if (!isset($_POST) || empty($_POST) || !isset($_POST['endpoint'])):
   responseJSON(['status' => 400, 'error' => 'Metodo Incorrecto: ' . $_SERVER['CONTENT_TYPE']]);
endif;

$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'No referer';

$viewPort = explode("views/", $referer)[1];

if ($viewPort !== 'login/') {
   $session = verifySession();
   if (!$session) {
      responseJSON(['status' => 400, 'error' => 'Estimado usuario, su sesión ha expirado. Por favor, inicie sesión nuevamente.']);
   }
}

$post = filter_var_array($_POST, FILTER_SANITIZE_STRING);
$query = null;

function resultResponse($rs, $type)
{

   if ($type == 'single'):
      $dataResponse = $rs->fetch();
   else:
      $dataResponse = $rs->fetchAll();
   endif;

   if (!empty($dataResponse)):
      responseJSON([
         'status' => 200,
         'message' => 'Información encontrada exitosamente',
         'result' => $dataResponse
      ]);
   else:
      responseJSON([
         'status' => 400,
         'message' => 'No se encontraron Resultados ',
      ]);
   endif;
}
