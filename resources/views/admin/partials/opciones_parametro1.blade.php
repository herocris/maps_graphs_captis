<div class="form-group row">
    <label for="tipo_decomiso" class="col-sm-4 col-form-label">Tipo de decomiso</label>
    <div class="col-sm-8">
        <select class="form-select" name="tipo_decomiso" id="tipo_decomiso" >
            <option value="" selected disabled hidden>Selecciona el decomiso</option>
            <option value="Droga">Droga</option>
            <option value="Precursor">Precursor</option>
            <option value="Arma">Arma</option>
            <option value="Municion">Municion</option>
            <option value="Detenido">Detenido</option>
            <option value="Transporte">Transporte</option>
        </select>
        <span style="color:red">
                <strong id="deco_valid" style="display:none;">"El tipo de decomiso es requerido"</strong>
        </span>
    </div>
</div>