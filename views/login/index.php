<?php
session_start();
require_once __DIR__ . '/../../config.php';
$title = 'Login';
$isLogin = true;
require_once __DIR__ . '/../../utils/php/utils.php';
require_once __DIR__ . '/../../includes/head.php';
?>

<div class="container-scroller">
  <div class="container-fluid page-body-wrapper full-page-wrapper">
    <div class="row w-100 m-0">
      <div class="content-wrapper full-page-wrapper d-flex align-items-center auth login-bg">
        <div class="card col-lg-4 mx-auto">
          <div class="card-body px-5 py-5">
            <div class="app-brand text-center justify-content-center mb-0 pb-0">
              <h4 class="app-brand-link text-center gap-2 mt-2">PROINV Software</h4>
            </div>

            <div class="justify-content-center">
              <h2 class="text-center text-primary">
                <i style="font-size: 4rem;" class="bi bi-person-circle"></i>
              </h2>
            </div>
            <form id="formAuthentication" class="mb-3" action="#" method="POST">
              <div class="mb-3">
                <label for="log" class="form-label">Usuario</label>
                <div class="input-group">
                  <span class="input-group-text bg-transparent border-end-0">
                    <i class="bi bi-person"></i>
                  </span>
                  <input required type="text" class="form-control border-start-0 val-nospc val-only-letters" id="log"
                    name="log-username" placeholder="Digite su Nombre de usuario" autocomplete="off" autofocus />

                </div>
              </div>
              <div class="mb-3 form-password-toggle">
                <div class="d-flex justify-content-between">
                  <label class="form-label" for="psw">Contraseña</label>
                </div>
                <div class="input-group input-group-merge">
                  <span class="input-group-text bg-transparent border-end-0">
                    <i class="bi bi-key"></i>
                  </span>
                  <input required type="password" id="psw" class="form-control border-start-0" name="psw" autocomplete="off"
                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                    aria-describedby="password" />
                  <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                </div>
              </div>
              <div class="mb-3">
                <button id="btn-log" class="btn btn-primary d-grid w-100" type="submit">Iniciar Sesión</button>
              </div>
            </form>
            <p align="justify">
              <span>
                Suministre los datos solicitados por el sistema para validar su
                sesión de usuario
              </span>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
require_once __DIR__ . '/../../includes/footer.php';
?>