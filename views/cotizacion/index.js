import { loadComponents, response, empty, showModalDocument } from "@util";
import { loadCrud } from "@StartCrud";
import { loadVenta } from "@Venta";

const hiddenCols = ["cod", "registro"];
let productosSeleccionados = [];
loadComponents();
loadCrud("cotizacion", hiddenCols, true);

function startDOM() {
  let dataRow = $('#dt-controls button[control="view"]').attr("row");
  const $btnCopyVent = `<button type="button" control="copy" id="btn-copy" onclick="copyToVenta(this)" class="btn rounded-circle p-2 btn-primary controls">
               <i class="m-auto bx bx-copy text-white"></i>
            </button>`;

  const $btnPrintCot = `<button type="button" control="pdf" id="btn-print-pdf" onclick="printPdfCot(this)"
   class="btn rounded-circle p-2 btn-primary controls ms-1">
      <i class="m-auto bi bi-file-pdf text-white fw-bold"></i>
  </button>`;

  let htmlControl =
    $btnCopyVent + $btnPrintCot + sessionStorage.getItem("controls");
  $("#dt-controls").html(htmlControl);
  $("#dt-controls button").attr("row", dataRow);
  alterControl();

  $("#mdl-cotizacion").on("hidden.bs.modal", () => {
    $("#mdl-cotizacion input, #mdl-cotizacion select").prop("readonly", false);
    $("#mdl-cotizacion input, #mdl-cotizacion select").val("");
    $(`#mdl-cotizacion input[name="endpoint"]`).val("add");
    $("#mdl-cotizacion #agregarFila").prop("hidden", false);
    $("#mdl-cotizacion #stotald").val("");
    $("#mdl-cotizacion .cli").html("");
    $("#tbl-cond tbody").html("");
    initSelectCli();
  });
}

window.calcm = function (me) {
  let precio = $(me).parent("td").parent("tr").find("td .monto").val();
  let titem = $(me).parent("td").parent("tr").find("td .titem");
  let item = precio * Number($(me).val());
  titem.val(item.toFixed(2));
  refreshItemStatus();
};

function initSelectCli() {
  $(".cli").select2({
    theme: "bootstrap-5",
    dropdownParent: $("#mdl-cotizacion"),
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
}

function initSelectCot() {
  $("#mdl-cotizacion #tbl-cond .prod").select2({
    theme: "bootstrap-5",
    dropdownParent: $("#mdl-cotizacion"),
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

  $("#mdl-cotizacion #tbl-cond .prod").change(function (e) {
    var nuevoProducto = $(this).val();
    var filaActual = $(this).closest("tr");
    var filaIndex = filaActual.index();
    var productosSeleccionadosTemp = [...productosSeleccionados]; // Crear una copia temporal del array de productos seleccionados

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
        productosSeleccionados.indexOf(productoAnterior);
      if (indexProductoAnterior > -1) {
        productosSeleccionados.splice(indexProductoAnterior, 1);
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
        /*
        $(this)
          .parent("td")
          .parent("tr")
          .find("td .cant")
          .attr("max", $rs["stockreal"] <= 0 ? $rs["stockreal"] : 0);
          */
        productosSeleccionados.push(nuevoProducto);
      });
    }
  });
}

window.refreshItemStatus = function () {
  let totalCot = 0;
  let count = 0;
  $("#mdl-cotizacion #tbl-cond tbody tr").each(function () {
    if (!empty($(this).find("td:eq(3) .titem").val())) {
      totalCot += Number($(this).find("td:eq(3) .titem").val());
      count++;
    }
  });

  $("#countItems").html(`#Items: ${count}`);

  $("#mdl-cotizacion #stotald").val(
    totalCot.toLocaleString("es-ES", {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2,
      useGrouping: true,
    })
  );
};

window.addCot = function () {
  $("#tbl-cond tbody").append(`
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
          <button onclick="removeCot(this);" type="button" title="Quitar Item de la Lista"
          class="btn btn-sm btn-danger rounded-pill p-2">
              <i class="bi bi-dash-circle m-0"></i>
          </button>
        </td>
    </tr>
    `);

  $("#tbl-cond tbody tr td .monto").on("change", function () {
    refreshItemStatus();
  });

  refreshItemStatus();
  initSelectCot();
};

window.removeCot = function (btn) {
  let optionSelect = $(btn).parent("td").parent("tr").find("td .prod").val();
  productosSeleccionados.splice(productosSeleccionados.indexOf(optionSelect));
  $(btn).parent("td").parent("tr").remove();

  refreshItemStatus();
  if ($("#tbl-cond tbody tr").length < 1) {
    return false;
  }
};

window.copyToVenta = function (btn) {
  loadVenta(btn, "Cotizacion");
};

window.printPdfCot = function (btn) {
  let row = atob($(btn).attr("row"));
  let dataRow = JSON.parse(row)[0];
  let urlPdf = `pdf/cotizacion.php?rc=${dataRow.cod}`;
  showModalDocument(urlPdf, `Cotización N° ${dataRow.cod}`);
};

async function getCotizacionData(idCotizacion) {
  try {
    const data = await response("cotizacion/", {
      endpoint: "getCotizacion",
      idCot: idCotizacion,
    });
    return data.result;
  } catch (error) {
    console.error("Error al obtener cotización:", error);
    return null; // O maneja el error de otra forma según tus necesidades
  }
}

function loadCotizacion(data, $type) {
  let cotizacion = data.cotizacion;
  let detalle = data.detail;
  $("#mdl-cotizacion #optcliente").html("");
  $("#mdl-cotizacion #optcliente").append(
    `<option value="${cotizacion.id_cliente}">${cotizacion.razon_social}</option>`
  );

  $("#mdl-cotizacion #fex").val(data.cotizacion.fvencimiento);
  $("#mdl-cotizacion #freg").val(data.cotizacion.fregistro);
  $("#mdl-cotizacion #ftime").val(data.cotizacion.tregistro);
  $("#mdl-cotizacion #desc").val(data.cotizacion.descripcion);
  $("#mdl-cotizacion #ncot").val(data.cotizacion["cod_nota"]);

  $("#mdl-cotizacion #tbl-cond tbody").html("");
  let stotal = 0.0;
  detalle.forEach((item) => {
    let total_item = item.cant * item.monto;
    stotal += total_item;
    $("#mdl-cotizacion #tbl-cond tbody").append(`
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
        <td>
          ${
            $type == "edit"
              ? `
          <button onclick="removeCot(this);" type="button" title="Quitar Item de la Lista"
          class="btn btn-sm btn-danger rounded-pill p-2">
              <i class="bi bi-dash-circle m-0"></i>
          </button> `
              : ``
          }
        </td>
    </tr>`);
    if ($type == "edit") {
      initSelectCli(cotizacion);
      initSelectCot();
    }
  });

  $("#mdl-cotizacion #stotald").val(
    stotal.toLocaleString("es-ES", {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2,
      useGrouping: true,
    })
  );

  if ($type == "view") {
    $("#mdl-cotizacion #agregarFila").prop("hidden", true);
    $("#mdl-cotizacion input, #mdl-cotizacion select").prop("readonly", true);
  } else if ($type == "edit") {
    $(`#mdl-cotizacion input[name="endpoint"]`).val("update");
    $("#mdl-cotizacion #agregarFila").prop("hidden", false);
    $("#mdl-cotizacion input, #mdl-cotizacion select").prop("readonly", false);
  }
}

window.alterControl = function () {
  const btnEditCot = $('#dt-controls button[control="edit"]');
  const btnViewCot = $('#dt-controls button[control="view"]');

  btnViewCot.click(async () => {
    let row = JSON.parse(atob(btnViewCot.attr("row")))[0];
    let data = await getCotizacionData(row.id_cotizacion);

    if (data) {
      loadCotizacion(data, "view");
    } else {
      console.log("error en respuesta");
    }
  });

  btnEditCot.click(async () => {
    let row = JSON.parse(atob(btnEditCot.attr("row")))[0];
    let data = await getCotizacionData(row.id_cotizacion);

    if (data) {
      loadCotizacion(data, "edit");
    } else {
      console.log("error en respuesta");
    }
  });
};

$(function () {
  sessionStorage.setItem("controls", $("#dt-controls").html());
  let table = $("#tbl-cotizacion")
    .DataTable()
    .on("draw.dt search.dt select.dt deselect.dt length.dt", () => {
      let selectedRows = table.rows({ selected: true });
      if (selectedRows.count() === 1) {
        startDOM();
      }
    });
  initSelectCot();
  initSelectCli();
});
