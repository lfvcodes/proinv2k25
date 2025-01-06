
import {response,empty } from '@util';

window.approveCxc = function(){
   var element = event.currentTarget; // o event.target
   alert('va a confirmar el elemento: ' + $(element).attr('rowId'));
}

window.deleteCxc = function(){
   var element = event.currentTarget; // o event.target
   alert('va a eliminar el elemento: ' + $(element).attr('rowId'));
}

window.revertCxc = function(){
   var element = event.currentTarget; // o event.target
   alert('va a revertir el elemento: ' + $(element).attr('rowId'));
}

window.setAbonoCxc = function(){

   var element = $(event.currentTarget);
   //$('input[name="id"]').val($dt['cod']);
   //$('input[name="mdeuda"]').val($dt['monto']);
   var res = response('cxc/',{endpoint:"getDetailAbono",id:element.attr('rowId')});
   if(res.status == 200){
    var $tbl = res.result
    $('#tbl-abono tbody').html("");
    var tabono = 0.0;
    for (let i = 0; i < $tbl.length; i++) {
      $('#tbl-abono tbody').append(`
      <tr>
        <td><input class="form-control fec" value="`+$tbl[i]['fecha_abono']+`" max="<?php echo date('Y-m-d'); ?>" required type="date" name="fec[]"></td>
        <td><input class="form-control monto" value="`+$tbl[i]['monto_abono']+`" placeholder="$" required step="0.01" type="number" name="monto[]"></td>
        <td><input placeholder="Describa el concepto de Abono"
        class="form-control" required type="text" maxlength="350" value="`+$tbl[i]['concepto_abono']+`" name="concepto[]"></td>
        <td><button onclick="removeAbono(this);" type="button" class="btn btn-sm btn-danger">-</button></td>
      </tr>
      `);
      tabono += $tbl[i]['monto_abono'];
    }

    $('#totalabono').html("Total: $ "+tabono.toLocaleString('es-ES', { minimumFractionDigits: 2, maximumFractionDigits: 2, useGrouping: true }));
    $('#title-abono').html('<i class="mb-2 bx bx-folder-plus"></i>Registrar Abono de Cobro de CxC ('+$dt['nom']+')');
    }

   $('#mdl-abono').modal('show');
}

window.addAbonoCxc = function(){
     $('#tbl-abono tbody').append(`
     <tr>
       <td><input class="form-control fec" required type="date" name="fec[]"></td>
       <td><input class="form-control monto" placeholder="$" required step="0.01" type="number" name="monto[]"></td>
       <td><input placeholder="Describa el concepto de Abono"
     class="form-control" required type="text" maxlength="350" name="concepto[]"></td>
     <td><button onclick="removeAbonoCxc();" type="button" class="btn btn-sm btn-danger">-</button></td>
     </tr>
     `);

     $('#tbl-abono tbody tr td .monto').change(function (e) {
       e.preventDefault();
       var tabono = 0;
       $('#tbl-abono tbody tr').each(function(){
         tabono += Number($(this).find('td:eq(1) .monto').val());
       });
       $('#totalabono').html("Total: $ "+tabono.toLocaleString('es-ES', { minimumFractionDigits: 2, maximumFractionDigits: 2, useGrouping: true }));
     });

     var tabono = 0;
     $('#tbl-abono tbody tr').each(function(){
       tabono += Number($(this).find('td:eq(1) .monto').val());
     });

     $('#totalabono').html("Total: $ "+tabono.toLocaleString('es-ES', { minimumFractionDigits: 2, maximumFractionDigits: 2, useGrouping: true }));
     //initSelect();
 }

window.removeAbonoCxc = function(){
  var btn = $(event.currentTarget);
  var tabono = 0;
  $(btn).parent('td').parent('tr').remove();

  $('#tbl-abono tbody tr').each(function(){
    tabono += Number($(this).find('td:eq(1) .monto').val());
  });

  tabono = tabono.toLocaleString('es-ES', { minimumFractionDigits: 2, maximumFractionDigits: 2, useGrouping: true })
  $('#totalabono').html(`Total: $ ${tabono}`);

  if($('#tbl-abono tbody tr').length < 1){
    return false;
  }
}
