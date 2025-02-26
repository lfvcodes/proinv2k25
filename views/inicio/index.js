import { loadComponents, response } from "@util";

loadComponents();

function getTasaToday() {
  response("tasa/", { endpoint: "getTasaToday" }).then((data) => {
    if (data.status == 200) {
      if (data.result === `Actualizar Tasa`) {
        let param = btoa("true");
        window.location.href = `../tasa/?actualizar=${param}`;
      }
    }
  });
}

$(function () {
  getTasaToday();
});
