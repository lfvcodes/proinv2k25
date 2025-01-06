<fieldset id="mdl-cxc" title="Registrar Cuenta de Cobro">
  <div class="row mb-3">
    <div class="col input-group">
      <label class="input-group-text" for="client">Cliente</label>
      <select required class="form-select cli" name="client" id="client">
        <option value="">Seleccionar uno</option>
      </select>
    </div>
  </div>

  <div class="row mb-3">
    <div class="col input-group">
      <label class="input-group-text" for="monto">Monto a Pagar </label>
      <input class="form-control" placeholder="$" required step="0.01" type="number" name="monto" id="monto">
    </div>

    <div class="col input-group">
      <label class="input-group-text" for="vence">Fecha de Vencimiento</label>
      <input class="form-control" required type="date" name="vence" id="vence">
    </div>
  </div>

  <div class="row mb-3">
    <div class="col input-group">
      <label class="input-group-text" for="concepto">Concepto / Descripción</label>
      <input placeholder="Describa el concepto de pago a proveedor"
        class="form-control" required type="text" maxlength="350" name="concepto" id="concepto">
    </div>
  </div>
  <input type="hidden" value="setCuenta" name="endpoint">
</fieldset>

<fieldset id="mdl-abono" title="Registrar Abono de Cobro">
  <label class="form-label text-primary">Abonos Asociados a la Cuenta</label>
  <div class="row mb-3">
    <table id="tbl-abono" class="table table-sm table-striped">
      <thead>
        <th>Fecha</th>
        <th>Monto</th>
        <th>Concepto</th>
        <th width="5%"><button onclick="addAbonoCxc();" class="btn btn-sm btn-primary" type="button" id="agregarFila"> + </button></th>
      </thead>
      <tbody>
        <tr>
          <td><input class="form-control fec" max="<?php echo date('Y-m-d'); ?>" required type="date" name="fec[]"></td>
          <td><input class="form-control monto" placeholder="$" required step="0.01" type="number" name="monto[]"></td>
          <td><input placeholder="Describa el concepto de Abono"
              class="form-control" required type="text" maxlength="350" name="concepto[]"></td>
          <td><button onclick="removeAbonoCxc();" type="button" class="btn btn-sm btn-danger">-</button></td>
        </tr>
      </tbody>
    </table>
  </div>
  <input type="hidden" name="id">
  <input type="hidden" name="mdeuda">
  <input type="hidden" value="setAbono" name="endpoint">
</fieldset>

<fieldset id="mdl-solvent" title="Cuentas por Cobrar Confirmadas">
  <div class="row mb-3">
    <table id="tbl-solv" class="table table-sm table-striped">
      <thead>
        <th>Comprobante</th>
        <th>Cliente</th>
        <th>Fecha Cobro</th>
        <th>Monto</th>
        <th>Estado</th>
        <th>Revertir</th>
      </thead>
      <tbody></tbody>
    </table>
  </div>
</fieldset>

<fieldset id="mdl-venta" title="Registrar Venta">

  <div class="row mb-3">
    <div class="col input-group mb-3">
      <label class="input-group-text" for="optcli">
        <i class="menu-icon tf-icons bx bx-user-pin"></i>
        Cliente
      </label>
      <select required class="form-select cli" id="optcli" name="optcli">
        <option value="">Seleccionar Cliente</option>
      </select>
    </div>
    <small id="refer" class="text-muted">Referido por: </small>

    <div class="col input-group">
      <label class="input-group-text" for="freg">Fecha de venta</label>
      <input type="date" name="freg" id="freg" class="form-control">
    </div>
    <div class="col input-group">
      <label class="input-group-text" for="fcobro">Fecha de Cobro</label>
      <input required type="date" name="fcobro" id="fcobro" class="form-control">
    </div>

  </div>

  <div class="row mb-3">
    <div class="col input-group">
      <label class="input-group-text" for="fact">
        <i class="bi bi-receipt me-1"></i>Nota de Entrega
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
      <input id="tasa" name="tasa" class="form-control tasa" min="0.01" step="0.01" type="number">
    </div>
  </div>

  <label class="form-label text-primary">Detalle de venta</label>

  <div class="row mb-3">
    <table class="table table-sm" id="tbl-cond">
      <thead>
        <th width="40%">Item</th>
        <th width="15%">Cantidad</th>
        <th width="20%">Precio</th>
        <th width="20%">Total Item</th>
        <th width="5%"></th>
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
  <input type="hidden" value="confirmVenta" name="endpoint">

</fieldset>