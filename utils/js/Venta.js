import { prepareFormData, response, SAConfig, empty } from "@util";

function saveVenta(instance, $data) {
  let total_Venta = 0;
  let title = instance == "cxp" ? "Cuenta por Pagar" : "Cuenta por Cobrar";
  let closeCall = false;

  $("#tbl-Venta tbody tr").each(function () {
    if ($(this).find("td:eq(1) .monto").val() < 0) {
      Swal.fire("No pueden haber Ventas con valores negativos", "", "error");
      closeCall = true;
      return false;
    } else {
      total_Venta += Number($(this).find("td:eq(1) .monto").val());
    }
  });

  if (total_Venta > $('#frm-Venta input[name="mdeuda"]').val()) {
    Swal.fire(
      `El Total de Ventas a Guardar Supera el Monto de la ${title}`,
      "",
      "error"
    );
    return false;
  }

  if (total_Venta < 0) {
    Swal.fire(
      `No es posible actualizar Ventas con valores negativos`,
      "",
      "error"
    );
    return false;
  }

  if (closeCall) {
    return false;
  }

  SAConfig.title = `¿Está seguro(a) de Actualizar Venta de ${title}?`;

  Swal.fire(SAConfig).then((result) => {
    if (result.value == true) {
      let $frm = $("#form-Venta");
      let frmData = prepareFormData($frm);
      frmData.endpoint = "setVenta";
      frmData.mdeuda = $data["monto"];
      frmData.id = $data["cod"];
      response(`${instance}/`, frmData).then((answer) => {
        if (answer.status == 200) {
          Swal.fire(answer.message, "", "success").then(() => {
            $("#mdl-Venta").modal("hide");
          });
        } else {
          Swal.fire(answer.error, "", "error");
        }
      });
    } else return false;
  });
}

export function loadItems(instance, $cod) {
  response(`${instance}/`, {
    endpoint: "getDetailVenta",
    id: $cod,
  }).then((answer) => {
    if (answer.status == 200) {
      let $tbl = answer.result;
      for (let i = 0; i < $tbl.length; i++) {
        let trVenta = `<tr>
          <td><input class="form-control fec" value="${$tbl[i]["fecha_Venta"]}" required type="date" name="fec[]"></td>
          <td><input class="form-control monto" value="${$tbl[i]["monto_Venta"]}" placeholder="$" required step="0.01" type="number" name="monto[]"></td>
          <td><input placeholder="Describa el concepto de Venta"
          class="form-control" required type="text" maxlength="350" value="${$tbl[i]["concepto_Venta"]}" name="concepto[]"></td>
          <td class="text-center">
            <button onclick="removeVenta(this);" type="button" class="btn btn-sm btn-danger rounded-pill p-2">
              <i class="bi bi-dash-circle m-0"></i>
            </button>
          </td>
        </tr>`;
        $("#tbl-Venta tbody").append(trVenta);
      }
      refreshItemStatus();
    }
  });
}

function refreshItemStatus() {
  let tdeuda = Number($("#mdeuda").attr("mnt"));
  let totalGeneral = 0;
  let tVenta = 0;
  let count = 0;
  $("#tbl-Venta tbody tr").each(function () {
    if (!empty($(this).find("td:eq(1) .monto").val())) {
      tVenta += Number($(this).find("td:eq(1) .monto").val());
      count++;
    }
  });

  $("#countVenta").html(`#Ventas: ${count}`);

  $("#totalVenta").html(
    "Total Venta: $ " +
      tVenta.toLocaleString("es-ES", {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
        useGrouping: true,
      })
  );

  totalGeneral = tdeuda - tVenta;
  $("#total").html(
    "Total Deuda: $ " +
      totalGeneral.toLocaleString("es-ES", {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
        useGrouping: true,
      })
  );
}

export function loadVenta(btn, instance) {
  let $jsonData = atob($(btn).attr("row"));
  let $data = JSON.parse($jsonData)[0];

  let $lblTitle =
    instance == "Cotizacion"
      ? "Registrar Venta desde Cotización"
      : "Registrar Nueva Venta";

  let $lblCount = `<label class="me-2" id="countVenta"></label>`;
  let $lblDeuda = `<label class="text-danger me-2" mnt="${$data.monto}" id="mdeuda">Deuda: $${$data.monto}</label>`;
  let $lblTotalVenta = `<label class="me-2" id="totalVenta"></label>`;
  let $lblTotal = `<label class="fw-bold" id="total"></label>`;

  const $btnSaveVenta = $("#mdl-Venta .modal-footer .btn-primary");
  const $frm = `<form action="#" id="form-Venta" enctype="multipart/form-data" method="POST"></form>`;
  $("#mdl-Venta .modal-footer").html(
    `<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button><button type="button" class="btn btn-primary">Guardar</button>`
  );
  $("#mdl-Venta .modal-footer").prepend([
    $lblDeuda,
    $lblCount,
    $lblTotalVenta,
    $lblTotal,
  ]);
  $("#mdl-Venta .modal-title").text($lblTitle);
  $("#mdl-Venta #form-contained").wrap($frm);
  $("#tbl-Venta tbody").html("");

  loadVentaItems(instance, $data["cod"]);

  $btnSaveVenta.click(() => {
    saveVenta(instance, $data);
  });

  $("#mdl-Venta").on("hide.bs.modal", () => {
    $btnSaveVenta.unbind("click");
    if ($("#form-Venta").length > 0 && $("#form-contained").length > 0) {
      $("#mdl-Venta #form-contained").unwrap();
    }
  });

  $("#mdl-Venta").modal("show");
}

export function addItem() {
  $("#tbl-Venta tbody").append(`
  <tr>
    <td><input class="form-control fec" max="<?php echo date('Y-m-d'); ?>" required type="date" name="fec[]"></td>
    <td><input class="form-control monto" placeholder="$" required step="0.01" type="number" name="monto[]"></td>
    <td><input placeholder="Describa el concepto de Venta"
  class="form-control" required type="text" maxlength="350" name="concepto[]"></td>
  <td class="text-center">
    <button onclick="removeVenta(this);" type="button" class="btn btn-sm btn-danger rounded-pill p-2">
      <i class="bi bi-dash-circle m-0"></i>
    </button>
  </td>
  </tr>
  `);

  $("#tbl-Venta tbody tr td .monto").on("change", function () {
    refreshItemStatus();
  });

  refreshItemStatus();
}

export function removeItem(btn) {
  $(btn).parent("td").parent("tr").remove();

  refreshItemStatus();

  if ($("#tbl-Venta tbody tr").length < 1) {
    return false;
  }
}
