import { loadComponents,response,empty,SAConfig } from '@util';
import { loadCrud } from '@StartCrud';
import {refreshTable} from '@DTCrud';

window.loadCxpConfirmed = function(){
  response('cxp/',{endpoint:'getCxpSolvent'}).then(data => {
    let table = $('#tbl-solv').find('tbody');
    data.result.forEach(element => {
      let comprobante = (empty(element.nota) == false) ? 'N'+element.nota : 'F'+element.fact;
      table.append(`<tr vt="${element.compra}">
        <td>${comprobante}</td>
        <td>${element.prov}</td>
        <td>${element.cobro}</td>
        <td>$${element.monto}</td>
        <td><span class="badge text-light bg-success">Solvente</span></td>
        <td onclick="revertCxpConfirmed(this)">
          <i title="Revertir Cuenta" class="btn btn-secondary rounded-pill text-right bi bi-arrow-left"></i>
        </td>
        </tr>`);
      });
    });
  
  $('#mdl-solvent').on('hidden.bs.modal',function(){
    $('#tbl-solv tbody').html("");
  });
  $('#mdl-solvent').modal('show');
}

window.revertCxpConfirmed = function(btn){
  let compra = $(btn).parent('tr').attr('vt');
  let comp = $(btn).parent('tr').find('td:first').html().trim();

  SAConfig.title = `¿Está seguro que desea Revertir la Cuenta ${comp}? \n
   Esta pasará a la lista de No confirmadas (pendientes o vencidas)`;
  
   Swal.fire(SAConfig).then((result) => {
     if (result.value == true) {
       
      response('cxp/',{endpoint:'revertCxpSolvent',idCompra:compra}).then(data => {
        console.log(data);
        if(data.status == 200){
          Swal.fire(data.message,'','success').then(() => {});
        }else{
          Swal.fire(`Error al Intentar Revertir Cuenta por pagar `+data.error,'','error').then(() => {});
        }
        $('#mdl-solvent').modal('hide');
        refreshTable();
      });

     } else return;
   });

}

loadComponents();
loadCrud('cxp',['cod','idp','fechac','fecha']);

$(document).ready(function () {
  $('.dt-buttons').removeClass('offset-3 offset-lg-5');
  let $btnCxpConfirmed = `<button class="btn btn-outline-primary ms-2 rounded-pill" 
    onclick="loadCxpConfirmed()" type="button">
    <span><i class="bi bi-check text-success me-1"></i>Ver cuentas Confirmadas</span>
  </button>`;
  $('.dt-buttons').append($btnCxpConfirmed);
});