import { APP_PATH, response, SAConfig, capitalize, empty } from "@util";
import { initCrudDataTable, crudAlert, refreshTable } from "@DTCrud";

export function initSelect(id, instance) {
  $(`#${id}`).select2({
    theme: "bootstrap-5",
    dropdownParent: $(`#mdl-${instance}`),
  });
}

export function loadCrud(instance, hideCols = []) {
  if (!empty(sessionStorage.getItem("controls"))) {
    sessionStorage.removeItem("controls");
  }

  function loadRowInfo(form, data, view) {
    $.each(data, function (key, value) {
      var element = form.find(`#${key}`);

      if (element.is("select")) {
        if (element.data("select2")) {
          $(`#${key}`).select2("destroy");
        }
        $(`#${key} option[value="${value}"]`).prop("selected", true);

        initSelect(key, instance);
      } else if (element.is("input[type='checkbox']")) {
        // Condición para checkboxes
        element.prop("checked", value != null && value != 0);
      } else {
        element.val(value);
        form.find(`input[name="${key}"]`).val(value);
      }

      element.prop("disabled", view ? true : false);
      form.find(`input[name="${key}"]`).prop("disabled", view ? true : false);
    });
  }

  initCrudDataTable(instance, hideCols);

  const mdl = $(`#mdl-${instance}`);

  const form = mdl.find("form");
  const mdlTitle = capitalize(instance);
  const endpoint = form.find('input[name="endpoint"]');
  let icon = `<i class="bx bx-plus-circle"></i>`;
  mdl.find(".modal-title").html(`${icon} Agregar ${mdlTitle}`);
  form.find('input[name="endpoint"]').val("add");

  form.on("submit", function (e) {
    e.preventDefault();

    let formData = $(this).serializeArray();

    let dataSend = formData.reduce((obj, item) => {
      // Verifica si el nombre del campo indica que es un array
      if (item.name.endsWith("[]")) {
        // Retira el sufijo '[]' para guardar el valor
        const key = item.name.slice(0, -2);

        // Si la propiedad no existe, inicializa un array
        if (!obj[key]) {
          obj[key] = [];
        }
        // Agrega el valor al array de esa propiedad
        obj[key].push(item.value);
      } else {
        // Para los campos que no son arrays, simplemente asigna el valor
        obj[item.name] = item.value;
      }
      return obj;
    }, {});

    let titleAlert = mdl.find(".modal-title").text();

    SAConfig.title = "¿Está Seguro(a) de Confirmar este Proceso?";
    Swal.fire(SAConfig).then((result) => {
      if (result.value == true) {
        response(`${instance}/`, dataSend)
          .then((data) => {
            crudAlert(titleAlert, data.message, "success");
            refreshTable();
            mdl.modal("hide");
          })
          .catch((error) => {
            const msgError =
              (error.responseJSON && error.responseJSON.message) ||
              "Error desconocido";
            crudAlert(
              `Error al ${titleAlert} `,
              msgError,
              "danger",
              ".modal-body"
            );
          });
      }
    });
  });

  window[`view_${instance}`] = function () {
    let element = event.currentTarget;
    const data = JSON.parse(atob($(element).attr("row")))[0];
    if (mdl.attr("detail") == "true") {
      let urlRef = "documents/pdf/" + mdl.attr("dref");
      let url = `${APP_PATH}${urlRef}${data.cod}`;
      window.open(url, "_blank");
    } else {
      loadRowInfo(form, data, true);
      icon = `<i class="bi bi-eye"></i>`;
      mdl.find(".modal-title").html(`${icon} Ver ${mdlTitle}`);
      mdl.find('button[type="submit"').hide();
      endpoint.val("view");
      mdl.modal("show");
    }
  };

  window[`edit_${instance}`] = function () {
    let element = event.currentTarget;
    icon = `<i class="bi bi-pencil"></i>`;
    const data = JSON.parse(atob($(element).attr("row")))[0];
    loadRowInfo(form, data);
    mdl.find(".modal-title").html(`${icon} Editar ${mdlTitle}`);

    endpoint.val("update");
    mdl.modal("show");
  };

  window[`delete_${instance}`] = function () {
    let element = event.currentTarget;
    const data = JSON.parse(atob($(element).attr("row")));
    SAConfig.title = "¿Está Seguro de Confirmar el Borrado?";

    Swal.fire(SAConfig).then((result) => {
      if (result.value == true) {
        let deleteElements = [];
        data.forEach((element) => {
          let idElement;
          if (empty(element.nac) == false) {
            idElement = `${element.nac}-${element.id}`;
          } else if (empty(element.nac) == true) {
            idElement = !empty(element.cod)
              ? `${element.cod}`
              : `${element.id}`;
          }
          deleteElements.push(idElement);
        });

        response(`${instance}/`, { endpoint: "delete", list: deleteElements })
          .then((data) => {
            refreshTable();
            crudAlert(
              `Borrar Registro(s) de ${instance}`,
              data.message,
              "success"
            );
          })
          .catch((error) => {
            const msgError =
              (error.responseJSON && error.responseJSON.message) ||
              "Error desconocido";
            crudAlert(`Error al Borrar  ${instance}`, `${msgError}`, "danger");
          });
      } else return;
    });
  };

  window.loadItemsDetail = function (instance) {
    let options = '<option disabled selected value="">Elige uno</option>';
    response(`${instance}/`, { endpoint: "getListOptionDetail" }).then(
      (data) => {
        data.result.forEach((opt) => {
          options += `<option value="${opt.id}">${opt.text}</option>`;
        });

        $("#tbl-items tbody tr td select").each(function () {
          if (!$(this).data("select2")) {
            $(this).html(options);
            $(this).select2({
              theme: "bootstrap-5",
              dropdownParent: $(`#mdl-${instance}`),
            });
          }
        });
      }
    );
  };

  if (mdl.attr("detail") == "true") {
    let itemList = [];
    const rowStart = $("#tbl-items tbody tr:first").clone();
    loadItemsDetail(instance);

    window.add = function () {
      if ($("#tbl-items tbody tr").length < 1) {
        $("#tbl-items tbody").append(rowStart);
      } else {
        let $tr = $("#tbl-items tbody tr:last");
        let $select = $tr.find("td select");
        $select.attr("id", $tr.index());

        if ($select.data("select2")) {
          $select.select2("destroy"); // Destruir select2 en el select original
        }

        let $newTr = $tr.clone();
        $newTr.insertAfter($tr);

        let $newSelect = $newTr.find("td select");
        $newSelect.attr("id", $tr.index() + 1); // Asignar un id único basado en el índice de la fila

        initSelect($select.attr("id"), instance);
      }

      loadItemsDetail(instance);
    };

    window.remove = function (btn) {
      let optionSelect = $(btn)
        .parent("td")
        .parent("tr")
        .find("td .vent")
        .val();
      itemList.splice(itemList.indexOf(optionSelect));
      $(btn).parent("td").parent("tr").remove();
      calcm($("#tbl-items tbody tr:last td:eq(1) .form-control"));
    };

    window.calcm = function (me) {
      var stotalb = 0.0;
      var stotald = 0.0;

      var precio = $(me).parent("td").parent("tr").find("td .monto").val();

      var titem = $(me).parent("td").parent("tr").find("td .titem");
      var item = precio * Number($(me).val());
      titem.val(item.toFixed(2));

      var vtasa = Number($("#tasa").val());

      $(".titem").each(function () {
        stotald += Number($(this).val());
      });

      $("#stotald").val(
        stotald.toLocaleString("es-ES", {
          minimumFractionDigits: 2,
          maximumFractionDigits: 2,
          useGrouping: true,
        })
      );
    };
  }

  mdl.on("hide.bs.modal", function () {
    icon = `<i class="bx bx-plus-circle"></i>`;
    mdl.find(".modal-title").html(`${icon} Agregar ${mdlTitle}`);
    mdl.find(".form-control,.form-select").prop("disabled", false);
    mdl.find('button[type="submit"]').show();
    mdl.find(".alert").remove();
    form[0].reset();
    form.find('input[name="endpoint"]').val("add");
    if (mdl.attr("detail") == "true") {
      let itemList = [];
      $("#tbl-items tbody").empty();
    }
  });
}

export function crudAlterControls(controlEvents) {
  $(function () {
    $.each(controlEvents, function (control, trigger) {
      $(`#dt-controls button[control="${control}"]`).on(
        trigger.event,
        trigger.fn
      );
    });
  });
}
