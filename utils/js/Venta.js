import { prepareFormData, response, SAConfig, empty } from "@util";

let productosVentaSeleccionados = [];

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

export function loadVentaItems(instance, $cod) {
  response(`${instance}/`, {
    endpoint: "getDetail",
    id: $cod,
  }).then((answer) => {
    if (answer.status == 200) {
      let detalle = answer.result;
      let stotal = 0.0;

      $("#mdl-venta #items-venta tbody").html("");
      detalle.forEach((item) => {
        let total_item = item.cant * item.monto;
        stotal += total_item;
        $("#mdl-venta #items-venta tbody").append(`
          <tr>
            <td>
              <select class="form-select form-control prod" name="prod[]">
                <option selected value="${item.cod_producto}">${item.nom_producto}</option>
              </select>
            </td>
            <td>
              <input type="number" onchange="calcm(this)" required name="cant[]"
              class="form-control cant" min="1" step="1" value="${item.cant}" >
            </td>
            <td>
              <input type="number" required readonly name="monto[]" value="${item.monto}"
              class="form-control monto" min="0.01" step="0.01">
            </td>
            <td>
              <input type="number" disabled class="form-control titem" value="${total_item}">
            </td>
            <td>
              <button onclick="removeItemVenta(this);" type="button" title="Quitar Item de la Lista"
              class="btn btn-sm btn-danger rounded-pill p-2">
                  <i class="bi bi-dash-circle m-0"></i>
              </button>
            </td>
        </tr>`);
      });

      $("#mdl-venta #stotald").val(
        stotal.toLocaleString("es-ES", {
          minimumFractionDigits: 2,
          maximumFractionDigits: 2,
          useGrouping: true,
        })
      );
    }
  });
}

window.changeCredit = function (me) {
  if ($(me).val() == "C") {
    $("#tasa,#mpago").parent(".input-group").prop("hidden", true);
    $('label[for="fact"]').html(
      '<i class="bi bi-receipt me-1"></i>Nota de Entrega'
    );
    $("#tasa,#mpago").prop("required", false);
    $("#stotal").prop("hidden", true);
    $("#flimit").prop("hidden", false);
    $('#flimite,label[for="flimite"]').prop("hidden", false);
    $('#iva,label[for="iva"]').prop("hidden", false);
    $("#flimite").prop("required", true);

    $("#iva").prop("checked", false);
    $("#iva").val("off");
    //ULTIMA NOTA CONSECUTIVA
    $("#fact").val(lnota);
  } else {
    if ($(me).val() == "FD") {
      $('label[for="fact"]').html('<i class="bi bi-receipt me-1"></i>Factura');
    }

    $("#tasa,#mpago").parent(".input-group").prop("hidden", false);
    $("#tasa,#mpago").prop("required", true);
    $("#stotal").prop("hidden", false);
    $("#flimit").prop("hidden", true);
    $("#flimite").prop("required", false);
    var vtasa = Number($("#tasa").val());
    var stotalb = 0;
    $(".titem").each(function () {
      stotald += Number($(this).val());
      stotalb += stotald * vtasa;
    });

    if ($(me).val() == "F" || $(me).val() == "FD") {
      $("#iva").prop("checked", true);
      $("#iva").val("on");
      $("#frm-venta #fact").val(null);
      $('#iva,label[for="iva"]').prop("hidden", true);
      $('#flimite,label[for="flimite"]').prop("hidden", false);
    } else {
      $("#iva").prop("checked", false);
      $("#iva").val("off");
    }

    if ($(me).val() == "F") {
      $("#tasa,#mpago").parent(".input-group").prop("hidden", true);
      $('label[for="fact"]').html('<i class="bi bi-receipt me-1"></i>Factura');
      $("#tasa,#mpago").prop("required", false);
      $("#stotal").prop("hidden", true);
      $("#flimit").prop("hidden", false);
      $("#flimite").prop("required", true);
    }

    if ($(me).val() == "D") {
      $("#fact").val(lnota);
      $("#flimit").prop("hidden", false);
      $('#iva,label[for="iva"]').prop("hidden", false);
      $('#flimite,label[for="flimite"]').prop("hidden", false);
    }
    $("#stotald").val(
      stotalb.toLocaleString("es-ES", {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
        useGrouping: true,
      })
    );
  }
};

function calcm(me) {
  let stotald = 0.0;

  let precio = $(me).parent("td").parent("tr").find("td .monto").val();

  let titem = $(me).parent("td").parent("tr").find("td .titem");
  let item = precio * Number($(me).val());
  titem.val(item.toFixed(2));

  let vtasa = Number($("#tasa").val());

  $(".titem").each(function () {
    stotald += Number($(this).val());
  });
  let stock = $(me).parent("td").parent("tr").find("td .stock");
  let vls = Number($(stock).attr("stk"));
  stock.val(vls - Number($(me).val()));

  $("#stotald").val(
    stotald.toLocaleString("es-ES", {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2,
      useGrouping: true,
    })
  );
}

function refreshItemStatus() {
  let tVenta = 0;
  let count = 0;
  $("#items-venta tbody tr").each(function () {
    if (!empty($(this).find("td:eq(3) .titem").val())) {
      tVenta += Number($(this).find("td:eq(3) .titem").val());
      count++;
    }
  });

  $("#stotald").val(
    tVenta.toLocaleString("es-ES", {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2,
      useGrouping: true,
    })
  );
}

function initSelectCli() {
  $(".cli").select2({
    theme: "bootstrap-5",
    dropdownParent: $("#mdl-venta"),
    ajax: {
      url: "../../api/cliente/",
      type: "post",
      dataType: "json",
      delay: 250,
      data: function (params) {
        return {
          endpoint: "getListOptionCli",
          lk: params.term,
        };
      },
      processResults: function (response) {
        return { results: response };
      },
    },
    language: {
      searching: function () {
        return "Buscando...";
      },
      noResults: function () {
        return "No se Encontraron Resultados";
      },
    },
  });
}

window.initSelectProdVent = function () {
  console.log("cargar select");
  $("#items-venta .prod").select2({
    theme: "bootstrap-5",
    dropdownParent: $("#mdl-venta"),
    ajax: {
      url: "../../api/inventario/",
      type: "POST",
      dataType: "json",
      delay: 250,
      data: function (params) {
        return {
          endpoint: "getOptionProduct",
          lk: params.term,
        };
      },
      processResults: function (response) {
        return { results: response };
      },
      cache: true,
    },
    language: {
      searching: function () {
        return "Buscando...";
      },
      noResults: function () {
        return "No se Encontraron Resultados";
      },
    },
  });

  $("#items-venta .prod").change(function (e) {
    var nuevoProducto = $(this).val();
    var filaActual = $(this).closest("tr");
    var filaIndex = filaActual.index();
    var productosSeleccionadosTemp = [...productosVentaSeleccionados]; // Crear una copia temporal del array de productos seleccionados

    // Eliminar el producto previamente seleccionado de la lista de productos seleccionados
    var productoAnterior = filaActual.data("producto-seleccionado");
    if (productoAnterior) {
      var indexProductoAnterior =
        productosSeleccionadosTemp.indexOf(productoAnterior);
      if (indexProductoAnterior > -1) {
        productosSeleccionadosTemp.splice(indexProductoAnterior, 1);
      }
    }

    // Validar si el producto ya ha sido seleccionado en otra fila
    if (productosSeleccionadosTemp.includes(nuevoProducto)) {
      Swal.fire(
        "¡El producto seleccionado ya ha sido agregado en otra fila!",
        "",
        "warning"
      );
      $(this).val(productoAnterior).trigger("change"); // Revertir la selección al producto anterior
      return;
    }

    // Actualizar el producto seleccionado en la fila actual
    filaActual.data("producto-seleccionado", nuevoProducto);

    // Actualizar el array de productos seleccionados después de la validación
    if (productoAnterior) {
      var indexProductoAnterior =
        productosVentaSeleccionados.indexOf(productoAnterior);
      if (indexProductoAnterior > -1) {
        productosVentaSeleccionados.splice(indexProductoAnterior, 1);
      }
    }
    if (nuevoProducto) {
      var vl = $(this).val();
      response("inventario/", {
        endpoint: "getProductPrices",
        id: vl,
      }).then((data) => {
        let $rs = data.result[0];
        if (sessionStorage.getItem("tipo") > 1) {
          var t = sessionStorage.getItem("tipo");
          $(this)
            .parent("td")
            .parent("tr")
            .find("td .monto")
            .val($rs["pventa" + t]);
        } else {
          $(this)
            .parent("td")
            .parent("tr")
            .find("td .monto")
            .val($rs["pventa"]);
        }
        /* solo para ventas*/

        $(this)
          .parent("td")
          .parent("tr")
          .find("td .cant")
          .attr("max", $rs["stockreal"] <= 0 ? $rs["stockreal"] : 0);

        productosVentaSeleccionados.push(nuevoProducto);
      });
    }
  });
};

export function loadVenta(btn, instance) {
  let $jsonData = atob($(btn).attr("row"));
  let $data = JSON.parse($jsonData)[0];

  let $lblTitle =
    instance == "Cotizacion"
      ? "Registrar Venta desde Cotización"
      : "Registrar Nueva Venta";

  const $btnSaveVenta = $("#mdl-venta .modal-footer .btn-primary");
  const $frm = `<form action="#" id="form-Venta" enctype="multipart/form-data" method="POST"></form>`;
  $("#mdl-venta .modal-footer").html(
    `<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
    <button type="button" class="btn btn-primary">Guardar</button>`
  );

  $("#mdl-venta .modal-title").text($lblTitle);
  $("#mdl-venta #form-contained").wrap($frm);
  $("#tbl-venta tbody").html("");

  loadVentaItems(instance, $data["cod"]);

  $btnSaveVenta.click(() => {
    saveVenta(instance, $data);
  });

  $("#mdl-venta").on("hide.bs.modal", () => {
    $btnSaveVenta.unbind("click");
    if ($("#form-Venta").length > 0 && $("#form-contained").length > 0) {
      $("#mdl-Venta #form-contained").unwrap();
    }
  });

  initSelectCli();
  initSelectProdVent();

  $("#mdl-venta").modal("show");
}

window.addItemVenta = function () {
  $("#mdl-venta #items-venta tbody").append(`
    <tr>
        <td>
          <select class="form-select form-control prod" name="prod[]"></select>
        </td>
        <td>
          <input type="number" onchange="calcm(this)" required name="cant[]"
          class="form-control cant" min="1" step="1" value="">
        </td>
        <td>
          <input type="number" required readonly name="monto[]" value=""
          class="form-control monto" min="0.01" step="0.01">
        </td>
        <td>
          <input type="number" readonly class="form-control titem" value="">
        </td>
        <td>
          <button onclick="removeItemVenta(this);" type="button" title="Quitar Item de la Lista"
          class="btn btn-sm btn-danger rounded-pill p-2">
              <i class="bi bi-dash-circle m-0"></i>
          </button>
        </td>
    </tr>
  `);

  $("#items-venta tbody tr td .monto").on("change", function () {
    refreshItemStatus();
  });

  refreshItemStatus();
};

window.removeItemVenta = function (btn) {
  let optionSelect = $(btn).parent("td").parent("tr").find("td .prod").val();
  productosVentaSeleccionados.splice(
    productosVentaSeleccionados.indexOf(optionSelect)
  );
  $(btn).parent("td").parent("tr").remove();

  refreshItemStatus();
  if ($("#item-venta  tbody tr").length < 1) {
    return false;
  }
};
