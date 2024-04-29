<div class="modal fade" id="createPrecursorModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agregar precursor</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form-horizontal" data-parsley-validate method="POST" action="{{route('decomisoprecursor.store')}}" id="form_create_precursor">

                <div class="modal-body">
                    <input id="precursorOcultoId" name="precursorOcultoId" type="hidden" value="">
                    <input name="_token" value="{{ csrf_token() }}" type="hidden">
                    <input id="precursor_decomiso_id" name="precursor_decomiso_id" type="hidden" value={{$decomiso->id}}>

                    <div class="form-group row">
                        <label for="precursor_id" class="col-sm-3 col-form-label">Descripción</label>
                        <div class="col-sm-9">
                            <select required class="form-select" data-placeholder="Selecciona precursor" name="precursor_id" id="precursor_id"
                                value="{{old('precursor_id')}}">
                                @foreach($precursores as $precursor)
                                <option value="" selected disabled hidden>Selecciona el precursor</option>
                                <option value={{$precursor->id}}>{{$precursor->descripcion}}</option>
                                @endforeach
                            </select>
                            @include('admin.partials.mensages_error', ['nombre' => 'precursor_id'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="presentacion_precursor_id" class="col-sm-3 col-form-label">Presentación</label>
                        <div class="col-sm-9">
                            <select required class="form-select" data-placeholder="Selecciona presentación" name="presentacion_precursor_id" id="presentacion_precursor_id"
                                value="{{old('presentacion_precursor_id')}}">
                                @foreach($presentacion_precursores as $presentacion_precursor)
                                <option value="" selected disabled hidden>Selecciona la presentación</option>
                                <option value={{$presentacion_precursor->id}}>{{$presentacion_precursor->descripcion}}</option>
                                @endforeach
                            </select>
                            @include('admin.partials.mensages_error', ['nombre' => 'presentacion_precursor_id'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="cantidadPrecursor" class="col-sm-3 col-form-label">Cantidad</label>
                        <div class="col-sm-9">
                            <input type="text" name="cantidadPrecursor" class="form-control" id="cantidadPrecursor"
                                placeholder="cantidad" value="{{old('cantidadPrecursor')}}" required min="0" max="900000" step="100" 
                            data-parsley-validation-threshold="1" data-parsley-trigger="keyup" 
                            data-parsley-type="integer" autocomplete="off">
                            @include('admin.partials.mensages_error', ['nombre' => 'cantidadPrecursor'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="volumen" class="col-sm-3 col-form-label">Volumen (L)</label>
                        <div class="col-sm-9">
                            <input type="text" name="volumen" class="form-control" id="volumen" 
                            placeholder="Volumen" value="{{old('volumen')}}" required min="0" max="90000" step="0.01" 
                            data-parsley-validation-threshold="1" data-parsley-trigger="keyup" 
                            data-parsley-type="number">
                            @include('admin.partials.mensages_error', ['nombre' => 'volumen'])
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
    function limpiar_precursor() {
        //limpiar_errores();
        $('#precursor_id, #presentacion_precursor_id').val(null).trigger('change');
        //$('#precursor_id').val("");
        //$('#presentacion_precursor_id').val("");
        //$('#cantidadPrecursor').val("");
        //$('#volumen').val("");
        $('#form_create_precursor').parsley().reset();
        
        document.getElementById("form_create_precursor").reset();
    }
    
    $('#precursor_id, #presentacion_precursor_id').select2({
        dropdownParent: $("#createPrecursorModal"),
        theme: 'bootstrap4',
    });
</script>
@endpush