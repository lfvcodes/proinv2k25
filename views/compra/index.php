<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../utils/php/utils.php';
$session = verifySession();
if (!$session) {
  header("Location: ../login/");
}

$title = 'Compras';

require_once __DIR__ . '/../../includes/head.php';
require_once __DIR__ . '/../../includes/header.php';

importModals();
?>

<div class="card">
  <div class="card-header">
    <div class="row">
      <h5 class="col text-primary m-auto"><i class="bi bi-building-up"></i> Compras Registradas</h5>
      <div class="col text-end">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#mdl-compra">
          <i class="bx bx-plus-circle"></i>Agregar
        </button>
      </div>
    </div>
  </div>

  <div class="table-responsive text-nowrap">
    <table id="tbl-compra" class="table table-striped table-hover">
      <thead>
        <tr>
          <th>ID</th>
          <th>#Comprobante</th>
          <th>Tipo de Compra</th>
          <th>Fecha</th>
          <th>idproveedor</th>
          <th>Proveedor</th>
          <th>Concepto</th>
          <th>Forma Pago</th>
          <th>Tasa</th>
          <th>Usuario</th>
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
