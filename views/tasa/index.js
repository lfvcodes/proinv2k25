import { loadComponents, getParameterByName } from "@util";
import { loadCrud } from "@StartCrud";

if (getParameterByName("actualizar") === "true") {
  let message = "You have not filled out the form.";
  window.onbeforeunload = function (event) {
    let e = event || window.event;
    if (e) {
      e.returnValue = message;
    }
    return message;
  };
}

loadComponents();
loadCrud("tasa");

$(function () {
  $("#lbl-date-tasa").text(moment().format("DD/MM/YYYY"));

  /*Detectar cuando se actualiza la tasa y quitar prevencion de salida*/
  const observer = new MutationObserver(function (mutationsList) {
    mutationsList.forEach((mutation) => {
      if (mutation.type === "childList") {
        mutation.addedNodes.forEach((node) => {
          if (node.nodeType === 1 && node.classList.contains("alert-success")) {
            window.onbeforeunload = null;
          }
        });
      }
    });
  });

  // Configura el observer para observar cambios en el cuerpo del documento
  observer.observe(document.body, {
    childList: true, // Para observar hijos añadidos o eliminados
    subtree: true, // Para observar también dentro de los hijos del nodo raíz
  });
});
