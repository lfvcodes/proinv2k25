import { loadComponents,response } from '@util';
import { loadCrud,initSelect } from '@StartCrud';


function loadConductor(){
  let options = '<option disabled selected value="">Elige uno</option>';
  response('conductor/', { endpoint: 'getOptionConductor' }).then(data => {
    data.result.forEach(opt => {
      options += `<option value="${opt.id}">${opt.nombre}</option>`;
    }); 
    $('#optcond').html(options);
    initSelect('optcond','guia');
  })
}

function loadVehiculo(){
  let options = '<option disabled selected value="">Elige uno</option>';
  response('vehiculo/', { endpoint: 'getOptionVehiculo' }).then(data => {
    data.result.forEach(opt => {
      options += `<option value="${opt.id}">${opt.nombre}</option>`;
    }); 
    $('#vehiculo').html(options);
    initSelect('vehiculo','guia');
  })
}

loadComponents();
loadCrud('guia',['optcond','vehiculo']);
loadConductor();
loadVehiculo();