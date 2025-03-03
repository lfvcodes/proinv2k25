<fieldset id="mdl-venta" crud="true" title="Registrar Venta">
   <div class="row mb-3">
      <div class="col input-group">
         <label class="input-group-text" for="optcli">
            <i class="menu-icon tf-icons bx bx-user-pin"></i>
            Cliente
         </label>
         <select required class="form-select cli" id="optcli" name="optcli">
            <option value="">Seleccionar Cliente</option>
         </select>
      </div>

      <small id="refer" class="text-muted">Referido por: </small>

   </div>

   <div class="row mb-3">
      <div class="col input-group">
         <label class="input-group-text" for="freg">Fecha de venta</label>
         <input type="date" name="freg" id="freg" class="form-control">
      </div>

      <div class="col input-group">
         <label class="input-group-text" for="fact">
            <i class="bi bi-receipt me-1"></i>Nota de Entrega
         </label>
         <input required class="form-control fact" maxlength="64" type="text" name="fact" id="fact">
      </div>
      <div class="col input-group">
         <label class="input-group-text" for="desc">
            Concepto
         </label>
         <input required class="form-control desc" maxlength="42" type="text" name="desc" id="desc">
      </div>
   </div>

   <div class="row mb-3">
      <div hidden class="col input-group">
         <label class="input-group-text" for="mpago">
            <i class="bi bi-receipt me-1"></i>Forma de Pago
         </label>
         <select class="form-select mpago" name="mpago" id="mpago">
            <option disabled selected value="">--</option>
            <option value="B">BS EFECTIVO</option>
            <option value="BT">BS TRANSFERENCIA</option>
            <option value="D">DIVISA EFECTIVO</option>
            <option value="DT">DIVISA TRANSFERENCIA</option>
         </select>
      </div>
      <div hidden class="col input-group">
         <label class="input-group-text" for="tasa">Tasa de Cambio</label>
         <input readonly id="tasa" name="tasa" class="form-control tasa" value="" min="0.01" step="0.01" type="number">
      </div>
   </div>

   <label class="form-label text-primary my-0">Detalle de venta</label>

   <div class="row mb-3">
      <table class="table table-sm" id="items-venta">
         <thead>
            <th width="40%">Item</th>
            <th width="15%">Cantidad</th>
            <th width="20%">Precio</th>
            <th width="20%">Total Item</th>
            <th width="5%">
               <button type="button" onclick="addItemVenta();" class="btn btn-sm btn-primary p-2 rounded-pill" id="agregarFila">
                  <i class="bi bi-plus-circle m-0"></i>
               </button>
            </th>

         </thead>
         <tbody>
            <tr>
               <td>
                  <select class="form-select form-control prod" name="prod[]"></select>
               </td>
               <td><input required onkeyup="calcm(this)" onchange="calcm(this)" name="cant[]" class="form-control cant" min="1" step="1" type="number"></td>
               <td><input required readonly name="monto[]" class="form-control monto" min="0.01" step="0.01" type="number"></td>
               <td><input readonly class="form-control titem" type="number"></td>
               <td>
                  <button onclick="removeItemVenta(this);" type="button" class="btn btn-sm btn-danger rounded-pill p-2">
                     <i class="bi bi-dash-circle m-0"></i>
                  </button>
               </td>
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

   <div class="row justify-content-end mb-3">
      <div class="col input-group offset-7">
         <label class="input-group-text" for="tventa">Tipo de venta</label>
         <select onchange="changeCredit(this)" required name="tventa" id="tventa" class="form-select form-control">
            <option selected disabled value="">Elige uno</option>
            <option value="C">Credito</option>
            <option value="D">Debito</option>
         </select>
      </div>
   </div>
   <div id="flimit" hidden class="row justify-content-end mb-3">
      <div class="col input-group offset-7">
         <label class="input-group-text" for="stotal">Fecha Limite de Credito</label>
         <input required class="form-control" type="date" name="flimite" id="flimite">
      </div>
   </div>

   <input type="hidden" value="setVenta" name="endpoint">

</fieldset>