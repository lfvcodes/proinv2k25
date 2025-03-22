import {
  loadComponents,
  showModalDocument,
  empty,
  response,
  getTasa,
} from "@util";
import { loadCrud } from "@StartCrud";

const hiddenCols = [
  "id_proveedor",
  "descripcion",
  "forma_pago",
  "tasa",
  "registro",
];

let productosCompraSeleccionados = [];
loadComponents();
loadCrud("compra", hiddenCols, true);

window.loadCompraItems = function ($cod, $type = false) {
  response("compra/", {
    endpoint: "getDetail",
    id: $cod,
  }).then((answer) => {
    if (answer.status == 200) {
      console.log(answer);
      let dataParent = answer.result.master;
      let detalle = answer.result.detail;
      let stotal = 0.0;
      let stotald = 0.0;

      let proveedorOption = `<option selected value="${dataParent.id_proveedor}">${dataParent.razon_social}</option>`;
      $("#optprov").append(proveedorOption);
      if ($("#mdl-compra .prov").data("select2")) {
        $("#mdl-compra .prov").select2("destroy");
      }

      initSelectProveedor();

      $("#freg").val(moment.utc(dataParent.freg).format("YYYY-MM-DD"));
      let hour = moment.utc(dataParent.freg).format("HH:mm");
      $("#ftime").val(hour);

      $("#desc").val(dataParent.descripcion);
      $("#tcompra").val(dataParent.tipo_compra).trigger("change");
      $("#mpago").val(dataParent.forma_pago).trigger("change");

      $("#mdl-compra #items-compra tbody").html("");
      detalle.forEach((item) => {
        let total_item = item.cant * item.monto;
        stotal += total_item * Number($("#tasa").val());
        stotald += total_item;
        $("#mdl-compra #items-compra tbody").append(`
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
              <input readonly class="form-control stock" type="number">
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
              <button onclick="removeItemCompra(this);" type="button" title="Quitar Item de la Lista"
              class="btn btn-sm btn-danger rounded-pill p-2">
                  <i class="bi bi-dash-circle m-0"></i>
              </button>
            </td>`
                : ``
            }
        </tr>`);
      });

      $("#mdl-compra #stotal").val(
        stotal.toLocaleString("es-ES", {
          minimumFractionDigits: 2,
          maximumFractionDigits: 2,
          useGrouping: true,
        })
      );

      $("#mdl-compra #stotald").val(
        stotald.toLocaleString("es-ES", {
          minimumFractionDigits: 2,
          maximumFractionDigits: 2,
          useGrouping: true,
        })
      );

      if ($type == "edit" || $type == false) {
        $(`#mdl-compra #agregarFila`).prop("hidden", false);
        initSelectProdCompra();
      }

      if ($type == "view") {
        $(`#mdl-compra #agregarFila`).prop("hidden", true);
        $(`#mdl-compra input, #mdl-compra select`).prop("readonly", true);
        $(`#mdl-compra input, #mdl-compra select`).prop("disabled", true);
      } else if ($type == "edit") {
        $(`#mdl-compra input[name="endpoint"]`).val("update");
        $(`#mdl-compra input, #mdl-compra select`).prop("readonly", false);
        $(`#mdl-compra input, #mdl-compra select`).prop("disabled", false);
        $("#stotal,#stotald,#tasa").prop("disabled", true);
      }
    }
  });
};

window.loadcompra = async function (btn, $type = false) {
  let $jsonData = atob($(btn).attr("row"));
  let $data = JSON.parse($jsonData)[0];
  let $tasaActual = await getTasa();
  let $lblTitle = "";
  //#const lnota = 5;

  window.changeCredit = function (me) {
    if ($(me).val() === "C") {
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

      if ($(me).val() == "D") {
        //$("#fact").val(lnota); #buscar ultima nota de entrega a generar
        $('#iva,label[for="iva"]').prop("hidden", false);
        $('#flimite, label[for="flimite"]').parent().prop("hidden", true);
      } else {
        $('#flimite, label[for="flimite"]').parent().prop("hidden", false);
      }
    }

    $('label[for="fact"]').html(
      '<i class="bi bi-receipt me-1"></i>Comprobante'
    );
  };

  if ($type == false) {
    $lblTitle = "Registrar Nueva compra";
  } else {
    $lblTitle =
      $type == "edit" && $type != false ? "Editar compra" : "Ver compra";
  }

  let saveButton =
    $type == "edit" || $type == false
      ? `<button type="button" id="save" class="btn btn-primary">Guardar</button>`
      : ``;
  const $frm = `<form action="#" id="form-compra" enctype="multipart/form-data" method="POST"></form>`;

  $("#mdl-compra .modal-footer").html(
    `<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
    ${saveButton}`
  );

  $("#mdl-compra .modal-title").text($lblTitle);
  $("#mdl-compra #form-contained").wrap($frm);

  $("#mdl-compra #tasa").val($tasaActual);

  if ($type != false) {
    loadCompraItems($data["cod"], $type);
  } else {
    initSelectProveedor();
    $(`#mdl-compra #agregarFila`).prop("hidden", false);
    initSelectProdCompra();
  }

  $("#mdl-compra").on("hide.bs.modal", () => {
    $("#mdl-compra .modal-footer .btn-primary").unbind("click");
    if ($("#form-compra").length > 0 && $("#form-contained").length > 0) {
      $("#mdl-compra #form-contained").unwrap();
    }
  });

  $("#mdl-compra #save").click(function () {
    if ($type == "edit") {
      let $frm = $("#items-compra").closest("form");
      $frm.append(`<input type="hidden" name="id" value="${$data["cod"]}"/>`);
    }
    //savecompra(instance, $data);
  });

  $("#mdl-compra").modal("show");
};

function startDOM() {
  let dataRow = $('#dt-controls button[control="view"]').attr("row");
  const $btnPrintcompra = `<button type="button" control="pdf" id="btn-print-pdf" onclick="printPdfcompra(this)"
   class="btn rounded-circle p-2 btn-primary controls ms-1">
      <i class="m-auto bi bi-file-pdf text-white fw-bold"></i>
  </button>`;

  let htmlControl = $btnPrintcompra + sessionStorage.getItem("controls");

  $("#dt-controls").html(htmlControl);
  $("#dt-controls button").attr("row", dataRow);

  alterControl();

  $("#freg").val(moment().format("YYYY-MM-DD"));
  $("#ftime").val(moment().format("HH:mm"));

  $("#freg").change(async function (e) {
    e.preventDefault();
    let $date = $(this).val();
    if ($date != moment().format("YYYY-MM-DD")) {
      $("#tasa").attr("readonly", false);
    } else {
      const $tasaActual = await getTasa();
      $("#tasa").val($tasaActual);
      $("#tasa").attr("readonly", true);
    }
  });

  $("#mdl-compra").on("hidden.bs.modal", () => {
    $("#mdl-compra input, #mdl-compra select").prop("readonly", false);
    $("#mdl-compra input, #mdl-compra select").val("");
    $(`#mdl-compra input[name="endpoint"]`).val("setCompra");
    $("#mdl-compra #agregarFila").prop("hidden", false);
    $("#items-compra tbody").html("");
    $("#mdl-compra #stotald").val("");
    $("#tasa").attr("readonly", true);
    $("#mdl-compra .prov").html("");
    $("#freg").val(moment().format("YYYY-MM-DD"));
    $("#ftime").val(moment().format("HH:mm"));
  });
}

window.printPdfcompra = function (btn) {
  let row = atob($(btn).attr("row"));
  let dataRow = JSON.parse(row)[0];
  let urlPdf = `pdf/compra.php?v=${dataRow.cod}&t=${dataRow.tipo_compra}`;
  showModalDocument(urlPdf, `compra N° ${dataRow.cod}`);
};

window.alterControl = function () {
  const btnAddcompra = $('button[control="add"]');
  const btnEditcompra = $('#dt-controls button[control="edit"]');
  const btnViewcompra = $('#dt-controls button[control="view"]');

  btnAddcompra.click(async () => {
    unselectAllRows();
    loadcompra(btnAddcompra);
  });

  btnViewcompra.click(async () => {
    loadcompra(btnViewcompra, "view");
  });

  btnEditcompra.click(async () => {
    loadcompra(btnEditcompra, "edit");
  });
};

window.calcm = function (me) {
  let stotald = 0.0;
  let precio = $(me).parents("tr").find("td .monto").val();

  let titem = $(me).parents("tr").find("td .titem");
  let item = precio * Number($(me).val());
  titem.val(item.toFixed(2));

  let vtasa = Number($("#tasa").val());

  $(".titem").each(function () {
    stotald += Number($(this).val());
  });

  let stock = $(me).parent("td").parent("tr").find("td .stock");
  let vls = Number($(stock).attr("stk"));
  stock.val(vls + Number($(me).val()));

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
  let tcompra = 0;
  let count = 0;
  $("#items-compra tbody tr").each(function () {
    if (!empty($(this).find("td:eq(4) .titem").val())) {
      tcompra += Number($(this).find("td:eq(4) .titem").val());
      count++;
    }
  });

  $("#stotald").val(
    tcompra.toLocaleString("es-ES", {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2,
      useGrouping: true,
    })
  );
}

window.initSelectProveedor = function () {
  $("#mdl-compra .prov").select2({
    theme: "bootstrap-5",
    dropdownParent: $("#mdl-compra"),
    ajax: {
      url: "../../api/proveedor/",
      type: "post",
      dataType: "json",
      delay: 250,
      data: function (params) {
        return {
          endpoint: "getListOptionProveedor",
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
};

window.initSelectProdCompra = function () {
  $("#items-compra tbody tr td .prod").select2({
    theme: "bootstrap-5",
    dropdownParent: $("#mdl-compra"),
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

  $("#items-compra .prod").change(function (e) {
    let nuevoProducto = $(this).val();
    let filaActual = $(this).closest("tr");
    let filaIndex = filaActual.index();
    let productosSeleccionadosTemp = [...productosCompraSeleccionados]; // Crear una copia temporal del array de productos seleccionados

    // Eliminar el producto previamente seleccionado de la lista de productos seleccionados
    let productoAnterior = filaActual.data("producto-seleccionado");
    if (productoAnterior) {
      let indexProductoAnterior =
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
        productosCompraSeleccionados.indexOf(productoAnterior);
      if (indexProductoAnterior > -1) {
        productosCompraSeleccionados.splice(indexProductoAnterior, 1);
      }
    }
    if (nuevoProducto) {
      var vl = $(this).val();
      response("inventario/", {
        endpoint: "getProductPrices",
        id: vl,
      }).then((data) => {
        let $rs = data.result[0];
        $(this).parents("tr").find("td .monto").val($rs["pcosto"]);

        /* solo para compras*/
        let stockProduct = $rs["stockreal"] <= 0 ? $rs["stockreal"] : 0;
        let cellStock = $(this).parents("tr").find("td .stock");
        let cellCant = $(this).parents("tr").find("td .cant");
        cellCant.attr("max", stockProduct);
        cellStock.val(stockProduct);

        productosCompraSeleccionados.push(nuevoProducto);
      });
    }
  });
};

window.addItemCompra = function () {
  $("#mdl-compra #items-compra tbody").append(`
    <tr>
        <td>
          <select class="form-select form-control prod" name="prod[]"></select>
        </td>
        <td>
          <input required onkeyup="calcm(this)" onchange="calcm(this)" name="cant[]" class="form-control cant" min="1" step="1" type="number">
        </td>
        <td>
          <input readonly class="form-control stock" type="number">
        </td>
        <td>
          <input required readonly name="monto[]" class="form-control monto" min="0.01" step="0.01" type="number">
          </td>
        <td>
          <input readonly class="form-control titem" type="number">
        </td>
        <td>
          <button onclick="removeItemCompra(this);" type="button" 
          class="btn btn-sm btn-danger rounded-pill p-2">
            <i class="bi bi-dash-circle m-0"></i>
          </button>
        </td>
      </tr>
  `);

  $("#items-compra tbody tr td .monto").on("change", function () {
    refreshItemStatus();
  });

  refreshItemStatus();
  initSelectProdCompra();
};

window.removeItemCompra = function (btn) {
  let optionSelect = $(btn).parent("td").parent("tr").find("td .prod").val();
  productosCompraSeleccionados.splice(
    productosCompraSeleccionados.indexOf(optionSelect)
  );
  $(btn).parent("td").parent("tr").remove();

  refreshItemStatus();
  if ($("#item-Compra  tbody tr").length < 1) {
    return false;
  }
};

$(function () {
  sessionStorage.setItem("controls", $("#dt-controls").html());
  let table = $("#tbl-compra")
    .DataTable()
    .on("draw.dt search.dt select.dt deselect.dt length.dt", () => {
      let selectedRows = table.rows({ selected: true });
      if (selectedRows.count() === 1) {
        startDOM();
      }
    });
  startDOM();
});
