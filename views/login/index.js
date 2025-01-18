import { changeLocation, response } from "@util";

$(function () {
  $(".form-control").on("focus", function () {
    if (!$("body").hasClass("theme-dark")) {
      $(this).prev("span").css("border", "1px solid rgba(71, 164, 71, 0.5)");
    }
  });

  $(".form-control").on("blur", function () {
    if (!$("body").hasClass("theme-dark")) {
      $(this).prev("span").css("border", "1px solid #d9dee3");
    }
  });

  $("#formAuthentication").on("submit", function (e) {
    e.preventDefault(); /* prevenir el evento de click por default */
    var usr = $("#log").val().trim();
    var psw = $("#psw").val().trim();
    response("login/", { log: btoa(usr), pass: btoa(psw), endpoint: "enter" })
      .then((data) => {
        if (data.status == 200) {
          changeLocation("inicio/");
        } else {
          Swal.fire("ERROR", data.message, "error");
        }
      })
      .catch((error) => {
        const msgError =
          (error.responseJSON && error.responseJSON.message) ||
          `Error desconocido ${error.error}`;
        Swal.fire("ERROR", msgError, "error");
      });
  });
});
