{{csrf_field()}}
<div class="card-body">
    @if ($presentacion->exists)
    <div class="form-group row">
        <label for="inputDescripcion" class="col-sm-2 col-form-label">Descripción</label>
        <div class="col-sm-10">
            <input type="text" name="descripcion" class="form-control" id="inputNombre"
                value="{{old('name',$presentacion->descripcion)}}" placeholder="Descripción">
        </div>
    </div>
    <div class="form-group row">
        <label for="inputPeso" class="col-sm-2 col-form-label">Peso aproximado</label>
        <div class="col-sm-10">
            <input type="text" name="peso" class="form-control" id="inputPeso"
                value="{{old('peso',$presentacion->peso)}}" placeholder="Peso">
        </div>
    </div>
    @else
    <div class="form-group row">
        <label for="inputDescripcion" class="col-sm-2 col-form-label">Descripción</label>
        <div class="col-sm-10">
            <input type="text" name="descripcion" class="form-control" id="inputDescripcion"
                value="{{old('descripcion')}}" placeholder="Descripción">
        </div>
    </div>
    <div class="form-group row">
        <label for="inputPeso" class="col-sm-2 col-form-label">Peso aproximado</label>
        <div class="col-sm-10">
            <input type="text" name="peso" class="form-control" id="inputPeso" value="{{old('peso')}}"
                placeholder="Peso">
        </div>
    </div>
    @endif
    @include('admin.partials.mensages_error', ['nombre' => 'descripcion'])
    @include('admin.partials.mensages_error', ['nombre' => 'peso'])
</div>
<div class="card-footer">
    <button type="submit" class="btn btn-primary">Guardar</button>
</div>