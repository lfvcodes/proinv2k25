<div class="row px-2">
  <div class="icard card col-lg col-md col-sm m-1">
    <div class="card-body p-3">
      <h4>
        <span class="fw-semibold d-block mb-3"><i class="bi bi-building"></i> Proinv 2k25</span>
      </h4>

    </div>

  </div>
  <div class="icard card col-lg col-md col-sm m-1">
    <div class="card-body p-3">
      <h4>
        <span class="fw-semibold d-block mb-3">Cuentas por Cobrar</span>
      </h4>
      <div class="d-flex">
        <h4 id="tcxc" class="card-title mb-1 text-dark">$2.202,16</h4>
      </div>
    </div>

  </div>

  <div class="icard card col-lg col-md col-sm m-1">
    <div class="card-body p-3">
      <h4>
        <span class="fw-semibold d-block mb-3">Cuentas por Cobrar</span>
      </h4>
      <div class="d-flex">
        <h4 id="tcxc" class="card-title mb-1 text-dark">$2.202,16</h4>
      </div>
    </div>
  </div>
</div>

<div class="row mt-2">

  <!-- Area Chart -->
  <div class="col-xl-7 col-lg-6">
    <div class="card shadow mb-4">
      <!-- Card Header - Dropdown -->
      <div class="card-header bg-primary py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-white">Transacciones mensuales totales en el año
          <?= date('Y'); ?></h6>
      </div>
      <!-- Card Body -->
      <div class="card-body">
        <div class="chart-area">
          <div class="chartjs-size-monitor">
            <div class="chartjs-size-monitor-expand">
              <div class=""></div>
            </div>
            <div class="chartjs-size-monitor-shrink">
              <div class=""></div>
            </div>
          </div>
          <canvas id="myAreaChart" width="669" height="320" class="chartjs-render-monitor"
            style="display: block; width: 669px; height: 320px;"></canvas>
        </div>
      </div>
    </div>
  </div>

  <!-- Pie Chart -->
  <div class="col-xl-5 col-lg-5">
    <div class="card shadow mb-4">
      <!-- Card Header - Dropdown -->
      <div class="card-header bg-primary py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-white">Transacciones</h6>
      </div>
      <!-- Card Body -->
      <div class="card-body">
        <div class="chart-pie pt-4 pb-2">
          <div class="chartjs-size-monitor">
            <div class="chartjs-size-monitor-expand">
              <div class=""></div>
            </div>
            <div class="chartjs-size-monitor-shrink">
              <div class=""></div>
            </div>
          </div>
          <canvas id="myPieChart" width="302" height="245" class="chartjs-render-monitor"
            style="display: block; width: 302px; height: 245px;"></canvas>
        </div>
        <div class="mt-4 text-center small">
          <span class="mr-2">
            <i class="bi bi-circle-fill text-primary"></i> Venta(s) /
          </span>
          <span class="mr-2">
            <i class="bi bi-circle-fill text-success"></i> Compra(s)
          </span>
        </div>
      </div>
    </div>
  </div>
</div>