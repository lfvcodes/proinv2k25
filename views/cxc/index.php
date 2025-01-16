<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../utils/php/utils.php';
$session = verifySession();
if (!$session) {
  header("Location: ../login/");
}

$title = 'Cuentas por Cobrar';

require_once __DIR__ . '/../../includes/head.php';
require_once __DIR__ . '/../../includes/header.php';

importModals();
?>

<div class="card">
  <div class="card-header">
    <h5 class="col text-primary m-auto"><i class="menu-icon tf-icons bx bx-table me-1 "></i> Cuentas por Cobrar</h5>
  </div>
  <div class="card-body pt-0 pb-1">
    <div class="table-responsive text-nowrap">
      <table id="tbl-cxc" class="table table-striped table-hover">
        <thead>
          <tr>
            <th>ID</th>
            <th>Venta</th>
            <th>N.Entrega</th>
            <th>Monto $</th>
            <th>fecha</th>
            <th>Cliente</th>
            <th>Concepto</th>
            <th>Rif</th>
            <th>Vencimiento</th>
            <th>Fechav</th>
            <th>Estado</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">

        </tbody>
      </table>
    </div>
  </div>
</div>

<?php
require_once __DIR__ . '/../../includes/footer.php';
