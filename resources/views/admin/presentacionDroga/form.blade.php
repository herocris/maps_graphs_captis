{{csrf_field()}}
<div class="card-body">
    @if ($presentaciondroga->exists)
    <div class="form-group row">
        <label for="inputDescripcion" class="col-sm-2 col-form-label">Descripción</label>
        <div class="col-sm-10">
            <input type="text" required name="descripcion" class="form-control" id="inputNombre"
                value="{{old('name',$presentaciondroga->descripcion)}}" placeholder="Descripción">
        </div>
        {{--  data-parsley-pattern="^[a-zA-Z\u00C0-\u017F\s]+$" data-parsley-pattern-message='Solo se admiten letras' data-parsley-trigger="keyup"  --}}
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