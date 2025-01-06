<nav class="sidebar sidebar-offcanvas shadow-lg" id="sidebar">
  <div class="sidebar-brand-wrapper d-none d-lg-flex align-items-center justify-content-center fixed-top">
    <a class="sidebar-brand brand-logo text-light text-decoration-none" href="<?= VIEW_PATH; ?>app/inicio">
      PROINV 2K25
      <?php
      #echo "<img src='{$APP_PATH}assets/img/logo.png' alt='logo' />"
      ?>
    </a>
    <a class="sidebar-brand brand-logo-mini" href="<?= VIEW_PATH; ?>inicio"><img src="<?= APP_PATH ?>assets/img/logo.png?rand=<?= substr(rand(), 0, 4) ?>"
        alt="logo" /></a>
  </div>
  <ul class="nav mt-1">
    <li class="nav-item nav-category">
      <span class="nav-link text-light">Menú</span>
    </li>
    <li class="nav-item menu-items pe-0">
      <a class="nav-link" href="<?= VIEW_PATH; ?>inicio">
        <span class="menu-icon">
          <i class="bx bx-home bx-md"></i>
        </span>
        <span class="menu-title">Inicio</span>
      </a>
    </li>
    <li class="nav-item menu-items pe-0">
      <a class="nav-link" data-bs-toggle="collapse" href="#ui-ventas" aria-expanded="false" aria-controls="ui-basic">
        <span class="menu-icon">
          <i class="menu-icon tf-icons bi bi-building-down bx-md"></i>
        </span>
        <span class="menu-title">Ventas</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="ui-ventas">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="<?= VIEW_PATH; ?>venta">Nueva Venta</a></li>
          <li class="nav-item"> <a class="nav-link" href="<?= VIEW_PATH; ?>cotizacion">Nueva Cotización</a></li>
          <li class="nav-item"> <a class="nav-link" href="<?= VIEW_PATH; ?>cxc">Cuentas por Cobrar</a></li>
        </ul>
      </div>
    </li>
    <li class="nav-item menu-items pe-0">
      <a class="nav-link" data-bs-toggle="collapse" href="#ui-com" aria-expanded="false" aria-controls="ui-basic">
        <span class="menu-icon">
          <i class="menu-icon tf-icons bi bi-building-up bx-md"></i>
        </span>
        <span class="menu-title">Compras</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="ui-com">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="<?= VIEW_PATH; ?>compra">Nueva Compra</a></li>
          <li class="nav-item"> <a class="nav-link" href="<?= VIEW_PATH; ?>cxp">Cuentas por Pagar</a></li>
        </ul>
      </div>
    </li>
    <li class="nav-item menu-items pe-0">
      <a class="nav-link" data-bs-toggle="collapse" href="#ui-inventario" aria-expanded="false"
        aria-controls="ui-basic">
        <span class="menu-icon">
          <i class="menu-icon tf-icons bi bi-boxes bx-md"></i>
        </span>
        <span class="menu-title">Inventario</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="ui-inventario">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="<?= VIEW_PATH; ?>inventario">Articulos</a></li>
          <li class="nav-item"> <a class="nav-link" href="<?= VIEW_PATH; ?>categoria">Categorias</a></li>
        </ul>
      </div>
    </li>
    <li class="nav-item menu-items pe-0">
      <a class="nav-link" data-bs-toggle="collapse" href="#ui-despacho" aria-expanded="false" aria-controls="ui-basic">
        <span class="menu-icon">
          <i class="bi bi-truck bx-md"></i>
        </span>
        <span class="menu-title">Guia de Despacho</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="ui-despacho">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="<?= VIEW_PATH; ?>guia">Nueva Guía</a></li>
          <li class="nav-item"> <a class="nav-link" href="<?= VIEW_PATH; ?>conductor">Conductores</a></li>
          <li class="nav-item"> <a class="nav-link" href="<?= VIEW_PATH; ?>vehiculo">Vehiculos</a></li>
        </ul>
      </div>
    </li>
    <li class="nav-item menu-items pe-0">
      <a class="nav-link" href="<?= VIEW_PATH; ?>reportes">
        <span class="menu-icon">
          <i class="bi bi-file-earmark-pdf bx-md"></i>
        </span>
        <span class="menu-title">Reportes</span>
      </a>
    </li>
    <li class="nav-item menu-items pe-0">
      <a class="nav-link" href="<?= VIEW_PATH; ?>cliente">
        <span class="menu-icon">
          <i class="menu-icon tf-icons bx bx-user-pin bx-md"></i>
        </span>
        <span class="menu-title">Cliente</span>
      </a>
    </li>
    <li class="nav-item menu-items pe-0">
      <a class="nav-link" href="<?= VIEW_PATH; ?>proveedor">
        <span class="menu-icon">
          <i class="menu-icon tf-icons bx bx-briefcase bx-md"></i>
        </span>
        <span class="menu-title">Proveedor</span>
      </a>
    </li>
    <li class="nav-item menu-items pe-0">
      <a class="nav-link" href="<?= VIEW_PATH; ?>vendedor">
        <span class="menu-icon">
          <i class="menu-icon tf-icons bi bi-person-badge bx-md"></i>
        </span>
        <span class="menu-title">Vendedor</span>
      </a>
    </li>

  </ul>
</nav>