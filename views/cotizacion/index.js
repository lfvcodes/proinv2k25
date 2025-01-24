import { loadComponents, response, empty } from "@util";
import { loadCrud, crudAlterControls } from "@StartCrud";
const hiddenCols = ["registro"];

loadComponents();
loadCrud("cotizacion", hiddenCols);

function startDOM() {
  const $btnCopyVent = `<button type="button" onclick="" class="btn rounded-circle p-2 btn-primary controls">
               <i class="m-auto bx bx-copy text-white"></i>
            </button>`;
  $("#dt-controls").html(`${CONTROL_START}`);
  $("#dt-controls").prepend($btnCopyVent);
}

$(function () {
  const CONTROL_START = $("#dt-controls").html();

  $("#tbl-cotizacion")
    .DataTable()
    .on("preInit.dt init.dt initComplete draw.dt search.dt", () => {
      startDOM();
    });
});
