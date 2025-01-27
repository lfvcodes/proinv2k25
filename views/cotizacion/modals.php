<fieldset id="mdl-cotizacion" crud="true" title="Registrar Cotización">
   <div class="row mb-3">
      <div class="col input-group">
         <label class="input-group-text" for="optcliente">
            <i class="menu-icon tf-icons bx bx-user-pin"></i>
            Cliente
         </label>
         <select required class="form-select cli" id="optcliente" name="optcliente">
            <option value="">Seleccionar Cliente</option>
         </select>

      </div>
      <div class="col input-group">
         <label class="input-group-text" for="freg">Fecha de Cotización</label>
         <input type="date" name="freg" id="freg" class="form-control">
         <input type="time" name="ftime" id="ftime" class="form-control">
      </div>

      <small id="refer" class="text-muted">Referido por: </small>
   </div>

   <div class="row mb-3">
      <div class="col input-group">
         <label class="input-group-text" for="ncot">
            <i class="bi bi-receipt me-1"></i>N° Cotizacion
         </label>
         <input required class="form-control ncot" maxlength="64" type="text" name="ncot" id="ncot">
      </div>
      <div class="col input-group">
         <label class="input-group-text" for="fex">Fecha de Expedición</label>
         <input type="date" name="fex" id="fex" class="form-control">
      </div>
   </div>

   <div class="row mb-3">
      <div class="col input-group">
         <label class="input-group-text" for="desc">
            Concepto
         </label>
         <input required class="form-control desc" maxlength="42" type="text" name="desc" id="desc">
      </div>
   </div>

   <label class="form-label text-primary">Detalle de Cotización</label>

   <div class="row mb-3">
      <table class="table table-sm" id="tbl-cond">
         <thead>
            <th width="40%">Item</th>
            <th width="15%">Cantidad</th>
            <th width="20%">Precio</th>
            <th width="20%">Total Item</th>
            <th width="5%">
               <button type="button" onclick="addCot();" class="btn btn-sm btn-primary p-2 rounded-pill" id="agregarFila">
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
                  <button onclick="removeCot(this);" type="button" class="btn btn-sm btn-danger rounded-pill p-2">
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

         <input required readonly placeholder="$." type="text" class="form-control fw-bold" name="stotald" id="stotald">
      </div>
   </div>
   <input type="hidden" value="add" name="endpoint">
</fieldset>