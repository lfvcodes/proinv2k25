import { loadComponents, DTConfig, response, empty } from '@util';
import * as operations from './operations.js';

loadComponents();

function loadTableSolvents() {

   var table = $('#tbl-solv').find('tbody');
   var res = response('cxc/', { endpoint: 'getCxcSolvent' });

   if (res.status == 200) {
      $.each(res.result, function (i, element) {
         let comprobante = (empty(element.nota) == false) ? 'N' + element.nota : 'F' + element.fact;
         table.append(`<tr vt="${element.venta}">
         <td>${comprobante}</td>
         <td>${element.cli}<br><small>Referido por: ${element.vendedor}</small></td>
         <td>${element.cobro}</td><td>$${element.monto}</td>
         <td><span class="badge bg-success text-white">Solvente</span>
         <td onclick="revertCxc(this)"><i class="text-right bi bi-arrow-left"></i></td>
         </td></tr>`);
      });
   }

   $('#mdl-solvent').on('hidden.bs.modal', function () {
      $('#tbl-solv tbody').html("");
   });

   $('#mdl-solvent').modal('show');
}

DTConfig.buttons = [
   {
      text: '<i class="bi bi-check me-1"></i>Cuentas Confirmadas',
      action: function () {
         loadTableSolvents();
      }
   },
];

var res = response('cxc/', { endpoint: 'getListCxc' })

res.result.forEach(item => {

   var buttonsOptions = (item.estado != 'S') ? `
         <a class="dropdown-item" rowId="${item.cod}" onclick="approveCxc()" href="javascript:void(0);">
            <i class="bx bx-check me-1"></i> Confirmar Pago
         </a>
         <a class="dropdown-item" rowId="${item.cod}" onclick="setAbonoCxc()" href="javascript:void(0);">
               <i class="bx bx-plus me-1"></i>Agregar Abono de Cobro
         </a>`
      :
      `<a class="dropdown-item" dl="${item.cod}"
            onclick="revertCxc(this)" href="javascript:void(0);">
            <i class="bx bx-reset me-1"></i> Revertir Cuenta
         </a>`;

   let options = `
         <div class="dropdown">
            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"></button>
            <div class="dropdown-menu">
               ${buttonsOptions}
            </div>
         </div>`;

   item.accion = options;

   switch (item.estado) {
      case 'P': item.estado = '<span class="badge text-white bg-warning">Pendiente</span>'; break;
      case 'S': item.estado = '<span class="badge text-white bg-success">Solvente</span>'; break;
      case 'V': item.estado = '<span class="badge text-white bg-danger">Vencida</span>'; break;
      default: break;
   }
});

var hiddenCols = ['cod', 'venta', 'idc', 'fechav', 'accion'];
var cols = Object.keys(res.result[0]).map(key => ({
   data: key,
   visible: !hiddenCols.includes(key)
}));

DTConfig.data = res.result;
DTConfig.columns = cols;
DTConfig.autoWidth = true;
$('#tbl-cuentas').DataTable(DTConfig);