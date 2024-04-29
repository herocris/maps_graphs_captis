{{csrf_field()}}
<div class="card-body">
    @if ($precursor->exists)
    <div class="form-group row">
        <label for="inputDescripcion" class="col-sm-2 col-form-label">Descripción</label>
        <div class="col-sm-10">
            <input type="text" required name="descripcion" class="form-control" id="inputDescripción"
                value="{{old('name',$precursor->descripcion)}}" placeholder="Descripción">
        </div>
    </div>
    @include('admin.partials.mensages_error', ['nombre' => 'descripcion'])
    
    @else
    <div class="form-group row">
        <label for="inputDescripcion" class="col-sm-2 col-form-label">Descripción</label>
        <div class="col-sm-10">
            <input type="text" required name="descripcion" class="form-control" id="inputDescripcion"
                value="{{old('descripcion')}}" placeholder="Descripción">
        </div>
    </div>
    @include('admin.partials.mensages_error', ['nombre' => 'descripcion'])
    
    @endif
    
    
</div>
<div class="card-footer">
    <button type="submit" class="btn btn-primary">Guardar</button>
</div>