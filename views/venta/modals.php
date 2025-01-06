<fieldset id="mdl-venta" title="Registrar Venta">
   <div class="row mb-3">
      <div class="col-lg col-md col-sm">
         <div class="input-group">
            <label class="input-group-text" for="optcli">
               <i class="menu-icon tf-icons bx bx-user-pin"></i>
               Cliente
            </label>
            <select required class="form-select form-control cli" id="optcli" name="optcli">
               <option value="">Seleccionar Cliente</option>
            </select>
         </div>
         <small id="refer" class="text-muted">Referido por: </small>
         <small id="cxcvenc" class=""></small>
      </div>


      <div class="col-lg col-md col-sm">
         <div class="input-group">
            <label class="input-group-text" for="freg">Fecha</label>
            <input type="date" name="freg" id="freg" class="form-control">
            <label class="input-group-text" for="ftime">Hora</label>
            <input type="time" name="ftime" id="ftime" class="form-control">
         </div>
      </div>

   </div>

   <div class="row mb-3">
      <div class="col-lg col-md col-sm input-group mb-3">
         <label class="input-group-text" for="fact">
            <i class="bi bi-receipt me-1"></i>Factura
         </label>
         <input required class="form-control fact" maxlength="6" type="text" name="fact" id="fact">
      </div>
      <div class="col-lg col-md col-sm input-group mb-3">
         <label class="input-group-text" for="desc">
            Concepto
         </label>
         <input required class="form-control desc" maxlength="120" type="text" name="desc" id="desc">
      </div>
   </div>

   <div hidden id="row-tpago" class="row mb-3">
      <div lass="col input-group">
         <label class="input-group-text" for="mpago">
            <i class="bi bi-receipt me-1"></i>Forma de Pago
         </label>
         <select class="form-select mpago" name="mpago" id="mpago">
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

   <div class="row mb-3">
      <div class="col-lg col-md col-sm">
         <div class="input-group mb-3">
            <label class="input-group-text" for="tventa">Tipo de venta</label>
            <select onchange="changeCredit(this)" required name="tventa" id="tventa" class="form-select form-control">
               <option selected value="F">Factura Credito</option>
               <option value="FD">Factura Debito</option>
               <option value="C">Nota Credito</option>
               <option value="D">Nota Debito</option>
            </select>
         </div>
      </div>

      <div id="flimit" class="col-lg col-md col-sm">
         <div class="input-group mb-3">
            <label class="input-group-text" for="flimite">Fecha Limite de Credito</label>
            <input required class="form-control " type="date" name="flimite" id="flimite">
         </div>

         <div class="input-group">
            <label hidden for="iva">Mostrar Nota de Entrega con Iva: </label>
            <input hidden class="ms-2" type="checkbox" name="iva" id="iva">
         </div>

      </div>
   </div>


   <label class="form-label text-primary">Detalle de venta</label>

   <div class="row mb-3">
      <table class="table table-sm" id="tbl-cond">
         <thead>
            <th width="40%">Item</th>
            <th width="15%">Cantidad</th>
            <th width="10%">Stock</th>
            <th width="15%">Precio</th>
            <th width="15%">Total Item</th>
            <th width="5%"><button onclick="add();" class="btn btn-sm btn-primary" type="button" id="agregarFila"> + </button></th>
         </thead>
         <tbody>
            <tr>
               <td>
                  <select class="form-select form-control prod" name="prod[]"></select>
               </td>
               <td><input required onkeyup="calcm(this)" onchange="calcm(this)" name="cant[]" class="form-control cant" min="1" step="1" type="number"></td>
               <td><input readonly class="form-control stock" type="number"></td>
               <td><input required readonly name="monto[]" class="form-control monto" min="0.01" step="0.01" type="number"></td>
               <td><input readonly class="form-control titem" type="number"></td>
               <td><button onclick="remove(this);" type="button" class="btn btn-sm btn-danger">-</button></td>
            </tr>
         </tbody>
      </table>
   </div>

   <div class="row justify-content-end">
      <div class="col input-group offset-7">
         <label class="input-group-text" for="stotal">
            Total
         </label>
         <input hidden required readonly placeholder="Bs." type="text" class="form-control fw-bold" name="stotal" id="stotal">
         <input required readonly placeholder="$." type="text" class="form-control fw-bold" name="stotald" id="stotald">
      </div>
   </div>

   <input type="hidden" value="<?php echo  $_SESSION['pro']['empresa']['percomision']; ?>" name="comision">
   <input type="hidden" value="setVenta" name="endpoint">

</fieldset>