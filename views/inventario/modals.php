<fieldset id="mdl-inventario" crud="true" title="Agregar Nuevo Producto">
  <div class="row mb-3">
    <div id="bar" title="codigo de barras" class="col-lg input-group">

    </div>
    <div class="col-lg m-2">
      <div class="input-group m-2">
        <span class="input-group-text">Código del Articulo</span><i class="bi bi-barcode"></i>
        <input required name="cod_product" maxlength="50" type="text" class="form-control" placeholder="Código Unico de Articulo">
      </div>
      <div class="input-group m-2">
        <span class="input-group-text">Código Alterno</span>
        <input name="cod_alt" type="text" maxlength="50" class="form-control" placeholder="Codigo Auxiliar del Articulo">
      </div>
    </div>
  </div>
  <div class="col mb-3">
    <div class="input-group">
      <span class="input-group-text">Nombre del Articulo</span><i class="bi bi-barcode"></i>
      <input required name="nom_product" type="text" class="form-control" placeholder="Nombre del Articulo">
    </div>
  </div>

  <div class="mb-3">
    <div class="input-group">
      <span class="input-group-text">Descripción</span><i class="bi bi-barcode"></i>
      <input name="desc_product" type="text" class="form-control" placeholder="Descripción del Articulo">
    </div>
  </div>
  <div class="mb-3">
    <div class="input-group">
      <span class="input-group-text">Categoría</span><i class="bi bi-id-card"></i>
      <select name="optgrupo" id="optgrupo" class="form-select form-control">
        <option selected value="">Elegir Grupo o Categoría</option>
      </select>
    </div>
  </div>
  <div class="row">
    <div class="col mb-3">
      <div class="input-group">
        <span class="input-group-text">Unidad de Medida</span><i class="bi bi-barcode"></i>
        <select required name="umedida" id="umedida" class="form-select form-control">
          <option disabled selected value="">Elegir uno</option>
          <option value="BD">Bidon(es)</option>
          <option value="BU">Bulto(s)</option>
          <option value="C">Caja(s)</option>
          <option value="G">Galon(es)</option>
          <option value="L">Litro(s)</option>
          <option value="P">Paquete(s)</option>
          <option value="U">Unidad(es)</option>
        </select>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col mb-3">
      <div class="input-group">
        <span class="input-group-text">Precio Costo $</span>
        <input required type="text" min="1" id="pcosto" name="pcosto" class="form-control" pattern="^\d+(\.\d{1,2})?$"
          title="Ingresa un monto numérico válido">
      </div>
      <small hidden class="canterior text-muted">Costo Anterior: <b id="pcanterior"></b></small>
    </div>
    <div class="col mb-3">
      <div class="input-group">
        <span class="input-group-text">Precio Venta $</span>
        <input type="text" id="pventa" name="pventa" class="form-control" pattern="^\d+(\.\d{1,2})?$"
          title="Ingresa un monto numérico válido">
      </div>
      <small hidden class="canterior text-muted">P.Venta Anterior: <b id="pvanterior"></b></small>
    </div>
    <div class="col mb-3">
      <div class="input-group">
        <span class="input-group-text">% Utilidad Bruta</span>
        <input type="text" id="ub" name="ub" readonly class="form-control" />
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-sm-10 col-md-4 mb-3">
      <div class="input-group">
        <span class="input-group-text">Stock Inicial</span><i class="bi bi-barcode"></i>
        <input name="stock" min="0" type="number" step="1" class="form-control" placeholder="Stock del Articulo">
      </div>
    </div>
    <div class="col-sm-10 col-md-4 mb-3">
      <div class="input-group">
        <span class="input-group-text">Stock Minimo</span><i class="bi bi-barcode"></i>
        <input required name="stockminimo" min="1" type="number" step="1" class="form-control"
          placeholder="Minimo del Articulo">
      </div>
    </div>
    <div class="col-sm-10 col-md-4 mb-3">
      <div class="input-group">
        <span class="input-group-text">Stock Maximo</span><i class="bi bi-barcode"></i>
        <input required name="stockmaximo" type="number" min="1" step="1" class="form-control"
          placeholder="Maximo del Articulo">
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-12 text-center">
      <button hidden id="btn-see-prov" type="button" onclick="viewSuppliers()"
        title="Ver Proveedores de este producto" class="btn btn-outline-primary rounded-pill">
        Ver Proveedores del Producto <i class="bi bi-arrow-down"></i>
      </button>
    </div>
  </div>
  <div class="row mt-4">
    <table hidden id="tbl-prov" class="table table-sm table-striped text-center">
      <thead>
        <th>RIF</th>
        <th>Nombre</th>
        <th>Ultima Compra</th>
      </thead>
      <tbody></tbody>
    </table>
  </div>

  <input type="hidden" value="" name="id">
  <input type="hidden" value="add" name="endpoint">
</fieldset>