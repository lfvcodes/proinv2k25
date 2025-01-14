import { response, SAConfig } from "@util";

function saveAbono(instance, $data) {
  let total_abono = 0;
  let title = instance == "cxp" ? "Cuenta por Pagar" : "Cuenta por Cobrar";
  let closeCall = false;
  $("#tbl-abono tbody tr").each(function () {
    if ($(this).find("td:eq(1) .monto").val() < 0) {
      Swal.fire("No pueden haber abonos con valores negativos", "", "error");
      closeCall = true;
      return false;
    } else {
      total_abono += Number($(this).find("td:eq(1) .monto").val());
    }
  });

  if (total_abono > $('#frm-abono input[name="mdeuda"]').val()) {
    Swal.fire(
      `El Total de Abonos a Guardar Supera el Monto de la ${title}`,
      "",
      "error"
    );
    return false;
  }

  if (total_abono < 0) {
    Swal.fire(
      `No es posible actualizar abonos con valores negativos`,
      "",
      "error"
    );
    return false;
  }

  if (closeCall) {
    return false;
  }

  SAConfig.title = `¿Está seguro(a) de Agregar/Actualizar Abono de ${title}?`;

  Swal.fire(SAConfig).then((result) => {
    if (result.value == true) {
      let $frmabono = $("#form-abono")[0];
      let frmData = new FormData($frmabono);
      frmData.append("endpoint", "setAbono");
      frmData.append("mdeuda", $data["monto"]);
      frmData.append("id", $data["cod"]);
      console.log(frmData);
      response(`${instance}/`, frmData);
    } else return false;
  });
}

function loadAbonoItems(instance, $cod) {
  response(`${instance}/`, {
    endpoint: "getDetailAbono",
    id: $cod,
  }).then((answer) => {
    if (answer.status == 200) {
      let $tbl = answer.result;

      let counterAbono = 0;
      let tabono = 0.0;
      for (let i = 0; i < $tbl.length; i++) {
        let trAbono = `<tr>
          <td><input class="form-control fec" value="${$tbl[i]["fecha_abono"]}" required type="date" name="fec[]"></td>
          <td><input class="form-control monto" value="${$tbl[i]["monto_abono"]}" placeholder="$" required step="0.01" type="number" name="monto[]"></td>
          <td><input placeholder="Describa el concepto de Abono"
          class="form-control" required type="text" maxlength="350" value="${$tbl[i]["concepto_abono"]}" name="concepto[]"></td>
          <td class="text-center">
            <button onclick="removeAbono(this);" type="button" class="btn btn-sm btn-danger rounded-pill p-2">
              <i class="bi bi-dash-circle m-0"></i>
            </button>
          </td>
        </tr>`;
        $("#tbl-abono tbody").append(trAbono);
        tabono += $tbl[i]["monto_abono"];
        counterAbono++;
      }
      $("#countabono").html(`#Abonos: ${counterAbono}`);
      $("#totalabono").html(
        "Total: $ " +
          tabono.toLocaleString("es-ES", {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
            useGrouping: true,
          })
      );
    }
  });
}

export function loadAbono(btn, instance) {
  let $jsonData = atob($(btn).attr("row"));
  let $data = JSON.parse($jsonData)[0];

  let title = instance == "cxp" ? "Cuentas por Pagar" : "Cuentas por Cobrar";
  let $lblTitle = `Registrar Abono de Pago de ${title} ${$data.compra} (${$data.nom})`;
  let $lblTotal = `<label class="fw-bold" id="totalabono"></label>`;
  let $lblCount = `<label class="fw-bold me-2" id="countabono"></label>`;
  const $btnSaveAbono = $("#mdl-abono .modal-footer .btn-primary");
  const $frm = `<form id="form-abono" method="POST"></form>`;

  $("#mdl-abono .modal-footer").prepend([$lblCount, $lblTotal]);
  $("#mdl-abono .modal-title").text($lblTitle);
  $("#mdl-abono #form-contained").wrap($frm);
  $("#tbl-abono tbody").html("");

  loadAbonoItems(instance, $data["cod"]);

  $btnSaveAbono.click(() => {
    saveAbono(instance, $data);
  });

  $("#mdl-abono").on("hide.bs.modal", () => {
    $("#totalabono,#countabono").remove();
    $btnSaveAbono.unbind("click");
    $frm.unwrap();
  });

  $("#mdl-abono").modal("show");
}

export function addAbono() {
  $("#tbl-abono tbody").append(`
  <tr>
    <td><input class="form-control fec" max="<?php echo date('Y-m-d'); ?>" required type="date" name="fec[]"></td>
    <td><input class="form-control monto" placeholder="$" required step="0.01" type="number" name="monto[]"></td>
    <td><input placeholder="Describa el concepto de Abono"
  class="form-control" required type="text" maxlength="350" name="concepto[]"></td>
  <td class="text-center">
    <button onclick="removeAbono(this);" type="button" class="btn btn-sm btn-danger rounded-pill p-2">
      <i class="bi bi-dash-circle m-0"></i>
    </button>
  </td>
  </tr>
  `);

  $("#tbl-abono tbody tr td .monto").change(function (e) {
    e.preventDefault();
    var tabono = 0;
    $("#tbl-abono tbody tr").each(function () {
      tabono += Number($(this).find("td:eq(1) .monto").val());
    });
    $("#totalabono").html(
      "Total: $ " +
        tabono.toLocaleString("es-ES", {
          minimumFractionDigits: 2,
          maximumFractionDigits: 2,
          useGrouping: true,
        })
    );
  });

  var tabono = 0;
  $("#tbl-abono tbody tr").each(function () {
    tabono += Number($(this).find("td:eq(1) .monto").val());
  });
  $("#totalabono").html(
    "Total: $ " +
      tabono.toLocaleString("es-ES", {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
        useGrouping: true,
      })
  );
}

export function removeAbono(btn) {
  var tabono = 0;
  $(btn).parent("td").parent("tr").remove();
  $("#tbl-abono tbody tr").each(function () {
    tabono += Number($(this).find("td:eq(1) .monto").val());
  });
  $("#totalabono").html(
    "Total: $ " +
      tabono.toLocaleString("es-ES", {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
        useGrouping: true,
      })
  );

  if ($("#tbl-abono tbody tr").length < 1) {
    return false;
  }
}
