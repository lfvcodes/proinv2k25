import {
  loadComponents,
  response,
  empty,
  SAConfig,
  prepareFormData,
} from "@util";
import { loadCrud } from "@StartCrud";
import { refreshTable } from "@DTCrud";
import { addAbono, removeAbono, loadAbono } from "@Abono";

window.startDOM = function () {
  let $btnCxcConfirmed = `<button class="btn btn-outline-primary ms-2 rounded-pill" 
    onclick="loadCxcConfirmed()" type="button">
    <span><i class="bi bi-check text-success me-1"></i>Ver cuentas Confirmadas</span>
  </button>`;

  $("#mdl-venta .modal-title").prepend('<i class="mb-2 bx bx-check"></i>');
  $(`.dt-buttons button[onclick="loadCxcConfirmed()"]`).remove();
  $(".dt-buttons").append($btnCxcConfirmed);

  $("#tbl-cxc tbody tr").each(function () {
    var estadoCell = $(this).find("td:last");

    if (estadoCell.text() === "V") {
      estadoCell.html('<span class="badge bg-danger">Vencida</span>');
    } else if (estadoCell.text() === "P") {
      estadoCell.html(
        '<span class="badge bg-warning text-dark">Pendiente</span>'
      );
    }
  });

  alterCxcControl();
};

window.loadCxcConfirmed = function () {
  response("cxc/", { endpoint: "getCxcSolvent" }).then((data) => {
    let table = $("#tbl-solv").find("tbody");
    data.result.forEach((element) => {
      let comprobante =
        empty(element.nota) == false ? "N" + element.nota : "F" + element.fact;
      table.append(`<tr vt="${element.venta}">
        <td>${comprobante}</td>
        <td>${element.cli}</td>
        <td>${element.cobro}</td>
        <td>$${element.monto}</td>
        <td><span class="badge text-light bg-success">Solvente</span></td>
        <td onclick="revertCxcConfirmed(this)">
          <i title="Revertir Cuenta" class="btn btn-secondary rounded-pill text-right bi bi-arrow-left"></i>
        </td>
        </tr>`);
    });
  });

  $("#mdl-solvent").on("hidden.bs.modal", function () {
    $("#tbl-solv tbody").html("");
  });
  $("#mdl-solvent").modal("show");
};

window.revertCxcConfirmed = function (btn) {
  let venta = $(btn).parent("tr").attr("vt");
  let comp = $(btn).parent("tr").find("td:first").html().trim();

  SAConfig.title = `¿Está seguro que desea Revertir la Cuenta ${comp}? \n
   Esta pasará a la lista de No confirmadas (pendientes o vencidas)`;

  Swal.fire(SAConfig).then((result) => {
    if (result.value == true) {
      response("cxc/", { endpoint: "revertCxcSolvent", idventa: venta }).then(
        (data) => {
          if (data.status == 200) {
            Swal.fire(data.message, "", "success").then(() => {
              refreshTable(startDOM);
            });
          } else {
            Swal.fire(
              `Error al Intentar Revertir Cuenta por pagar ` + data.error,
              "",
              "error"
            ).then(() => {});
          }

          $("#mdl-solvent").modal("hide");
        }
      );
    } else return;
  });
};

window.confirmCxc = function (btn) {
  let data = atob($(btn).attr("row"));
  let $dt = JSON.parse(data)[0];

  const formCxc = `<form id="form-venta" enctype="multipart/form-data" action="#"></form>`;
  $("#mdl-venta #form-contained").wrap(formCxc);
  $("#optcli").html(`<option value="${$dt["idp"]}">${$dt["nom"]}</option>`);
  $("#freg").val(moment($dt["fechac"]).format("YYYY-MM-DD"));
  $(".desc").val($dt["concepto"]);
  $("#fact").val($dt["nota"]);

  response("venta/", {
    endpoint: "getDetailTable",
    id: $dt["venta"],
  }).then((answer) => {
    if (answer.status == 200) {
      let $tbl = answer.result;

      $("#tbl-venta tbody").html("");
      let stotald = 0;
      let stotalb = 0;
      for (let i = 0; i < $tbl.length; i++) {
        $("#tbl-venta tbody").append(
          `
          <tr>
            <td>
              <select class="form-select form-control prod" name="prod[]">
                <option selected value="${$tbl[i]["cod_producto"]}">${
            $tbl[i]["nom_producto"]
          }</option>
              </select>
            </td>
            <td>
            <input required readonly onkeyup="calcm(this)" onchange="calcm(this)" name="cant[]" value="${
              $tbl[i]["cant"]
            }"
            class="form-control cant" min="1" step="1" type="number"></td>
            <td>
              <input required readonly name="monto[]" class="form-control monto" value="${
                $tbl[i]["monto"]
              }" min="0.01" step="0.01" type="number">
            </td>
            <td>
              <input readonly class="form-control titem" type="number" value="${
                $tbl[i]["cant"] * $tbl[i]["monto"]
              }">
            </td>
            <td></td>
          </tr>`
        );
        stotald += $tbl[i]["cant"] * $tbl[i]["monto"];
      }

      $("#stotald").val(
        stotald.toLocaleString("es-ES", {
          minimumFractionDigits: 2,
          maximumFractionDigits: 2,
          useGrouping: true,
        })
      );
      stotalb = stotald * $("#tasa").val();

      $("#tasa").change(function (e) {
        var tdol = $("#stotald").val().toString().replace(",", ".");
        stotalb = tdol * $(this).val();
        $("#stotal").val(
          stotalb.toLocaleString("es-ES", {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
            useGrouping: true,
          })
        );
      });

      $("#stotal").val(
        stotalb.toLocaleString("es-ES", {
          minimumFractionDigits: 2,
          maximumFractionDigits: 2,
          useGrouping: true,
        })
      );

      let $btnConfirmCxc = $("#mdl-venta .modal-footer .btn-primary");
      $btnConfirmCxc.text("Confirmar Pago");

      $btnConfirmCxc.click(() => {
        SAConfig.title = "¿Está seguro(a) de Confirmar esta Cuenta por Cobrar?";

        Swal.fire(SAConfig).then((result) => {
          if (result.value == true) {
            let $frm = $("#form-venta");
            let frmData = prepareFormData($frm);
            frmData.endpoint = "confirmVenta";
            frmData.id = $dt["venta"];

            response("cxc/", frmData).then((res) => {
              if (res.status == 200) {
                Swal.fire(res.message, "", "success").then(() => {
                  refreshTable(startDOM);
                  $("#mdl-venta").modal("hide");
                });
              } else {
                Swal.fire(res.error, "", "error");
              }
            });
          } else return false;
        });
      });

      $("#mdl-venta").on("hide.bs.modal", () => {
        $("#totalabono,#countabono").remove();
        $btnConfirmCxc.unbind("click");
        $("#mdl-venta #form-contained").unwrap();
      });

      $("#mdl-venta").modal("show");
    } else {
      Swal.fire("Error al cargar Cuenta", "", "error");
    }
  });
};

window.loadAbono = loadAbono;

window.alterCxcControl = function () {
  let btnAddPay = $('#dt-controls button[control="edit"]');
  let btnConfirm = $('#dt-controls button[control="view"]');

  window.addAbono = addAbono;
  window.removeAbono = removeAbono;

  $("#tbl-cxc").on("select.dt deselect.dt", () => {
    btnConfirm.html(`<i class="m-auto bi bi-check text-white"></i>`);
    btnAddPay.html(`<i class="m-auto bi bi-plus text-white"></i>`);
  });

  btnConfirm.click(() => {
    confirmCxc(btnConfirm);
  });

  btnAddPay.click(() => {
    loadAbono(btnAddPay, "cxc");
  });
};

loadComponents();
let $hideCols = ["cod", "idp", "fechac", "fecha"];
loadCrud("cxc", $hideCols);

$(function () {
  startDOM();

  $('#tbl-cxc').DataTable().on('search.dt', function () {
    startDOM();
  });
  
  $('#tbl-cxc').DataTable().on("draw.dt",() => {
    startDOM();  
  });
});
