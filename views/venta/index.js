import { loadComponents, showModalDocument } from "@util";
import { loadCrud } from "@StartCrud";
import { loadVenta } from "@Venta";

const hiddenCols = [
  "id_venta",
  "id_cliente",
  "descripcion",
  "forma_pago",
  "tasa",
  "iva",
  "comision",
  "registro",
];

let productosSeleccionados = [];
loadComponents();
loadCrud("venta", hiddenCols, true);

function startDOM() {
  let dataRow = $('#dt-controls button[control="view"]').attr("row");
  const $btnPrintVenta = `<button type="button" control="pdf" id="btn-print-pdf" onclick="printPdfVenta(this)"
   class="btn rounded-circle p-2 btn-primary controls ms-1">
      <i class="m-auto bi bi-file-pdf text-white fw-bold"></i>
  </button>`;

  let htmlControl = $btnPrintVenta + sessionStorage.getItem("controls");

  $("#dt-controls").html(htmlControl);
  $("#dt-controls button").attr("row", dataRow);
  alterControl();

  $("#mdl-venta").on("hidden.bs.modal", () => {
    $("#mdl-venta input, #mdl-venta select").prop("readonly", false);
    $("#mdl-venta input, #mdl-venta select").val("");
    $(`#mdl-venta input[name="endpoint"]`).val("setVenta");
    $("#mdl-venta #agregarFila").prop("hidden", false);
    $("#items-venta tbody").html("");
    $("#mdl-venta #stotald").val("");
    $("#mdl-venta #refer").html("");
    $("#mdl-venta .cli").html("");
  });
}

window.printPdfVenta = function (btn) {
  let row = atob($(btn).attr("row"));
  let dataRow = JSON.parse(row)[0];
  let urlPdf = `pdf/venta.php?v=${dataRow.cod}&t=${dataRow.tipo_venta}`;
  showModalDocument(urlPdf, `Venta N° ${dataRow.cod}`);
};

window.alterControl = function () {
  const btnAddVenta = $('button[control="add"]');
  const btnEditVenta = $('#dt-controls button[control="edit"]');
  const btnViewVenta = $('#dt-controls button[control="view"]');

  btnAddVenta.click(async () => {
    unselectAllRows();
    loadVenta(btnAddVenta, "Venta");
  });

  btnViewVenta.click(async () => {
    loadVenta(btnViewVenta, "Venta", "view");
  });

  btnEditVenta.click(async () => {
    loadVenta(btnEditVenta, "Venta", "edit");
  });
};

$(function () {
  sessionStorage.setItem("controls", $("#dt-controls").html());
  let table = $("#tbl-venta")
    .DataTable()
    .on("draw.dt search.dt select.dt deselect.dt length.dt", () => {
      let selectedRows = table.rows({ selected: true });
      if (selectedRows.count() === 1) {
        startDOM();
      }
    });
  startDOM();
});
