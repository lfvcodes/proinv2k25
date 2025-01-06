import { loadComponents,response } from '@util';
import { loadCrud,initSelect } from '@StartCrud';

const hideCols = ['nac', 'id', 'dir', 'optestado', 'optvendedor', 'email', 'cont'];
loadComponents();
loadCrud('cliente', hideCols);

function loadVendedores(){
  let options = '';
  response('vendedor/', { endpoint: 'getOption' }).then(data => {
    data.result.forEach(opt => {
      options += `<option value="${opt.id}">${opt.nombre}</option>`;
    }); 
    $('#optvendedor').html(options);
    initSelect('optvendedor','cliente');
  });
}

function loadEstados(){
  let options = '';
  response('cliente/', { endpoint: 'getOptionEstado' }).then(data => {
    data.result.forEach(opt => {
      options += `<option value="${opt.id}">${opt.nombre}</option>`;
    }); 
    $('#optestado').html(options);
    initSelect('optestado','cliente');
  });
}

loadVendedores();
loadEstados();

