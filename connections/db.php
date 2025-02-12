<?php
$conexion = null;
$dsn = "mysql:host=localhost;dbname=bd_proinv2k25;charset=utf8";
$optionsPdo = [
  PDO::ATTR_EMULATE_PREPARES   => false,
  PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
  $conexion = new PDO($dsn, 'root', '12345678', $optionsPdo);
} catch (PDOException $e) {
  echo "Error de Conexion: " . $e->getMessage();
}

function prepareRS($conexion, $query, $params, $lastInsert = false)
{
  try {
    $statment = $conexion->prepare($query);
    $statment->execute($params);
    $result = $statment;
    $statment = null;
    return $result;
  } catch (PDOException $e) {
    $_SESSION['error'] = $e->getMessage();
    return false;
  }
}
