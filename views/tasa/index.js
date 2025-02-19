import { loadComponents } from "@util";
import { loadCrud } from "@StartCrud";

loadComponents();
loadCrud("tasa");

$(function () {
  $("#lbl-date-tasa").text(moment().format("DD/MM/YYYY"));
});

/*
$(document).ready(function () {

  (async function() {
    fetch('ctrl_tasa',{
      method: "POST",
      headers: {
        "Content-Type":"application/x-www-form-urlencoded"
      },
      body: new URLSearchParams({action: "getListTasa"})
    }).then((response) => {
      return response.json(); //ar la respuesta como JSON
    }).then((datos) => {
      $('#tbl-tasa').DataTable({
        "responsive": true,
        "fixedHeader": true,
        "scroller":    true,
        "sScrollY":     300,
        "pageLength": 10,
        "Sort": true,
        "aaSorting": [],
        dom: "<'row px-2 px-md-4 pt-2'<'col-md-3' l><'col-md-5 text-center' ><'col-md-4'f>>" +
          "<'row'<'col-md-12'trip>>",
        "columnDefs":[
          {
            "targets":[4],
            "orderable":false,
          },
        ],
        "columns":[
          { 
            "data": "id",
            "visible":false
          },
          {"data":"fecha"},
          {"data":"tasa"},
          {"data":"log"},
          {"data": null,
            "render": function(data, type, row){
              if($('#usr-role').text().trim() == 'Administrador'){
              return `
              <div class="dropdown">
                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                  <i class="bx bx-dots-vertical-rounded"></i>
                </button>
                <div class="dropdown-menu">
                  <a class="dropdown-item"
                      rw="`+ base64Encode(JSON.stringify(row))+`"
                    onclick="editTasa(this)" href="javascript:void(0);">
                    <i class="bx bx-edit-alt me-1"></i> Editar 
                  </a>
                  <a class="dropdown-item" dl="`+row['cod']+`" onclick="delTasa(this)" href="javascript:void(0);">
                    <i class="bx bx-trash-alt me-1"></i> Eliminar
                  </a>
                </div>
              </div>`;
              }else{
                return `
                <div class="dropdown">
                  <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                    <i class="bx bx-dots-vertical-rounded"></i>
                  </button>
                  <div class="dropdown-menu">
                    <a class="dropdown-item"
                        rw="`+ base64Encode(JSON.stringify(row))+`"
                      onclick="editTasa(this)" href="javascript:void(0);">
                      <i class="bx bx-edit-alt me-1"></i> Editar 
                    </a>
                  </div>
                </div>`;
              }
            },
          }
        ],
        "data":datos,
        "drawCallback": function( settings ) {
          $('.buttons-excel').addClass('btn-sm mb-2');
          $('.buttons-excel').css('background','#0C7363');
        },
      });
    }).catch((error) => {
      console.log('Hubo un error', error);
    });
  })();

  $('#tbl-tasa_length').addClass('ms-2');
  $('#tbl-tasa_filter').addClass('me-2');

  $(window).on('hidden.bs.modal',function(){
    $('#mdl-tasa .modal-title').html('<i class="mb-2 bx bx-folder-plus"></i>Registar Actualización');
    $('#mdl-tasa #btn-save').text('Guardar');
    $('#mdl-tasa #btn-save').removeClass('btn-warning').addClass('btn-primary');
    $('#frm-tasa')[0].reset();
  });

  $(".menu-inner .menu-item a:contains('Inicio')").parent('li').addClass('active');
});

function editTasa($btn){
  $jdata = base64Decode($($btn).attr('rw'));
  $dt = JSON.parse($jdata);
  //console.log($dt);
  $('#mdl-tasa form .form-control').each(function () {
    this.value = $dt[this.name];
  });  

  $('#mdl-tasa .modal-title').html('<i class="mb-2 bx bx-edit"></i>Editar Actualización');
  $('#mdl-tasa #btn-save').text('Editar');
  $('#mdl-tasa #btn-save').addClass('btn-warning').removeClass('btn-primary');
  $('#frm-tasa').append('<input type="hidden" value="'+$dt['id']+'" name="cod">');
  $('input[name="action"]').val('updateTasa');
  $('#mdl-tasa').modal('show');
}

function delTasa(btn){

  if(confirm('¿Está seguro que desea eliminar esta Categoría?')){
    $(btn).wrap('<form id="frm-post" method="POST" action="ctrl_tasa"><input type="hidden" name="action" value="removecat"></form>');
    $('#frm-post').append('<input type="hidden" value="'+$(btn).attr('dl')+'" name="cod">');
    $('#frm-post').submit();
  }else return false;

}
  */
