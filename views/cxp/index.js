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
  let $btnCxpConfirmed = `<button class="btn btn-outline-primary ms-2 rounded-pill" 
    onclick="loadCxpConfirmed()" type="button">
    <span><i class="bi bi-check text-success me-1"></i>Ver cuentas Confirmadas</span>
  </button>`;

  $("#mdl-compra .modal-title").prepend('<i class="mb-2 bx bx-check"></i>');
  $(`.dt-buttons button[onclick="loadCxpConfirmed()"]`).remove();
  $(".dt-buttons").append($btnCxpConfirmed);

  $("#tbl-cxp tbody tr").each(function () {
    var estadoCell = $(this).find("td:last");

    if (estadoCell.text() === "V") {
      estadoCell.html('<span class="badge bg-danger">Vencida</span>');
    } else if (estadoCell.text() === "P") {
      estadoCell.html(
        '<span class="badge bg-warning text-dark">Pendiente</span>'
      );
    }
  });

  alterCxpControl();
};

window.loadCxpConfirmed = function () {
  response("cxp/", { endpoint: "getCxpSolvent" }).then((data) => {
    let table = $("#tbl-solv").find("tbody");
    data.result.forEach((element) => {
      let comprobante =
        empty(element.nota) == false ? "N" + element.nota : "F" + element.fact;
      table.append(`<tr vt="${element.compra}">
        <td>${comprobante}</td>
        <td>${element.prov}</td>
        <td>${element.cobro}</td>
        <td>$${element.monto}</td>
        <td><span class="badge text-light bg-success">Solvente</span></td>
        <td onclick="revertCxpConfirmed(this)">
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

window.revertCxpConfirmed = function (btn) {
  let compra = $(btn).parent("tr").attr("vt");
  let comp = $(btn).parent("tr").find("td:first").html().trim();

  SAConfig.title = `¿Está seguro que desea Revertir la Cuenta ${comp}? \n
   Esta pasará a la lista de No confirmadas (pendientes o vencidas)`;

  Swal.fire(SAConfig).then((result) => {
    if (result.value == true) {
      response("cxp/", { endpoint: "revertCxpSolvent", idCompra: compra }).then(
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

window.confirmCxp = function (btn) {
  let data = atob($(btn).attr("row"));
  let $dt = JSON.parse(data)[0];

  const formCxp = `<form id="form-compra" enctype="multipart/form-data" action="#"></form>`;
  $("#mdl-compra #form-contained").wrap(formCxp);
  $("#optprov").html(`<option value="${$dt["idp"]}">${$dt["nom"]}</option>`);
  $("#freg").val(moment($dt["fechac"]).format("YYYY-MM-DD"));
  $(".desc").val($dt["concepto"]);
  $("#fact").val($dt["nota"]);

  response("compra/", {
    endpoint: "getDetailTable",
    id: $dt["compra"],
  }).then((answer) => {
    if (answer.status == 200) {
      let $tbl = answer.result;

      $("#tbl-compra tbody").html("");
      let stotald = 0;
      let stotalb = 0;
      for (let i = 0; i < $tbl.length; i++) {
        $("#tbl-compra tbody").append(
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

      let $btnConfirmCxp = $("#mdl-compra .modal-footer .btn-primary");
      $btnConfirmCxp.text("Confirmar Pago");

      $btnConfirmCxp.click(() => {
        SAConfig.title = "¿Está seguro(a) de Confirmar esta Cuenta por Pagar?";

        Swal.fire(SAConfig).then((result) => {
          if (result.value == true) {
            let $frm = $("#form-compra");
            let frmData = prepareFormData($frm);
            frmData.endpoint = "confirmCompra";
            frmData.id = $dt["compra"];

            response("cxp/", frmData).then((res) => {
              if (res.status == 200) {
                Swal.fire(res.message, "", "success").then(() => {
                  refreshTable(startDOM);
                  $("#mdl-compra").modal("hide");
                });
              } else {
                Swal.fire(res.error, "", "error");
              }
            });
          } else return false;
        });
      });

      $("#mdl-compra").on("hide.bs.modal", () => {
        $("#totalabono,#countabono").remove();
        $btnConfirmCxp.unbind("click");
        $("#mdl-compra #form-contained").unwrap();
      });

      $("#mdl-compra").modal("show");
    } else {
      Swal.fire("Error al cargar Cuenta", "", "error");
    }
  });
};

window.loadAbono = loadAbono;

window.alterCxpControl = function () {
  let btnAddPay = $('#dt-controls button[control="edit"]');
  let btnConfirm = $('#dt-controls button[control="view"]');

  window.addAbono = addAbono;
  window.removeAbono = removeAbono;

  $("#tbl-cxp").on("select.dt deselect.dt", () => {
    btnConfirm.html(`<i class="m-auto bi bi-check text-white"></i>`);
    btnAddPay.html(`<i class="m-auto bi bi-plus text-white"></i>`);
  });

  btnConfirm.click(() => {
    confirmCxp(btnConfirm);
  });

  btnAddPay.click(() => {
    loadAbono(btnAddPay, "cxp");
  });
};

loadComponents();
let $hideCols = ["cod", "idp", "fechac", "fecha"];
loadCrud("cxp", $hideCols);

$(function () {
  startDOM();
  $('#tbl-cxp').DataTable().on("draw.dt",() => {
    startDOM();  
  });

  $('#tbl-cxp').DataTable().on("search.dt",() => {
    startDOM();  
  });
});
