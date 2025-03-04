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
        <button type="button" class="btn btn-primary btn-md" data-bs-toggle="modal" data-bs-target="#mdl-inventario">
          <i class="bx bx-plus-circle"></i>Agregar
        </button>
      </div>
    </div>
  </div>

  <div class="card-body pt-0 pb-0">
    <table id="tbl-inventario" class="table table-striped table-hover display nowrap">
      <thead>
        <tr>
          <th title="Codigo de Sistema">ID</th>
          <th hidden>COD</th>
          <th title="Codigo Alternativo">Cód Alterno</th>
          <th title="Nombre de Producto">Producto</th>
          <th title="Existencia">Stock</th>
          <th title="Precio de Costo">P.Costo</th>
          <th title="Precio de Venta">P.Venta</th>
          <th title="Utilidad Bruta">U.B%</th>
          <th hidden>Descripcion</th>
          <th hidden>Categoria</th>
          <th hidden>S.Minimo</th>
          <th hidden>S.Maximo</th>
          <th hidden>U.M</th>
          <th hidden>Excento</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">

      </tbody>
    </table>
  </div>
</div>

<?php
require_once __DIR__ . '/../../includes/footer.php';
