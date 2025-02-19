<fieldset id="mdl-tasa" crud="true" size="md" title="Actualizar Tasa de Cambio">

	<div class="mb-3">
		<div class="input-group">
			<label class="input-group-text" id="lbl-date-tasa" for="tasa"></label>
			<input type="number" min="1" step="0.01" name="tasa" class="form-control"
				placeholder="Tasa de Cambio">
		</div>
	</div>
	<input type="hidden" value="" name="id">
	<input type="hidden" value="addTasa" name="endpoint">

</fieldset>