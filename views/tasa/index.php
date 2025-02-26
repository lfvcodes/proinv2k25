<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../utils/php/utils.php';
$session = verifySession();
if (!$session) {
  header("Location: ../login/");
}
setBitacora(null, "ACTUALIZAR TASA", "ENTRAR AL MODULO DE CAMBIO DE TASA", [], $session['log_user']);

$title = 'Tasa';
require_once __DIR__ . '/../../includes/head.php';
require_once __DIR__ . '/../../includes/header.php';

importModals();
?>

<div class="card">
  <div class="card-header">
    <div class="row">
      <h5 class="col text-primary m-auto">
        <i class="menu-icon tf-icons me-1 bi bi-coin"></i>Tasas de Cambio
      </h5>
      <div class="col text-end">
        <button type="button" class="btn btn-primary btn-md" data-bs-toggle="modal" data-bs-target="#mdl-tasa">
          <i class="bx bx-plus-circle"></i>Agregar
        </button>
      </div>
    </div>
  </div>
  <div class="card-body pt-0 pb-1">
    <div class="table-responsive">
      <table id="tbl-tasa" class="table table-striped table-hover display nowrap">
        <thead>
          <tr>
            <th>id</th>
            <th>Tasa</th>
            <th>Fecha y Hora</th>
            <th>Usuario</th>
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
