import { loadComponents,response } from '@util';
import { loadCrud,initSelect } from '@StartCrud';
function loadEstados(){
  let options = '';
  response('vendedor/', { endpoint: 'getOptionEstado' }).then(data => {
    data.result.forEach(opt => {
      options += `<option value="${opt.id}">${opt.nombre}</option>`;
    }); 
    $('#optestado').html(options);
    initSelect('optestado','vendedor');
  });
}

loadComponents();
loadCrud('vendedor', ['optestado','dir','email']);
loadEstados();