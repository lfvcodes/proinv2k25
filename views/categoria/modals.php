<fieldset id="mdl-categoria" crud="true" title="Registrar Categoria" size="md">
  <div class="mb-3">
    <div class="input-group">
      <label class="input-group-text" for="id">ID</label>
      <input type="text" disabled id="id" name="id" value="" class="form-control" placeholder="Codigo de Categoria">
    </div>
  </div>
  <div class="mb-3">
    <div class="input-group">
      <label class="input-group-text" for="nom">Categoria/Departamento</label>
      <input type="text" id="nom" name="nom" class="form-control" placeholder="Nombre de Categoría o Departamento">
    </div>
  </div>

  <input type="hidden" value="add" name="endpoint">
</fieldset>