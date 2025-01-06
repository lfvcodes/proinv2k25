<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../utils/php/utils.php';
$session = verifySession();
if (!$session) {
  header("Location: ../login/");
}
$title = 'Categoria';


require_once __DIR__ . '/../../includes/head.php';
require_once __DIR__ . '/../../includes/header.php';
importModals();
?>

<div class="card">
  <div class="card-header">
    <div class="row">
      <h5 class="col text-primary m-auto">
        <i class="bi bi-tag"></i>Categorias
      </h5>
      <div class="col text-end">
        <button type="button" class="btn btn-primary btn-md" data-bs-toggle="modal" data-bs-target="#mdl-categoria">
          <i class="bx bx-plus-circle"></i>Agregar
        </button>
      </div>
    </div>
  </div>
  <div class="card-body pt-0">

    <table id="tbl-categoria" class="table table-striped table-hover display nowrap">
      <thead>
        <tr>
          <th>ID</th>
          <th>Categoría/Departamento</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">

      </tbody>
    </table>
  </div>
</div>

<?php
require_once __DIR__ . '/../../includes/footer.php';
