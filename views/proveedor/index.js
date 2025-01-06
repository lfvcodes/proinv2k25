import { loadComponents,response } from '@util';
import { loadCrud,initSelect } from '@StartCrud';

function loadEstados(){
  let options = '';
  response('cliente/', { endpoint: 'getOptionEstado' }).then(data => {
    data.result.forEach(opt => {
      options += `<option value="${opt.id}">${opt.nombre}</option>`;
    }); 
    $('#optestado').html(options);
    initSelect('optestado','proveedor');
  });
}

loadComponents();
loadCrud('proveedor', ['optestado','dir']);
loadEstados();