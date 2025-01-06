<?php
function importmapToArray($dir, $dirDOM, &$arr = [], $subfolder = '')
{
  $dirs = scandir($dir);
  unset($dirs[0]);
  unset($dirs[1]);
  foreach ($dirs as $value) {
    $valueAux = str_replace('.mjs', '', $value);
    $valueAux = str_replace('.js', '', $valueAux);
    if (preg_match('/\.(m)?js$/i', $value)) {
      $arr["@$subfolder$valueAux"] = $dirDOM . $value;
    } else if (is_dir($dir . $value)) {
      $exist = importmapToArray($dir . $value . '/', $dirDOM, $value . '/', $arr, $value . '/');
      if ($exist) {
        $arr = array_merge($arr, $exist);
      }
    }
  }

  if (count($arr) === 0) {
    return false;
  }

  return $arr;
}

function importmap($dir, $dirDOM, $version = false)
{
  $arr = importmapToArray($dir, $dirDOM);
  $arrAux = [];
  foreach ($arr as $key => $value) {
    $arrAux[] = '"' . $key . '":"' . $value . ($version ? "?version=$version" : '') . '"';
  }
  $text = '<script type="importmap">{"imports":{' . join(',', $arrAux) . '}}</script>';
  echo $text;
}
