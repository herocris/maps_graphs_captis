<div class="modal fade" id="editDetenidoModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar detenido</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form-horizontal" data-parsley-validate method="POST" id="form_edit_detenido">

                <div class="modal-body">
                    
                    <input name="_token" value="{{ csrf_token() }}" type="hidden">
                    <input name="_method" value="PUT" type="hidden">
                    <input id="detenido_decomiso_id_" name="detenido_decomiso_id_" type="hidden" value={{$decomiso->id}}>

                    <div class="form-group row">
                        <label for="nombreDetenido_" class="col-sm-3 col-form-label">Nombre</label>
                        <div class="col-sm-9">
                            <input type="text" required name="nombreDetenido_" class="form-control" id="nombreDetenido_" value="{{old('nombreDetenido_')}}"
                                placeholder="Nombre" autocomplete="off">
                            @include('admin.partials.mensages_error', ['nombre' => 'nombreDetenido_'])
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label for="alias_" class="col-sm-3 col-form-label">Alias</label>
                        <div class="col-sm-9">
                            <input type="text" name="alias_" class="form-control" id="alias_" value="{{old('alias_')}}"
                                placeholder="Alias" autocomplete="off">
                            @include('admin.partials.mensages_error', ['nombre' => 'alias_'])
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="identidad_" class="col-sm-3 col-form-label">Identidad</label>
                        <div class="col-sm-9">
                            <input  type="text" name="identidad_" class="form-control" id="identidad_"
                                value="{{old('identidad_')}}" placeholder="Identidad" autocomplete="off">
                            @include('admin.partials.mensages_error', ['nombre' => 'identidad_'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="edad_" class="col-sm-3 col-form-label">Edad</label>
                        <div class="col-sm-9">
                            <input  type="text" name="edad_" class="form-control" id="edad_" value="{{old('edad_')}}"
                                placeholder="Edad" min="0" max="99" step="100" 
                            data-parsley-validation-threshold="1" data-parsley-trigger="keyup" 
                            data-parsley-type="integer" autocomplete="off">
                            @include('admin.partials.mensages_error', ['nombre' => 'edad_'])
                        </div>
                    </div>
                    <div class="form-group row" id="genero_div_">
                        <label for="genero_" class="col-sm-3 col-form-label">Género</label>
                        <div class="col-sm-9">
                            <select   class="form-select" name="genero_" id="genero_">
                                {{--  <option value="" {{ old('genero_') == null ? ' selected' : '' }} disabled hidden>Selecciona el genero</option>  --}}
                                <option value="M" {{ old('genero_') == "M" ? ' selected' : '' }}>Masculino</option>
                                <option value="F" {{ old('genero_') == "F" ? ' selected' : '' }}>Femenino</option>
                            </select>
                            @include('admin.partials.mensages_error', ['nombre' => 'genero_'])
                        </div>
                    </div>
                    <div class="form-group row" id="tip_id_div_">
                        <label for="identificacion_id_" class="col-sm-3 col-form-label">Tipo de identidad</label>
                        <div class="col-sm-9">
                            <select   class="form-select" name="identificacion_id_" id="identificacion_id_">
                                @foreach($indentidades as $indentidad)
                                {{--  <option value="" {{ old('identificacion_id_') == null ? ' selected' : '' }} disabled hidden>Selecciona el tipo de identidad</option>  --}}
                                <option value={{$indentidad->id}}{{ old('identificacion_id_') == $indentidad->id ? ' selected' : '' }}>{{$indentidad->descripcion}}</option>
                                @endforeach
                            </select>
                            @include('admin.partials.mensages_error', ['nombre' => 'identificacion_id_'])
                        </div>
                    </div>
                    <div class="form-group row" id="estructura_div_">
                        <label for="estructura_id_" class="col-sm-3 col-form-label">Estructura</label>
                        <div class="col-sm-9">
                            <select  class="form-select" name="estructura_id_" id="estructura_id_"
                                value="{{old('estructura_id_')}}">
                                @foreach($estructuras as $estructura)
                                {{--  <option value="" {{ old('estructura_id_') == null ? ' selected' : '' }} disabled hidden>Selecciona la estructura</option>  --}}
                                <option value={{$estructura->id}}{{ old('estructura_id_') == $estructura->id ? ' selected' : '' }}>{{$estructura->descripcion}}</option>
                                @endforeach
                            </select>
                            @include('admin.partials.mensages_error', ['nombre' => 'estructura_id_'])
                        </div>
                    </div>
                    <div class="form-group row" id="ocupacion_div_">
                        <label for="ocupacion_id_" class="col-sm-3 col-form-label">Ocupación</label>
                        <div class="col-sm-9">
                            <select  class="form-select" name="ocupacion_id_" id="ocupacion_id_" value="{{old('ocupacion_id_')}}">
                                @foreach($ocupaciones as $ocupacion)
                                {{--  <option value="" {{ old('ocupacion_id_') == null ? ' selected' : '' }} disabled hidden>Selecciona la ocupación</option>  --}}
                                <option value={{$ocupacion->id}}{{ old('ocupacion_id_') == $ocupacion->id ? ' selected' : '' }}>{{$ocupacion->descripcion}}</option>
                                @endforeach
                            </select>
                            @include('admin.partials.mensages_error', ['nombre' => 'ocupacion_id_'])
                        </div>
                    </div>
                    <div class="form-group row" id="civil_div_">
                        <label for="estado_civil_id_" class="col-sm-3 col-form-label">Estado civil</label>
                        <div class="col-sm-9">
                            <select  class="form-select" name="estado_civil_id_" id="estado_civil_id_"
                                value="{{old('estado_civil_id_')}}">
                                @foreach($estados as $estado)
                                {{--  <option value="" {{ old('estado_civil_id_') == null ? ' selected' : '' }} disabled hidden>Selecciona el estado civil</option>  --}}
                                <option value={{$estado->id}}{{ old('estado_civil_id_') == $estado->id ? ' selected' : '' }}>{{$estado->descripcion}}</option>
                                @endforeach
                            </select>
                            @include('admin.partials.mensages_error', ['nombre' => 'estado_civil_id_'])
                        </div>
                    </div>
                    <div class="form-group row" id="nacionalidad_div_">
                        <label for="pais_id_" class="col-sm-3 col-form-label">Nacionalidad</label>
                        <div class="col-sm-9">
                            <select  class="form-select" name="pais_id_" id="pais_id_"
                                value="{{old('pais_id_')}}">
                                @foreach($paises as $pais)
                                {{--  <option value="" {{ old('pais_id_') == null ? ' selected' : '' }} disabled hidden>Selecciona nacionalidad</option>  --}}
                                <option value={{$pais->id}}{{ old('pais_id_') == $pais->id ? ' selected' : '' }}>{{$pais->nombre}}</option>
                                @endforeach
                            </select>
                            @include('admin.partials.mensages_error', ['nombre' => 'pais_id_'])
                        </div>
                    </div>
 

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    @can('editar detenidos en decomisos')
                    <button type="submit" class="btn btn-primary" id="detenido-b">Editar</button>
                    @endcan
                    </form>
                    @can('borrar detenidos en decomisos')
                    <form method="POST" style="display: inline;" id="form_delete_detenido">
                        {{csrf_field()}} {{method_field('DELETE')}}
                        <button type="submit" class="btn  btn-danger" id="detenido-borrar" onclick="return confirm('¿Borrar?');">Borrar</button>
                    </form>
                    @endcan
                    @can('ver detenidos en decomisos deshabilitados')
                    <form method="GET" style="display: inline;" id="form_restore_detenido">
                        {{csrf_field()}}
                        <button type="submit" id="restaurar_det" class="btn btn-success">Restaurar</button>
                    </form>
                    @endcan
                </div>
            
        </div>
    </div>
</div>
@push('scripts')
<script>
    $('#genero_').select2({
        dropdownParent: $("#genero_div_"),
        theme: 'bootstrap4',
    });
    $('#identificacion_id_').select2({
        dropdownParent: $("#tip_id_div_"),
        theme: 'bootstrap4',
    });
    $('#estructura_id_').select2({
        dropdownParent: $("#estructura_div_"),
        theme: 'bootstrap4',
    });
    $('#ocupacion_id_').select2({
        dropdownParent: $("#ocupacion_div_"),
        theme: 'bootstrap4',
    });
    $('#estado_civil_id_').select2({
        dropdownParent: $("#civil_div_"),
        theme: 'bootstrap4',
    });
    $('#pais_id_').select2({
        dropdownParent: $("#nacionalidad_div_"),
        theme: 'bootstrap4',
    });
</script>
@endpush