<fieldset id="mdl-vehiculo" crud="true" size="md" title="Registrar Vehiculo">

  <div class="row">

    <div class="col-lg-4 col-md col-sm mb-3">
      <div class="input-group">
        <div class="input-group-text d-block p-0 w-100">
          <label class="d-block text-start m-1" for="placa">Placa del Vehiculo</label>
          <div class="input-group input-group-merge">
            <span class="input-group-text input-icon"><i class="bi bi-card-text"></i></span>
            <input type="text" id="placa" name="placa" class="form-control border-start-0" />
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-8 col-md col-sm">

      <div class="input-group">
        <div class="input-group-text d-block p-0 w-100">
          <label class="d-block text-start m-1" for="descripcion">Descripción del Vehiculo</label>

          <div class="input-group input-group-merge">
            <span class="input-group-text input-icon"><i class="bx bx-map"></i></span>
            <input type="text" id="descripcion" name="descripcion" class="form-control border-start-0"
              placeholder="Descripción del Vehiculo a Realizar Despachos">
          </div>
        </div>
      </div>
    </div>
  </div>
  <input type="hidden" value="" name="idvehiculo">
  <input type="hidden" value="add" name="endpoint">
</fieldset>