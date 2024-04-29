{{csrf_field()}}
<div class="card-body">
    @if ($parametro->exists)
    <div class="form-group row">
        <label for="inputDescripcion" class="col-sm-2 col-form-label">Descripción</label>
        <div class="col-sm-10">
            <input type="text" name="descripcion" class="form-control" id="inputNombre"
                value="{{old('descripcion',$parametro->descripcion)}}" placeholder="Descripción">
        </div>
    </div>
    <div class="form-group row">
        <label for="inputTipo" class="col-sm-2 col-form-label">Tipo de parametro</label>
        <div class="col-sm-10">
        <select name="tipo_parametro_id" id="inputTipo" class="lista">
            @foreach($tipoParametros as $tipoParametro)
                
                <option value={{$parametro->tipo_parametro_id}}>{{$parametro->Tipo_parametro->descripcion}}</option>   
            @endforeach             
        </select>
            
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
        <label for="inputTipo" class="col-sm-2 col-form-label">Tipo de parametro</label>
        <div class="col-sm-10">
        <select class="form-select" name="tipo_parametro_id" id="inputTipo">
            @foreach($tipoParametros as $tipoParametro)
                <option value="" selected disabled hidden>Selecciona el tipo</option>
                <option value={{$tipoParametro->id}}>{{$tipoParametro->descripcion}}</option>   
            @endforeach             
        </select>
            
        </div>
    </div>
    @endif
    @include('admin.partials.mensages_error', ['nombre' => 'descripcion'])
    @include('admin.partials.mensages_error', ['nombre' => 'tipo_parametro_id'])
</div>
<div class="card-footer">
    <button type="submit" class="btn btn-primary">Guardar</button>
</div>