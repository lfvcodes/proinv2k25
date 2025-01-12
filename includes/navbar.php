<nav class="navbar p-0 fixed-top">
  <div class="navbar-menu-wrapper flex-grow align-items-stretch row">
    <div class="col-lg col-md d-lg-flex d-sm-none">
      <button class="navbar-toggler navbar-toggler align-self-center text-light" type="button" data-toggle="minimize">
        <span class="bx bx-menu bx-md"></span>
      </button>
      <ul class="d-none d-lg-block m-auto navbar-nav w-100">
        <li class="nav-item me-2">
          <small><span id="nav-date"></span></small>
        </li>
      </ul>
    </div>

    <div class="col-lg d-none d-lg-flex">
      <ul class="navbar-nav mx-auto w-100">
        <li class="nav-item me-2 w-100">
          <?php if ($session['nivel'] == 1): ?>
            <input id="searchNav" type="text" class="form-control" placeholder="Buscar Articulos">
          <?php endif; ?>
        </li>
      </ul>
    </div>

    <div class="col-lg col-md col-sm d-lg-flex">
      <ul class="navbar-nav navbar-nav-right">
        <li class="nav-item">
          <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
            data-toggle="offcanvas">
            <span class="bx bx-menu text-light"></span>
          </button>
        </li>
        <li class="nav-item nav-settings d-flex">

          <?php if ($MODO == 'claro'): ?>
            <a class="nav-link btn-theme" href="#">
              <i class="bi bi-sun"></i>
            </a>
          <?php else: ?>
            <a class="nav-link btn-theme" href="#">
              <i class="bi bi-moon"></i>
            </a>
          <?php endif; ?>
          <a class="nav-link btn-notification" href="#">
            <i class="bi bi-bell"></i>
          </a>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link" id="profileDropdown" href="#" data-bs-toggle="dropdown">
            <div class="navbar-profile">
              <p class="m-auto navbar-profile-name">
                <i class="bi bi-person-circle"></i>
                <?= $session['log_user']; ?>
              </p>
              <i class="menu-arrow d-none d-sm-block"></i>
            </div>
          </a>
          <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="profileDropdown">
            <h6 id="usr-role" class="p-3 mb-0">
              <?= ($session['nivel'] === 1) ? 'Administrador' : 'Operador'; ?></h6>
            <div class="dropdown-divider"></div>

            <?php if ($session['nivel'] === 1): ?>
              <a id="btn-empresa-change" class="dropdown-item" href="<?= APP_PATH; ?>tasa/actualizar">
                <i class="bi bi-cash-coin me-2"></i>
                <span class="align-middle">Actualizar Tasa</span>
              </a>
            <?php endif; ?>

            <a id="btn-empresa-change" class="dropdown-item" href="<?= APP_PATH; ?>herr/acerca">
              <i class="bx bx-info-circle me-2"></i>
              <span class="align-middle">Acerca del Sistema</span>
            </a>
            <?php if ($session['nivel'] === 1): ?>
              <a id="btn-empresa-change" class="dropdown-item" href="<?= APP_PATH; ?>user/user">
                <i class="bx bx-group me-2"></i>
                <span class="align-middle">Usuarios</span>
              </a>
            <?php endif; ?>

            <div class="dropdown-divider"></div>
            <a href="../exit" class="dropdown-item fw-bold text-danger">
              <i class="bx bx-log-out"></i>
              <span class="align-middle">Cerrar Sesión</span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
        </li>
      </ul>
    </div>
  </div>
</nav>