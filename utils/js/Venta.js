import { prepareFormData, response, SAConfig, empty, getTasa } from "@util";

let productosVentaSeleccionados = [];

function saveVenta(instance, $data) {
  let total_Venta = 0;
  let title = instance == "Cotizacion" ? "Venta desde Cotización" : "Venta";
  let closeCall = false;

  $("#items-venta tbody tr").each(function () {
    if ($(this).find("td:eq(1) .monto").val() < 0) {
      Swal.fire("No pueden haber Ventas con valores negativos", "", "error");
      closeCall = true;
      return false;
    } else {
      total_Venta += Number($(this).find("td:eq(1) .monto").val());
    }
  });

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

  SAConfig.title = `¿Está seguro(a) de Confirmar ${title}?`;

  let $frm = $("#items-venta").closest("form");
  if (!$frm[0].checkValidity()) {
    $frm[0].reportValidity();
    return;
  }

  Swal.fire(SAConfig).then((result) => {
    if (result.value == true) {
      let frmData = prepareFormData($frm);

      response(`venta/`, frmData).then((answer) => {
        if (answer.status == 200) {
          Swal.fire(answer.message, "", "success").then(() => {
            //document.location.reload();
          });
        } else {
          Swal.fire(answer.error, "", "error");
        }
      });
    } else return false;
  });
}

export function loadVentaItems(instance, $cod, $type = false) {
  response(`${instance}/`, {
    endpoint: "getDetail",
    id: $cod,
  }).then((answer) => {
    if (answer.status == 200) {
      console.log(answer);
      let dataParent = answer.result.master;
      let detalle = answer.result.detail;
      let stotal = 0.0;
      let stotald = 0.0;

      let cliOption = `<option selected value="${dataParent.id_cliente}">${dataParent.razon_social}</option>`;
      $("#optcli").append(cliOption);
      if ($("#mdl-venta .cli").data("select2")) {
        $("#mdl-venta .cli").select2("destroy");
      }
      findReferClient(dataParent.id_cliente);
      initSelectClient();

      $("#freg").val(moment.utc(dataParent.freg).format("YYYY-MM-DD"));
      let hour = moment.utc(dataParent.freg).format("HH:mm");
      $("#ftime").val(hour);

      $("#desc").val(dataParent.descripcion);
      $("#tventa").val(dataParent.tipo_venta).trigger("change");
      $("#mpago").val(dataParent.forma_pago).trigger("change");

      $("#mdl-venta #items-venta tbody").html("");
      detalle.forEach((item) => {
        let total_item = item.cant * item.monto;
        stotal += total_item * Number($("#tasa").val());
        stotald += total_item;
        $("#mdl-venta #items-venta tbody").append(`
          <tr>
            <td>
              <select class="form-select form-control prod" name="prod[]">
                <option selected value="${item.cod_producto}">${
          item.nom_producto
        }</option>
              </select>
            </td>
            <td>
              <input type="number" onchange="calcm(this)" required name="cant[]"
              class="form-control cant" min="1" step="1" value="${item.cant}" >
            </td>
            <td>
              <input type="number" required readonly name="monto[]" value="${
                item.monto
              }"
              class="form-control monto" min="0.01" step="0.01">
            </td>
            <td>
              <input type="number" disabled class="form-control titem" value="${total_item}">
            </td>
            ${
              $type == "edit" || $type == false
                ? `
            <td>
              <button onclick="removeItemVenta(this);" type="button" title="Quitar Item de la Lista"
              class="btn btn-sm btn-danger rounded-pill p-2">
                  <i class="bi bi-dash-circle m-0"></i>
              </button>
            </td>`
                : ``
            }
        </tr>`);
      });

      $("#mdl-venta #stotal").val(
        stotal.toLocaleString("es-ES", {
          minimumFractionDigits: 2,
          maximumFractionDigits: 2,
          useGrouping: true,
        })
      );

      $("#mdl-venta #stotald").val(
        stotald.toLocaleString("es-ES", {
          minimumFractionDigits: 2,
          maximumFractionDigits: 2,
          useGrouping: true,
        })
      );

      if ($type == "edit" || $type == false) {
        $(`#mdl-venta #agregarFila`).prop("hidden", false);
        initSelectProdVent();
      }

      if ($type == "view") {
        $(`#mdl-venta #agregarFila`).prop("hidden", true);
        $(`#mdl-venta input, #mdl-venta select`).prop("readonly", true);
        $(`#mdl-venta input, #mdl-venta select`).prop("disabled", true);
      } else if ($type == "edit") {
        $(`#mdl-venta input[name="endpoint"]`).val("update");
        $(`#mdl-venta input, #mdl-venta select`).prop("readonly", false);
        $(`#mdl-venta input, #mdl-venta select`).prop("disabled", false);
        $("#stotal,#stotald,#tasa").prop("disabled", true);
      }
    }
  });
}

window.calcm = function (me) {
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

  let stotalb = stotald * vtasa;

  $("#stotald").val(
    stotald.toLocaleString("es-ES", {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2,
      useGrouping: true,
    })
  );

  $("#stotal").val(
    stotalb.toLocaleString("es-ES", {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2,
      useGrouping: true,
    })
  );
};

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

function findReferClient(clientId) {
  $("#refer").text("");
  response(`cliente/`, {
    endpoint: "getReferClient",
    id: clientId,
  }).then((answer) => {
    if (answer.status == 200) {
      $("#refer").text(`Referido por: ${answer.result.ref}`);
    }
  });
}

function initSelectClient() {
  $("#mdl-venta .cli").select2({
    theme: "bootstrap-5",
    dropdownParent: $("#mdl-venta"),
    ajax: {
      url: "../../api/cliente/",
      type: "post",
      dataType: "json",
      delay: 250,
      data: function (params) {
        return {
          endpoint: "getListOptionClient",
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

  $("#mdl-venta .cli").on("change", function (e) {
    if ($(`.menu-items .menu-title:contains('Vendedor')`).length > 0) {
      let clientId = $(this).val();
      findReferClient(clientId);
    }
  });
}

window.initSelectProdVent = function () {
  $("#items-venta tbody tr td .prod").select2({
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

export async function loadVenta(btn, instance, $type = false) {
  let $jsonData = atob($(btn).attr("row"));
  let $data = JSON.parse($jsonData)[0];
  let $tasaActual = await getTasa();
  //#const lnota = 5;

  window.changeCredit = function (me) {
    if ($(me).val() === "C" || $(me).val() === "FC") {
      $("#tasa,#mpago").parent(".input-group").prop("hidden", true);
      $('label[for="fact"]').html(
        '<i class="bi bi-receipt me-1"></i>Nota de Entrega'
      );
      $("#tasa,#mpago").prop("required", false);
      $("#flimit").prop("hidden", false);
      $('#flimite,label[for="flimite"]').prop("hidden", false);
      $('#iva,label[for="iva"]').prop("hidden", false);
      $("#flimite").prop("required", true);

      $("#iva").prop("checked", false);
      $("#iva").val("off");
      //ULTIMA NOTA CONSECUTIVA
      //$("#fact").val(lnota);
    } else {
      $("#tasa,#mpago").parent(".input-group").prop("hidden", false);
      $("#tasa,#mpago").prop("required", true);
      $("#flimit").prop("hidden", true);
      $("#flimite").prop("required", false);

      if ($(me).val() == "D" || $(me).val() === "FD") {
        //$("#fact").val(lnota); #buscar ultima nota de entrega a generar
        $('#iva,label[for="iva"]').prop("hidden", false);
        $('#flimite,label[for="flimite"]').prop("hidden", false);
      }
    }

    if ($(me).val() !== "C" && $(me).val() !== "D") {
      $('label[for="fact"]').html('<i class="bi bi-receipt me-1"></i>Factura');
    } else {
      $('label[for="fact"]').html(
        '<i class="bi bi-receipt me-1"></i>Nota de Entrega'
      );
    }
  };

  let $lblTitle =
    instance == "Cotizacion"
      ? "Registrar Venta desde Cotización"
      : "Registrar Nueva Venta";

  if (instance == "Venta" && !empty($type)) {
    $lblTitle = $type == "edit" ? "Editar Venta" : "Ver Venta";
  }

  let saveButton =
    $type == "edit" || $type == false
      ? `<button type="button" id="save" class="btn btn-primary">Guardar</button>`
      : ``;
  const $frm = `<form action="#" id="form-Venta" enctype="multipart/form-data" method="POST"></form>`;

  $("#mdl-venta .modal-footer").html(
    `<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
    ${saveButton}`
  );

  $("#mdl-venta .modal-title").text($lblTitle);
  $("#mdl-venta #form-contained").wrap($frm);

  $("#mdl-venta #tasa").val($tasaActual);

  if (instance == "Cotizacion" || $type != false) {
    loadVentaItems(instance, $data["cod"], $type);
  } else {
    initSelectClient();
    $(`#mdl-venta #agregarFila`).prop("hidden", false);
    initSelectProdVent();
  }

  $("#mdl-venta").on("hide.bs.modal", () => {
    $("#mdl-venta .modal-footer .btn-primary").unbind("click");
    if ($("#form-Venta").length > 0 && $("#form-contained").length > 0) {
      $("#mdl-Venta #form-contained").unwrap();
    }
  });

  $("#mdl-venta #save").click(function () {
    if ($type == "edit") {
      let $frm = $("#items-venta").closest("form");
      $frm.append(`<input type="hidden" name="id" value="${$data["cod"]}"/>`);
    }
    saveVenta(instance, $data);
  });

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
  initSelectProdVent();
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
