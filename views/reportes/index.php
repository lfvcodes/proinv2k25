<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../utils/php/utils.php';
$session = verifySession();
if (!$session) {
  header("Location: ../login/");
}

$title = 'Reportes';

require_once __DIR__ . '/../../includes/head.php';
require_once __DIR__ . '/../../includes/header.php';

?>

<div class="card">
  <div class="card-header">
    <h5 class="text-primary m-auto">
      <i class="bi bi-file-earmark-pdf me-1"></i>Reportes
    </h5>
  </div>
  <div class="card-body pt-0 pb-0">
    <table id="report-table" class="table table-sm table-hover table-striped">
      <thead class="">
        <th>Titulo de Reporte</th>
        <th class="text-end">Opción</th>
      </thead>
      <tbody>
        <tr>
          <td>Ingresos vs Egresos</td>
          <td class="text-end"><button type="button" class="btn btn-sm btn-primary">Ver</button></td>
        </tr>
        <tr>
          <td>Reporte de Compras</td>
          <td class="text-end"><button type="button" class="btn btn-sm btn-primary">Ver</button></td>
        </tr>
        <tr>
          <td>Reporte de Ventas</td>
          <td class="text-end"><button type="button" class="btn btn-sm btn-primary">Ver</button></td>
        </tr>
        <tr>
          <td>Comisiones de Ventas</td>
          <td class="text-end"><button type="button" class="btn btn-sm btn-primary">Ver</button></td>
        </tr>
        <tr>
          <td>Ventas por Cliente</td>
          <td class="text-end"><button type="button" class="btn btn-sm btn-primary">Ver</button></td>
        </tr>
        <tr>
          <td>Productos por Categoría</td>
          <td class="text-end"><button type="button" class="btn btn-sm btn-primary">Ver</button></td>
        </tr>
        <tr>
          <td>Compras por Vendedor</td>
          <td class="text-end"><button type="button" class="btn btn-sm btn-primary">Ver</button></td>
        </tr>
        <tr>
          <td>Estado de Morosidad</td>
          <td class="text-end"><button type="button" class="btn btn-sm btn-primary">Ver</button></td>
        </tr>
        <tr>
          <td>Inventario por Fecha</td>
          <td class="text-end"><button type="button" class="btn btn-sm btn-primary">Ver</button></td>
        </tr>
        <tr>
          <td>Kardex de Productos</td>
          <td class="text-end"><button type="button" class="btn btn-sm btn-primary">Ver</button></td>
        </tr>
        <tr>
          <td>Cuentas por Cobrar</td>
          <td class="text-end"><button type="button" class="btn btn-sm btn-primary">Ver</button></td>
        </tr>
        <tr>
          <td>Cuentas por Pagar</td>
          <td class="text-end"><button type="button" class="btn btn-sm btn-primary">Ver</button></td>
        </tr>
      </tbody>
    </table>
  </div>
  <div class="card-body mt-3 pt-0 pb-0">
    <iframe hidden id="frm-rpt" class="w-100 m-2 p-1" style="background: gray;" src="" frameborder="0">

    </iframe>
  </div>
</div>

<?php
require_once __DIR__ . '/../../includes/footer.php';
