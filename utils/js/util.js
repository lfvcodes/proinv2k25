export const APP_PATH = "../../";
export const DOCS_PATH = "../../documents/";
const VIEW_PATH = "../../views/";
const API_PATH = "../../api/";

let timerLogout = false;
const VALUE_SECONDS = 900;

function updateSystemDate() {
  var dzone = moment().format("DD/MM/YYYY");
  var tzone = moment().format("hh:mm a");
  var txtFecha = `<i class="bi bi-calendar"></i> ${dzone} | ${tzone}`;
  $("#nav-date").html(txtFecha);

  setInterval(function () {
    let dzone = moment().format("DD/MM/YYYY");
    let tzone = moment().format("hh:mm a");
    let txtFecha = `<i class="bi bi-calendar"></i> ${dzone} | ${tzone}`;
    $("#nav-date").html(txtFecha);
  }, 30000);
}

function setActiveLink() {
  var url = window.location.pathname.substring(
    window.location.pathname.lastIndexOf("ws/") + 2
  );
  url = url.substring(1, url.length - 1);

  $("#sidebar .nav li").removeClass("active");

  let item = $(`#sidebar .nav li a[href="../${url}"]`);
  item.parent("li").addClass("active");

  let subItem = $(`#sidebar .nav li .collapse .nav li a[href="../${url}"]`);
  subItem.parents(".collapse").parent().addClass("active text-danger");
}

function changeStyle(modo) {
  if (modo === "claro") {
    document.cookie =
      "modo_estilo=claro; expires=Fri, 31 Dec 9999 23:59:59 GMT; path=/";
  } else {
    document.cookie =
      "modo_estilo=oscuro; expires=Fri, 31 Dec 9999 23:59:59 GMT; path=/";
  }
  window.location.reload();
}

export function encodeBase64WithoutPadding(input) {
  let encoded = btoa(input);
  // Eliminar los caracteres "=" al final
  return encoded.replace(/=+$/, "");
}

// Función para decodificar Base64 sin los caracteres "==" al final
export function decodeBase64WithoutPadding(encoded) {
  // Agregar de nuevo los caracteres "=" para hacer la longitud divisible por 4
  while (encoded.length % 4 !== 0) {
    encoded += "=";
  }
  return atob(encoded);
}

export function getParameterByName(name) {
  name = name.replace(/[$]/, "$").replace(/[$]/, "$");
  let regex = new RegExp("[\\?&]" + name + "=([^&#]*)");
  let results = regex.exec(location.search);

  if (results === null) {
    return "";
  } else {
    let value = decodeURIComponent(results[1].replace(/\+/g, " "));
    try {
      let decodedValue = atob(value);
      return decodedValue;
    } catch (e) {
      return value;
    }
  }
}

export function showModalDocument(url, pdfTitle) {
  let modalID = "pdfModal";
  let iframeID = "pdfIframe";

  // Comprobar si el modal ya existe
  if ($("#" + modalID).length) {
    // Si el modal existe, actualizar la URL del iframe
    $("#" + iframeID).attr("src", url);
  } else {
    // Si el modal no existe, crearlo
    let modalHtml = `
      <div class="modal fade" id="${modalID}" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
        role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">${pdfTitle}</h5>
              <button type="button" class="btn-close"
                data-bs-dismiss="modal" aria-label="Close">
              </button>
            </div>
            <div class="modal-body">
              <iframe id="${iframeID}" src="${DOCS_PATH}${url}" style="width: 100%; height: 80vh;"></iframe>
            </div>
          </div>
        </div>
      </div>
    `;
    $("body").append(modalHtml);
    // Inicializar el modal de Bootstrap
    $("#" + modalID).modal("show");
    // Asegurarse de que el modal se elimina del DOM al cerrarse, para evitar duplicados
    $("#" + modalID).on("hidden.bs.modal", function (e) {
      $(this).remove();
    });
  }
}

export function capitalize(string) {
  const words = string.split(" ");
  const output = words.map((word) => {
    const firstLetter = word.substring(0, 1).toUpperCase();
    const rest = word.substring(1);

    return `${firstLetter}${rest}`;
  });

  return output.join(" ");
}

export function getTasa() {
  //tasa de cambio exchange
  return response("cotizacion/", { endpoint: "getTasa" }).then((data) => {
    if (data.status == 200) {
      return data.result.tasa;
    } else {
      return null;
    }
  });
}

export function parseJwt(token) {
  var base64Url = token.split(".")[1];
  var base64 = base64Url.replace(/-/g, "+").replace(/_/g, "/");
  var jsonPayload = decodeURIComponent(
    window
      .atob(base64)
      .split("")
      .map(function (c) {
        return "%" + ("00" + c.charCodeAt(0).toString(16)).slice(-2);
      })
      .join("")
  );

  return JSON.parse(jsonPayload);
}

export function getCookie(name) {
  const value = `; ${document.cookie}`;
  const parts = value.split(`; ${name}=`);
  if (parts.length === 2) {
    return parts.pop().split(";").shift();
  }
  return undefined; // Devolver undefined si no se encuentra la cookie
}

export const scrollStartPosition =
  document.body.scrollTop || document.documentElement.scrollTop;

export var DTConfig = {
  responsive: false,
  fixedHeader: true,
  scroller: true,
  sScrollY: window.innerHeight - scrollStartPosition - 300 + "px",
  pageLength: 20,
  Sort: true,
  buttons: [],
  aaSorting: [],
  /*
  "columnDefs": [{
    "targets": [-1],
    "orderable": false,
  },],
  */
  data: {},
  drawCallback: function () {
    const iconSearch = `<i class="p-1 bi bi-search"></i>Buscar:`;
    $("div.dt-container div.dt-search label").html(iconSearch);
  },
};

export var SAConfig = {
  title: "¿Está Seguro(a) de Confirmar esta Operación?",
  text: "",
  icon: "question",
  showCancelButton: true,
  confirmButtonColor: "#1D7CA1",
  cancelButtonColor: "#d33",
  confirmButtonText: "Si, Confirmar",
  cancelButtonText: "Cancelar",
};

export function loadComponents() {
  updateSystemDate();
  setActiveLink();

  $(".btn-theme").click(function () {
    if ($(this).find("i").hasClass("bi-sun")) {
      changeStyle("oscuro");
    } else {
      changeStyle("claro");
    }
  });
}

export const showLoading = ({
  message = "Cargando",
  transparentBackground = true,
} = {}) => {
  let textColor = transparentBackground ? "#fff" : "#000";
  const themeValue = localStorage.getItem("theme");
  textColor = themeValue && themeValue.includes("dark") ? "#fff" : textColor;
  let background = "transparent";

  if (!transparentBackground) {
    background = "#fff";
  }

  Swal.fire({
    html: `<span style='color: ${textColor}; font-weight: bold; font-size: 3rem;'>${message}</span>`,
    allowEscapeKey: false,
    allowOutsideClick: false,
    showConfirmButton: false,
    background: background,
  });

  Swal.showLoading();
};

export const showNotification = ({ type = "error", message = "" } = {}) => {
  Swal.fire({
    icon: type,
    html: `<b>${message}</b>`,
    showConfirmButton: false,
  });
};

export const showAutoLogout = ({
  secondsWaiting = 30,
  message = "Su sesión expirará en ",
} = {}) => {
  let secondsNow = secondsWaiting;
  const DEFAULT_SECONDS_WAITING = secondsWaiting;

  const optionsSwal = {
    html: `<span style="font-weight: bold; font-size: 3rem;">${
      message + secondsNow + " segundos"
    }</span>`,
    allowEscapeKey: false,
    allowOutsideClick: false,
    showConfirmButton: true,
    confirmButtonText: "Actualizar sesión",
  };

  function fnRefresh() {
    timerLogout = setTimeout(() => {
      try {
        secondsNow--;
        Swal.update({
          ...optionsSwal,
          html: `<span style="font-weight: bold; font-size: 3rem;">${
            message + secondsNow + " segundos"
          }</span>`,
        });
      } catch (error) {}
      if (secondsNow < 0) {
        Swal.close();
        window.location.replace("../exit/");
      } else {
        fnRefresh();
      }
    }, 1000);
  }

  fnRefresh();

  Swal.fire({ ...optionsSwal }).then((res) => {
    if (res.isConfirmed) {
      showLoading({ message: "Actualizando sesión" });
      clearTimeout(timerLogout);

      response("refresh-session/", { endpoint: "refresh" }).then((json) => {
        if (json.status == 200) {
          clearTimeout(timerLogout);
          showNotification({ type: "success", message: json.message });

          const newTime = json.time;
          secondsWaiting = DEFAULT_SECONDS_WAITING;

          if (newTime <= secondsWaiting) {
            secondsWaiting = newTime;
            showAutoLogout({ secondsWaiting });
          } else return;
        } else {
          showNotification({ message: json.error });
          setTimeout(() => {
            changeLocation("exit/");
          }, secondsNow * 1000);
        }
      });
    }
  });
};

export function prepareFormData(form) {
  const formData = form.serializeArray().reduce((f, c) => {
    f[c["name"]] = f[c["name"]]
      ? [].concat(f[c["name"]], c["value"])
      : c["value"];
    return f;
  }, {});

  return formData;
}

export function changeLocation(_url) {
  window.location.replace(VIEW_PATH + _url);
}

export function response($url, $data, isAsync) {
  return new Promise((resolve, reject) => {
    $.ajax({
      type: "POST",
      url: API_PATH + $url,
      data: JSON.stringify($data),
      contentType: "application/json; charset=UTF-8",
      dataType: "json",
      async: isAsync || false,
    })
      .done((data) => {
        resolve(data); // Resuelve la promesa con los datos obtenidos
      })
      .fail((jqXHR) => {
        reject(jqXHR); // Rechaza la promesa en caso de error
      });
  });
}

export function responseForm($url, formData, isAsync) {
  return new Promise((resolve, reject) => {
    $.ajax({
      type: "POST",
      url: API_PATH + $url,
      data: formData,
      contentType: false,
      dataType: "json",
      async: isAsync || false,
    })
      .done((data, textStatus, jqXHR) => {
        resolve({ status: jqXHR.status, response: data });
      })
      .fail((jqXHR, textStatus, errThrown) => {
        reject({ status: jqXHR.status, error: textStatus });
      });
  });
}

export function empty(e) {
  if (typeof e === "string" && e.trim() === "") {
    return true;
  }

  switch (e) {
    case "":
    case 0:
    case "0":
    case null:
    case undefined:
    case false:
    case typeof e === undefined:
    case typeof e == "undefined":
      return true;
    default:
      return false;
  }
}
