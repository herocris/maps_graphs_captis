
<div class="card-body">
    @if ($droga->exists)
    

    <input name="_token" value="{{ csrf_token() }}" type="hidden">
    <input name="_method" value="PUT" type="hidden">
    <div class="form-group row">
        <label for="inputDescripcion" class="col-sm-2 col-form-label">Descripción</label>
        <div class="col-sm-10">
            <input type="text" required name="descripcion" class="form-control" id="inputNombre"
                value="{{old('descripcion',$droga->descripcion)}}" placeholder="Descripción">
        </div>
    </div>
    <div class="form-group row">
        <label for="inputTipo" class="col-sm-2 col-form-label">Tipo de droga</label>
        <div class="col-sm-10">
        <select class="form-select" required name="tipo_droga_id" id="inputTipo" class="lista">
            @foreach($tipoDrogas as $tipoDroga)
                <option {{$tipoDroga->id==$droga->tipo_droga_id?'selected':''}} value={{$tipoDroga->id}}>{{$tipoDroga->descripcion}}</option>   
            @endforeach
        </select>
            
        </div>
    </div>
    @else
    {{csrf_field()}}
    <div class="form-group row">
        <label for="inputDescripcion" class="col-sm-2 col-form-label">Descripción</label>
        <div class="col-sm-10">
            <input type="text" required  name="descripcion" class="form-control" id="inputDescripcion"
                value="{{old('descripcion')}}" placeholder="Descripción" >
                @include('admin.partials.mensages_error', ['nombre' => 'descripcion'])
        </div>
    </div>
    <div class="form-group row">
        <label for="inputTipo" class="col-sm-2 col-form-label">Tipo de droga</label>
        <div class="col-sm-10">
        <select class="form-select" required data-placeholder="Selecciona tipo" name="tipo_droga_id" id="inputTipo" >
            @foreach($tipoDrogas as $tipoDroga)
                <option value="" {{ old('tipo_droga_id') == null ? 'selected' : '' }} disabled hidden>Selecciona el tipo</option>
                <option value={{$tipoDroga->id}} {{ old('tipo_droga_id') == $tipoDroga->id ? 'selected' : '' }}>{{$tipoDroga->descripcion}}</option>   
            @endforeach             
        </select>
            @include('admin.partials.mensages_error', ['nombre' => 'tipo_droga_id'])
        </div>
        
    </div>
    @endif
    
    
</div>
<div class="card-footer">
    <button type="submit" class="btn btn-primary">Guardar</button>
</div>

@push('scripts')
<script>
$('#inputTipo').select2({
    theme: 'bootstrap4',
});
</script>
@endpush

{{--  {{ old('tipo_droga_id') == $tipoDroga->id ? 'selected' : '' }}  --}}