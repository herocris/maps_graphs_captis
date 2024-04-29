<div class="modal fade" id="createDetenidoModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agregar detenido</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form-horizontal" data-parsley-validate method="POST" action="{{route('decomisodetenido.store')}}" id="form_create_detenido">

                <div class="modal-body">
                    
                    <input name="_token" value="{{ csrf_token() }}" type="hidden">
                    <input id="detenido_decomiso_id" name="detenido_decomiso_id" type="hidden" value={{$decomiso->id}}>

                    <div class="form-group row">
                        <label for="nombreDetenido" class="col-sm-3 col-form-label">Nombre</label>
                        <div class="col-sm-9">
                            <input type="text" required name="nombreDetenido" class="form-control" id="nombreDetenido" value="{{old('nombreDetenido')}}"
                                placeholder="Nombre">
                            @include('admin.partials.mensages_error', ['nombre' => 'nombreDetenido'])
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label for="alias" class="col-sm-3 col-form-label">Alias</label>
                        <div class="col-sm-9">
                            <input type="text" required name="alias" class="form-control" id="alias" value="{{old('alias')}}"
                                placeholder="alias">
                            @include('admin.partials.mensages_error', ['nombre' => 'alias'])
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="identidad" class="col-sm-3 col-form-label">Identidad</label>
                        <div class="col-sm-9">
                            <input type="text" name="identidad" class="form-control" id="identidad"
                                value="{{old('identidad')}}" placeholder="identidad">
                            @include('admin.partials.mensages_error', ['nombre' => 'identidad'])
                        </div>
                    </div>
                    <div class="form-group row" id="genero_div">
                        <label for="genero" class="col-sm-3 col-form-label">Género</label>
                        <div class="col-sm-9">
                            <select required  class="form-select" data-placeholder="Selecciona genero" name="genero" id="genero">
                                <option value="" {{ old('genero') == null ? ' selected' : '' }} disabled hidden>Selecciona el genero</option>
                                <option value="M" {{ old('genero') == "M" ? ' selected' : '' }}>Masculino</option>
                                <option value="F" {{ old('genero') == "F" ? ' selected' : '' }}>Femenino</option>
                            </select>
                            @include('admin.partials.mensages_error', ['nombre' => 'genero'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="edad" class="col-sm-3 col-form-label">Edad</label>
                        <div class="col-sm-9">
                            <input type="text" name="edad" class="form-control" id="edad" value="{{old('edad')}}"
                                placeholder="edad" min="0" max="99" step="100" 
                            data-parsley-validation-threshold="1" data-parsley-trigger="keyup" 
                            data-parsley-type="integer" autocomplete="off">
                            @include('admin.partials.mensages_error', ['nombre' => 'edad'])
                        </div>
                    </div>
                    
                    <div class="form-group row" id="tip_id_div">
                        <label for="identificacion_id" class="col-sm-3 col-form-label">Tipo de identidad</label>
                        <div class="col-sm-9">
                            <select  class="form-select" data-placeholder="Selecciona identificación" name="identificacion_id" id="identificacion_id">
                                @foreach($indentidades as $indentidad)
                                
                                <option value={{$indentidad->id}}{{$indentidad->id == 11 ? ' selected' : '' }}>{{$indentidad->descripcion}}</option>
                                @endforeach
                            </select>
                            @include('admin.partials.mensages_error', ['nombre' => 'identificacion_id'])
                        </div>
                    </div>
                    <div class="form-group row" id="estructura_div">
                        <label for="estructura_id" class="col-sm-3 col-form-label">Estructura</label>
                        <div class="col-sm-9">
                            <select  class="form-select" data-placeholder="Selecciona estructura" name="estructura_id" id="estructura_id">
                                @foreach($estructuras as $estructura)
                                
                                <option value={{$estructura->id}}{{ $estructura->id == 8 ? ' selected' : '' }}>{{$estructura->descripcion}}</option>
                                @endforeach
                            </select>
                            @include('admin.partials.mensages_error', ['nombre' => 'estructura_id'])
                        </div>
                    </div>
                    <div class="form-group row" id="ocupacion_div">
                        <label for="ocupacion_id" class="col-sm-3 col-form-label">Ocupación</label>
                        <div class="col-sm-9">
                            <select  class="form-select" data-placeholder="Selecciona ocupación" name="ocupacion_id" id="ocupacion_id" value="{{old('ocupacion_id')}}">
                                @foreach($ocupaciones as $ocupacion)
                                
                                <option value={{$ocupacion->id}}{{ $ocupacion->id == 11 ? ' selected' : '' }}>{{$ocupacion->descripcion}}</option>
                                @endforeach
                            </select>
                            @include('admin.partials.mensages_error', ['nombre' => 'ocupacion_id'])
                        </div>
                    </div>
                    <div class="form-group row" id="civil_div">
                        <label for="estado_civil_id" class="col-sm-3 col-form-label">Estado civil</label>
                        <div class="col-sm-9">
                            <select  class="form-select" data-placeholder="Selecciona estado civíl" name="estado_civil_id" id="estado_civil_id">
                                @foreach($estados as $estado)
                                
                                <option value={{$estado->id}}{{ $estado->id == 11 ? ' selected' : '' }}>{{$estado->descripcion}}</option>
                                @endforeach
                            </select>
                            @include('admin.partials.mensages_error', ['nombre' => 'estado_civil_id'])
                        </div>
                    </div>
                    <div class="form-group row" id="nacionalidad_div">
                        <label for="pais_id" class="col-sm-3 col-form-label">Nacionalidad</label>
                        <div class="col-sm-9">
                            <select  class="form-select" data-placeholder="Selecciona país" name="pais_id" id="pais_id"
                                value="{{old('pais_id')}}">
                                @foreach($paises as $pais)
                                
                                <option value={{$pais->id}}{{ $pais->id == 238 ? ' selected' : '' }}>{{$pais->nombre}}</option>
                                @endforeach
                            </select>
                            @include('admin.partials.mensages_error', ['nombre' => 'pais_id'])
                        </div>
                    </div>
 

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary" id="droga-b">Agregar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@push('scripts')
<script>
    function limpiar_detenido() {
        //alert("lls");
        //limpiar_errores();
        $('#form_create_detenido').parsley().reset();
        
        document.getElementById("form_create_detenido").reset();
        $('#genero').val(null).change();
        $('#identificacion_id').val(11).change();
        $('#estructura_id').val(8).change();
        $('#ocupacion_id').val(11).change();
        $('#estado_civil_id').val(11).change();
        $('#pais_id').val(238).change();
    }

    $("#nombreDetenido, #alias, #nombreDetenido_, #alias_" ).change(function(e) {
        
        if($("#nombreDetenido").val()!="" || $("#alias").val()!=""){
            $("#alias").removeAttr( "required" );
            $("#nombreDetenido").removeAttr( "required" );
        }else{
            $("#alias").attr( "required",true );
            $("#nombreDetenido").attr( "required",true );
        }

        if($("#nombreDetenido_").val()!="" || $("#alias_").val()!=""){
            $("#alias_").removeAttr( "required" );
            $("#nombreDetenido_").removeAttr( "required" );
        }else{
            $("#alias_").attr( "required",true );
            $("#nombreDetenido_").attr( "required",true );
        }
        
    });
    
    $('#genero').select2({
        dropdownParent: $("#genero_div"),
        theme: 'bootstrap4',
    });
    $('#identificacion_id').select2({
        dropdownParent: $("#tip_id_div"),
        theme: 'bootstrap4',
    });
    $('#estructura_id').select2({
        dropdownParent: $("#estructura_div"),
        theme: 'bootstrap4',
    });
    $('#ocupacion_id').select2({
        dropdownParent: $("#ocupacion_div"),
        theme: 'bootstrap4',
    });
    $('#estado_civil_id').select2({
        dropdownParent: $("#civil_div"),
        theme: 'bootstrap4',
    });
    $('#pais_id').select2({
        dropdownParent: $("#nacionalidad_div"),
        theme: 'bootstrap4',
    });
</script>
@endpush