<fieldset id="mdl-guia" crud="true" detail="true" dref="guia.php?rc=" title="Agregar Guia de Despacho">

  <div class="row mb-3">
    <div class="col input-group">
      <label class="input-group-text" for="nguia">
        <i class="bi bi-receipt me-1"></i>N° Guía
      </label>
      <input required class="form-control nguia" maxlength="../rpt/rpt_guia?rc=`+ row['cod']+ `'64" type="text" name="nguia" id="nguia">
    </div>
    <div class="col-lg input-group">
      <label class="input-group-text" for="freg">Fecha</label>
      <input type="date" name="freg" id="freg" class="form-control">
      <label class="input-group-text" for="freg">Hora</label>
      <input type="time" name="ftime" id="ftime" class="form-control">
    </div>
  </div>

  <div class="row mb-3">
    <div class="col input-group">
      <label class="input-group-text" for="optcond">
        <i class="bi bi-person-lines-fill me-1"></i>Conductor
      </label>
      <select required name="optcond" id="optcond" class="form-select cond"></select>
    </div>
    <div class="col input-group">
      <label class="input-group-text" for="vehiculo">
        <i class="bi bi-truck me-1"></i>Vehiculo
      </label>
      <select required name="vehiculo" id="vehiculo" class="form-select vehiculo"></select>
    </div>
  </div>

  <label class="form-label text-primary">Detalle de Guía</label>

  <div class="p-2 mb-3">
    <table class="table table-sm" id="tbl-items">
      <thead>
        <th width="60%">Cliente / Venta</th>
        <th width="15%">#Items</th>
        <th width="20%">Monto</th>
        <th width="5%">
          <button onclick="add();" class="btn btn-sm rounded-pill btn-primary" type="button" id="agregarFila"> +
          </button>
        </th>
      </thead>
      <tbody>
        <tr>
          <td>
            <select required class="form-select form-control vent" name="vent[]"></select>
          </td>
          <td><input readonly class="form-control cant" min="1" step="1" type="number"></td>
          <td><input readonly class="form-control monto" min="0.01" step="0.01" type="number"></td>
          <td><button onclick="remove(this);" type="button" class="btn btn-sm rounded-pill btn-danger">-</button></td>
        </tr>
      </tbody>
    </table>
  </div>

  <div class="row justify-content-end mb-3">
    <div class="col input-group offset-7">
      <label class="input-group-text" for="stotal">
        Total Guía
      </label>
      <input hidden required readonly placeholder="Bs." type="text" class="form-control fw-bold" name="stotal"
        id="stotal">
      <input required readonly placeholder="$." type="text" class="form-control fw-bold" name="stotald" id="stotald">
    </div>
  </div>

  <input type="hidden" value="setGuia" name="endpoint">
</fieldset>