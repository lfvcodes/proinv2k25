import { loadComponents, getParameterByName } from "@util";
import { loadCrud } from "@StartCrud";

if (getParameterByName("actualizar") === "true") {
  var message = "You have not filled out the form.";
  window.onbeforeunload = function (event) {
    var e = event || window.event;
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
});
