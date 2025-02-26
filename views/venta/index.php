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
   <div class="table-responsive text-nowrap">


      <table id="tbl-venta" class="table table-striped table-hover">
         <thead>
            <tr>
               <th>ID</th>
               <th>#Factura</th>
               <th>#Nota.E</th>
               <th>Tipo de venta</th>
               <th>Fecha</th>
               <th>idcliente</th>
               <th>Cliente</th>
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
