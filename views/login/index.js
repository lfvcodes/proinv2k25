
import { changeLocation, response } from '@util';

$(document).ready(function () {

  $('#formAuthentication').on('submit', function (e) {
    e.preventDefault() /* prevenir el evento de click por default */
    var usr = $('#log').val().trim();
    var psw = $('#psw').val().trim();
    response('/login/', { log: usr, pass: psw, endpoint: 'enter' })
      .then(data => {
        changeLocation('inicio/');
      }).catch(error => {
        const msgError = (error.responseJSON && error.responseJSON.message) || 'Error desconocido';
        Swal.fire('ERROR',msgError,'error');
      })
  });

});