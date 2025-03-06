<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../utils/php/utils.php';
$session = verifySession();
if (!$session) {
   header("Location: ../login/");
}

$title = 'Venta';

require_once __DIR__ . '/../../includes/head.php';
require_once __DIR__ . '/../../includes/header.php';

importModals();
?>
<div class="card">
   <div class="card-header">
      <div class="row">
         <h5 class="col text-primary m-auto">
            <i class="menu-icon tf-icons me-1 bi bi-building-down"></i>Ventas
         </h5>
         <div class="col text-end">
            <button type="button" class="btn btn-primary btn-md" data-bs-toggle="modal" data-bs-target="#mdl-venta">
               <i class="bx bx-folder-plus"></i>Agregar
            </button>
         </div>
      </div>
   </div>
   <div class="card-body pt-0 pb-1">
      <div class="table-responsive text-nowrap">

         <table id="tbl-venta" class="table table-striped table-hover w-100">
            <thead>
               <tr>
                  <th>ID</th>
                  <th>#Venta</th>
                  <th>#Factura</th>
                  <th>#Nota.E</th>
                  <th>Tipo de venta</th>
                  <th>Fecha</th>
                  <th hidden>idcliente</th>
                  <th hidden>Concepto</th>
                  <th hidden>Forma Pago</th>
                  <th hidden>Tasa</th>
                  <th hidden>IVA</th>
                  <th>Usuario</th>
                  <th hidden>Comision</th>
                  <th hidden>Registro</th>
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
