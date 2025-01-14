<fieldset id="mdl-compra" title="Confirmar Compra (Cuenta por Pagar)">
  <div id="form-contained">
    <div class="row mb-3">
      <div class="col input-group">
        <label class="input-group-text" for="optprov">
          <i class="menu-icon tf-icons bx bx-briefcase"></i>
          Proveedor
        </label>
        <select required class="form-select prov" id="optprov" name="optprov">
          <option value="">Seleccionar Proveedor</option>
        </select>
      </div>
      <div class="col input-group">
        <label class="input-group-text" for="freg">Fecha de Compra</label>
        <input required type="date" name="freg" id="freg" class="form-control">
        <label class="input-group-text" for="fcobro">Fecha de Cobro</label>
        <input required type="date" name="fcobro" id="fcobro" class="form-control">
      </div>
    </div>

    <div class="row mb-3">
      <div class="col input-group">
        <label class="input-group-text" for="fact">
          <i class="bi bi-receipt me-1"></i>Factura
        </label>
        <input readonly class="form-control fact" maxlength="64" type="text" name="fact" id="fact">
      </div>
      <div class="col input-group">
        <label class="input-group-text" for="desc">
          Concepto
        </label>
        <input required class="form-control desc" maxlength="100" type="text" name="desc" id="desc">
      </div>
    </div>

    <div class="row mb-3">
      <div class="col input-group">
        <label class="input-group-text" for="mpago">
          <i class="bi bi-receipt me-1"></i>Forma de Pago
        </label>
        <select required class="form-select mpago" name="mpago" id="mpago">
          <option disabled selected value="">--</option>
          <option value="B">BS EFECTIVO</option>
          <option value="BT">BS TRANSFERENCIA</option>
          <option value="D">DIVISA EFECTIVO</option>
          <option value="DT">DIVISA TRANSFERENCIA</option>
          <option value="M">BS + DIVISA</option>
          <option value="MB">BS EFECTIVO + TRANSFERENCIA</option>
        </select>
      </div>
      <div class="col input-group">
        <label class="input-group-text" for="tasa">Tasa de Cambio</label>
        <input required id="tasa" name="tasa" class="form-control tasa" min="0.01" step="0.01" type="number">
      </div>
    </div>

    <label class="form-label text-primary">Detalle de Compra</label>

    <div class="row mb-3">
      <table class="table table-sm" id="tbl-compra">
        <thead>
          <th width="50%">Item</th>
          <th width="15%">Cantidad</th>
          <th width="15%">Precio</th>
          <th width="19%">Total Item</th>
          <th width="1%"></th>
        </thead>
        <tbody>
          <tr>
            <td>
              <select class="form-select form-control prod" name="prod[]"></select>
            </td>
            <td><input required onkeyup="calcm(this)" onchange="calcm(this)" name="cant[]" class="form-control cant" min="1" step="1" type="number"></td>
            <td><input required readonly name="monto[]" class="form-control monto" min="0.01" step="0.01" type="number"></td>
            <td><input readonly class="form-control titem" type="number"></td>
            <td></td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="row justify-content-end mb-3">
      <div class="col input-group offset-7">
        <label class="input-group-text" for="stotal">
          Total
        </label>
        <input hidden required readonly placeholder="Bs." type="text" class="form-control fw-bold" name="stotal" id="stotal">
        <input required readonly placeholder="$." type="text" class="form-control fw-bold" name="stotald" id="stotald">
      </div>
    </div>
  </div>
</fieldset>

<fieldset id="mdl-abono" size="lg" title="Registrar Abono de Pago">

  <label class="form-label text-primary">Abonos Asociados a la Cuenta</label>
  <div id="form-contained" class="row container mb-3">
    <table id="tbl-abono" class="table-sm table-striped">
      <thead>
        <th>Fecha</th>
        <th>Monto</th>
        <th>Concepto</th>
        <th class="text-center" width="5%">
          <button type="button" onclick="addAbono();" class="btn btn-sm btn-primary p-2 rounded-pill" id="agregarFila">
            <i class="bi bi-plus-circle m-0"></i>
          </button>
        </th>
      </thead>
      <tbody>
        <tr>
          <td><input class="form-control fec" required type="date" name="fec[]"></td>
          <td><input class="form-control monto" placeholder="$" required step="0.01" type="number" name="monto[]"></td>
          <td><input placeholder="Describa el concepto de Abono"
              class="form-control" required type="text" maxlength="350" name="concepto[]"></td>
          <td class="text-center">
            <button onclick="removeAbono(this);" type="button" class="btn btn-sm btn-danger p-2 rounded-pill">
              <i class="bi bi-dash-circle m-0"></i>
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</fieldset>

<fieldset id="mdl-solvent" title="Cuentas por Pagar Confirmadas Recientemente">
  <div class="row mb-3">
    <table id="tbl-solv" class="table table-sm table-striped">
      <thead>
        <th>Comprobante</th>
        <th>Proveedor</th>
        <th>Fecha Cobro</th>
        <th>Monto</th>
        <th>Estado</th>
        <th>Revertir</th>
      </thead>
      <tbody></tbody>
    </table>
  </div>

</fieldset>