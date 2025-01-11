import { loadComponents,response,empty } from '@util';
import { loadCrud,crudAlterControls } from '@StartCrud';
const hiddenCols = ['cod','desc_product','optgrupo','categoria','stockminimo','stockmaximo','umedida']
const codProd = $('#mdl-inventario input[name="cod_product"]');

function loadCategorias(){
  response('categoria/',{endpoint:'getListOptionCat'}).then(data => {
    if(data.status == 200 && !empty(data.result)){
      data.result.forEach(item => {
        $('#optgrupo').append(`<option value="${item.id}">${item.text}</option>`);
      });
    }

    $('#optgrupo').select2({
      theme: 'bootstrap-5',
      dropdownParent: $("#mdl-inventario"),
      language: {
        searching: function() { return "Buscando...";},
        noResults: function() {
          return "No se Encontraron Resultados";
        },
      },
    });

  });
}

function initBarCode(barCodeValue){
  JsBarcode("#barcode", barCodeValue, {
    lineColor: "#000",
    width: 2,
    height: 72,
  });
}

function loadBarcode(){
  $('#mdl-inventario #bar').append(`<svg id="barcode"></svg>`);
  initBarCode("000-000-000-0");

  codProd.bind('keypress', function(e) { e.stopPropagation();  });
  codProd.on('keyup keydown change',function(){
    let $vl = $(this).val();
    initBarCode($vl);
  });
}

loadComponents();
loadCrud('inventario',hiddenCols);
loadBarcode();
loadCategorias();

crudAlterControls({
  'view':{
    event:'click',
    fn: () => {
      initBarCode(codProd.val())
    }
  },
  'edit':{
    event:'click',
    fn: () => {
      initBarCode(codProd.val())
    }
  }
})