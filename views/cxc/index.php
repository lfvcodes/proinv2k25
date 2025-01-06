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
  <h5 class="card-header text-primary"><i class="menu-icon tf-icons bx bx-table me-1"></i>Cuentas por Cobrar</h5>
  <div class="card-body pt-0 pb-1 px-1">
    <div class="table-responsive">
      <table id="tbl-cuentas" class="table table-striped table-hover display">
        <thead>
          <tr>
            <th>ID</th>
            <th>Venta</th>
            <th>N.Entrega</th>
            <th>Monto $</th>
            <th>Cliente</th>
            <th>Rif</th>
            <th>Concepto</th>
            <th>Fecha</th>
            <th>Vencimiento</th>
            <th>FechaV</th>
            <th>Estado</th>
            <th>Acción</th>
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
