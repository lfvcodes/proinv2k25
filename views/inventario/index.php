<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../utils/php/utils.php';
$session = verifySession();
if (!$session) {
  header("Location: ../login/");
}

$title = 'Inventario';

require_once __DIR__ . '/../../includes/head.php';
require_once __DIR__ . '/../../includes/header.php';

importModals();
?>

<div class="card">
  <div class="card-header">
    <div class="row">
      <h5 class="col text-primary m-auto">
        <i class="menu-icon tf-icons bi bi-boxes me-1"></i>Inventario
      </h5>
      <div class="col text-end">
        <button type="button" class="btn btn-primary btn-md" data-bs-toggle="modal" data-bs-target="#mdl-product">
          <i class="bx bx-plus-circle"></i>Agregar Articulo
        </button>
      </div>
    </div>
  </div>

  <div class="card-body pt-0 pb-0">
    <table id="tbl-inventario" class="table table-striped table-hover display nowrap">
      <thead>
        <tr>
          <th>ID</th>
          <th>Código</th>
          <th>CAlterno</th>
          <th>Producto</th>
          <th>U.M</th>
          <th>Stock</th>
          <th>P.Costo</th>
          <th>P.Venta</th>
          <th>S.Minimo</th>
          <th>S.Maximo</th>
          <th>Categoria</th>
          <th>U.B%</th>
          <th>Acción</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">

      </tbody>
    </table>
  </div>
</div>

<?php
require_once __DIR__ . '/../../includes/footer.php';
