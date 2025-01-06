<?php
session_start();
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../utils/php/utils.php';
$session = verifySession();
if (!$session) {
  header("Location: ../login/");
}
$title = 'Clientes';

require_once __DIR__ . '/../../includes/head.php';
require_once __DIR__ . '/../../includes/header.php';

importModals();
?>

<div class="card">
  <div class="card-header">
    <div class="row">
      <h5 class="col text-primary m-auto"><i class="menu-icon tf-icons me-1 bx bx-user-pin"></i>Clientes</h5>
      <div class="col text-end">
        <button type="button" class="btn btn-primary btn-md" data-bs-toggle="modal" data-bs-target="#mdl-cliente">
          <i class="bx bx-plus-circle"></i>Agregar
        </button>
      </div>
    </div>
  </div>

  <div class="card-body pt-0 pb-1">
    <div class="table-responsive">
      <table id="tbl-cliente" class="table table-striped table-hover display">
        <thead>
          <tr>
            <th hidden>TIPO ID</th>
            <th hidden>ID</th>
            <th>Razon Social</th>
            <th>Nombre/ Contacto</th>
            <th hidden>Estado</th>
            <th hidden>Dirección</th>
            <th hidden>Correo</th>
            <th>Teléfono</th>
            <th>Vendedor</th>
            <th hidden>idVend</th>
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
