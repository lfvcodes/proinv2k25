<?php
require_once(__DIR__ . "/../../connections/db.php");
require_once __DIR__ . '/../../utils/php/utils.php';
require_once __DIR__ . '/../api_header.php';

if ($post['endpoint'] == 'getList') {
  $query = 'SELECT * FROM pro_v_inventario';
  $rs = prepareRS($conexion, $query, []);
  resultResponse($rs, 'all');
}

if ($post['endpoint'] === 'add') {

  $query = "CALL pro_5setProducto (?,?,?,?,?,?,?,?,?,?,?)";
  $params = [
    strtoupper($post['cod_product']),
    !empty($post['cod_alt']) ? strtoupper($post['cod_alt']) : null,
    strtoupper($post['nom_product']),
    strtoupper($post['desc_product']),
    $post['optgrupo'],
    $post['stockminimo'],
    $post['stockmaximo'],
    $post['pcosto'],
    $post['pventa'],
    $post['umedida'],
    $post['stock']
  ];

  $rs = prepareRS($conexion, $query, $params);
  responseJSON($rs->fetch(PDO::FETCH_ASSOC));
  #setBitacora('INVENTARIO','AGREGAR PRODUCTO',$params,$_SESSION['pro']['usr']['user']);
}

if ($post['endpoint'] === 'update') {
  $query = "CALL pro_5editProducto (?,?,?,?,?,?,?,?,?,?,?)";
  $params = [
    strtoupper($post['cod_product']),
    !empty($post['cod_alt']) ? $post['cod_alt'] : '0',
    strtoupper($post['nom_product']),
    strtoupper($post['desc_product']),
    $post['optgrupo'],
    $post['stockminimo'],
    $post['stockmaximo'],
    $post['pcosto'],
    $post['pventa'],
    $post['umedida'],
    $post['stock']
  ];

  $rs = prepareRS($conexion, $query, $params);
  responseJSON($rs->fetch(PDO::FETCH_ASSOC));
  #setBitacora('INVENTARIO','MODIFICAR PRODUCTO',$params,$_SESSION['pro']['usr']['user']);
}

if ($post['endpoint'] === 'delete') {
  $query = "DELETE FROM pro_2producto WHERE cod_producto IN ";
  $delItems = implode("','", $post['list']);
  $query .= "('{$delItems}')";

  if (!prepareRS($conexion, $query, [])):
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
    responseJSON(['status' => 400, 'error' => $error]);
  else:
    responseJSON(['status' => 200, 'message' => "Producto(s) de Inventario {$delItems} Borrado(s) Correctamente"]);
  #setBitacora('INVENTARIO','BORRAR PRODUCTO',$params,$_SESSION['pro']['usr']['user']);
  endif;
}

if ($post['endpoint'] === 'getProductSupplier') {
  $query = 'SELECT 
    CONCAT(p.sig_idproveedor,"-",p.id_proveedor) AS rif,
    p.razon_social AS nombre,
    sub.ucompra
FROM pro_1proveedor p
JOIN (
    SELECT 
        c.id_proveedor,
        DATE(MAX(c.fecha_compra)) AS ucompra
    FROM pro_3dcompra dc
    JOIN pro_2compra c ON dc.id_pago = c.id_pago
    WHERE dc.cod_producto = ?
    GROUP BY c.id_proveedor
) AS sub ON p.id_proveedor = sub.id_proveedor';

  $rs = prepareRS($conexion, $query, [$post['id']]);
  resultResponse($rs, 'all');
}
