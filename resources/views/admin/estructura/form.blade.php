{{csrf_field()}}
<div class="card-body">
    @if ($estructura->exists)
    <div class="form-group row">
        <label for="inputDescripcion" class="col-sm-2 col-form-label">Descripción</label>
        <div class="col-sm-10">
            <input type="text" required name="descripcion" class="form-control" id="inputNombre"
                value="{{old('name',$estructura->descripcion)}}" placeholder="Descripción">
        </div>
    </div>
    @else
    <div class="form-group row">
        <label for="inputDescripcion" class="col-sm-2 col-form-label">Descripción</label>
        <div class="col-sm-10">
            <input type="text" required name="descripcion" class="form-control" id="inputDescripcion"
                value="{{old('descripcion')}}" placeholder="Descripción">
        </div>
    </div>
    @endif
    @include('admin.partials.mensages_error', ['nombre' => 'descripcion'])
</div>
<div class="card-footer">
    <button type="submit" class="btn btn-primary">Guardar</button>
</div>