<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../utils/php/utils.php';
$session = verifySession();
if (!$session) {
  header("Location: ../login/");
}

$title = 'Proveedores';

require_once __DIR__ . '/../../includes/head.php';
require_once __DIR__ . '/../../includes/header.php';

importModals();

?>

<div class="card">
  <div class="card-header">
    <div class="row">
      <h5 class="col text-primary m-auto">
        <i class="menu-icon tf-icons me-1 bx bx-briefcase"></i>Proveedores
      </h5>
      <div class="col text-end">
        <button type="button" class="btn btn-primary btn-md" data-bs-toggle="modal" data-bs-target="#mdl-proveedor">
          <i class="bx bx-plus-circle"></i>Agregar
        </button>
      </div>
    </div>
  </div>

  <div class="card-body pt-0 pb-1">
    <div class="table-responsive">
      <table id="tbl-proveedor" class="table table-striped table-hover">
        <thead>
          <tr>
            <th>J/V/P</th>
            <th>ID</th>
            <th>Razon social</th>
            <th>Contacto</th>
            <th>Correo</th>
            <th hidden>Estado</th>
            <th hidden>Direccion</th>
            <th>Teléfono</th>
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
