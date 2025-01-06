<fieldset id="mdl-conductor" crud="true" title="Registrar Conductor">
  <div class="row mb-3">

    <div class="col-lg col-md col-sm m-auto">

      <div class="input-group mb-3">
        <div class="input-group-text d-block p-0 w-100">
          <label for="nac" class="d-block text-start m-1">Tipo de Identificación</label>
          <div class="d-flex">
            <select required class="form-select border-0 rounded-0 w-auto" name="nac" id="nac">
              <option value="V">V</option>
              <option value="E">E</option>
              <option value="J">J</option>
              <option value="G">G</option>
              <option value="P">P</option>
            </select>
            <input required="" type="text" minlength="7" id="id" name="id" class="border-0 form-control m-auto"
              placeholder="#RIF O #Identificación">
          </div>
        </div>
      </div>

    </div>

    <div class="col-lg col-md col-sm m-auto">
      <div class="input-group mb-3">
        <div class="input-group-text d-block p-0 w-100">
          <label for="nom" class="d-block text-start m-1">Nombre(s)</label>
          <div class="d-flex">
            <span class="input-group-text input-icon"><i class="bi bi-card-text"></i></span>
            <input required type="text" minlength="2" id="nom" name="nom" class="form-control border-start-0"
              placeholder="Nombre(s) del Conductor">
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg col-md col-sm m-auto">
      <div class="input-group mb-3">
        <div class="input-group-text d-block p-0 w-100">
          <label for="ape" class="d-block text-start m-1">Apellido(s)</label>
          <div class="d-flex">
            <span class="input-group-text input-icon"><i class="bi bi-card-text"></i></span>
            <input required type="text" minlength="2" id="ape" name="ape" class="form-control border-start-0"
              placeholder="Apellidos(s) del Conductor">
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">

    <div class="col-lg-4 col-md col-sm mb-3">
      <div class="input-group">
        <div class="input-group-text d-block p-0 w-100">
          <label class="d-block text-start m-1" for="tel">Teléfono</label>
          <div class="input-group input-group-merge">
            <span class="input-group-text input-icon"><i class="bx bx-phone"></i></span>
            <input type="text" minlength="11" id="tel" name="tel" class="form-control border-start-0"
              placeholder="9999-9999999">
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-8 col-md col-sm">

      <div class="input-group">
        <div class="input-group-text d-block p-0 w-100">
          <label class="d-block text-start m-1" for="dir">Dirección</label>

          <div class="input-group input-group-merge">
            <span class="input-group-text input-icon"><i class="bx bx-map"></i></span>
            <input type="text" id="dir" name="dir" class="form-control border-start-0"
              placeholder="Dirección del Conductor">
          </div>
        </div>
      </div>
    </div>
  </div>


  <input type="hidden" value="add" name="endpoint">
</fieldset>