import { DTConfig, response, empty } from "@util";

/* LIBRERIA ABSTRACTA PARA CRUDS */
let tbl = {};
export function initCrudDataTable(sectionName, hiddenCols) {
  DTConfig.NameSection = sectionName;
  response(`${DTConfig.NameSection}/`, { endpoint: "getList" })
    .then((data) => {
      let cols = Object.keys(data.result[0]).map((key) => ({
        data: key,
        visible: !hiddenCols.includes(key),
      }));
      DTConfig.data = data.result;
      DTConfig.buttons = [
        {
          extend: "excelHtml5",
          text: '<i class="bi bi-file-earmark-excel text-success me-1"></i>Exportar Excel',
          customize: function (xlsx) {
            console.log("");
          },
        },
      ];

      DTConfig.drawCallback = function (settings) {
        setCrudControls(settings);
        const iconSearch = `<i class="p-1 bi bi-search"></i>Buscar:`;
        $("div.dt-container div.dt-search label").html(iconSearch);
        $("div.dt-buttons .btn").addClass("rounded-pill");
        $("div.dt-buttons").addClass("w-auto");
      };

      DTConfig.select = { style: "multi" };
      DTConfig.columns = cols;
      DTConfig.selectOptions = false;
      tbl = $(`#tbl-${sectionName}`).DataTable(DTConfig);
    })
    .catch((error) => {
      console.log(error);
      return false;
    });
}

export function crudAlert(title, text, type, targets) {
  const notify = `
      <div class="alert alert-${type} alert-dismissible fade show py-2" 
         role="alert" style="display: none;">
         <b>${title}</b> : <span>${text}</span>
         <button type="button" class="btn-close py-2 my-1" aria-label="Close"></button>
      </div>
   `;

  targets = empty(targets) ? ".dt-layout-table" : targets;

  // Eliminar alertas anteriores
  $(targets).find(".alert").remove();

  // Prepend la nueva alerta
  $(targets).prepend(notify);

  // Usar fadeIn para mostrar la alerta
  $(targets).find(".alert").first().fadeIn(300); // Aquí puedes ajustar el tiempo (500ms)

  $(targets)
    .find(".alert")
    .first()
    .on("click", ".btn-close", function () {
      $(this)
        .closest(".alert")
        .fadeOut(300, function () {
          $(this).remove();
        });
    });

  // Desmarcar todas las filas
  unselectAllRows();
}

window.unselectAllRows = function () {
  tbl.rows().deselect();
  tbl.draw();
};

export function refreshTable(callBack) {
  callBack = callBack || null;
  response(`${DTConfig.NameSection}/`, { endpoint: "getList" })
    .then((data) => {
      var table = $(`#tbl-${DTConfig.NameSection}`);
      var jsonTable = data.result;
      table.DataTable().clear();
      table.DataTable().rows.add(jsonTable).draw();
      $("#options").hide();
      callBack();
    })
    .catch((error) => {
      const msgError =
        (error.responseJSON && error.responseJSON.message) ||
        "Error desconocido";
      console.error(msgError);
    });
}

function setCrudControls(settings) {
  var table = new $.fn.dataTable.Api(settings);
  if (table.rows().count() > 0) {
    const actionRow = `
      <div hidden class="m-auto my-1 py-1 row alert alert-secondary shadow-sm rounded-pill" id="options">
         <span id="dt-selected" class="col m-auto text-start"></span>
         <div id="dt-controls" class="col m-auto p-auto text-end">
            <button type="button" control="view" onclick="view_${DTConfig.NameSection}()"
               class="btn rounded-circle p-2 btn-primary controls">
               <i class="m-auto bi bi-eye text-white"></i>
            </button>
            <button type="button" control="edit" onclick="edit_${DTConfig.NameSection}()"
               class="btn rounded-circle p-2 btn-primary controls">
               <i class="m-auto bi bi-pencil text-white"></i>
            </button>
            <button type="button" onclick="unselectAllRows()"
               class="btn rounded-circle p-2 btn-primary delete">
               <i class="m-auto bi bi-list-check text-white"></i>
            </button>
            <button type="button" control="delete" onclick="delete_${DTConfig.NameSection}()"
               class="btn rounded-circle p-2 btn-danger delete">
               <i class="m-auto bi bi-trash text-white"></i>
            </button>
         </div>
      </div>`;

    if (DTConfig.selectOptions == false) {
      $(table.table().container()).find(".dt-layout-table").prepend(actionRow);
    }

    if ($("body").hasClass("theme-dark")) {
      $("#options").addClass("bg-transparent border-0");
    }

    table.on("select.dt deselect.dt", function (e, dt, type, indexes) {
      var selectedRows = table.rows({ selected: true });

      $("#options").prop("hidden", false);
      if (selectedRows.count() === 0) {
        $("#options").fadeOut();
        DTConfig.selectOptions = false;
        $("#dt-controls .controls").removeAttr("row").fadeOut();
        $("#dt-controls .delete").removeAttr("row");
      } else {
        $("#options").fadeIn();
        DTConfig.selectOptions = true;
        var rowData = selectedRows.data().toArray();
        var rowItem = btoa(JSON.stringify(rowData));

        if (selectedRows.count() === 1) {
          $("#dt-controls .controls").attr("row", rowItem).fadeIn();
          $("#dt-controls .delete").attr("row", rowItem).fadeIn();
        } else {
          $("#dt-controls .controls").removeAttr("row").fadeOut();
          $("#dt-controls .delete").attr("row", rowItem);
        }
      }
    });
  }
}
