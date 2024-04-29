<div class="modal fade" id="editPrecursorModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar precursor</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form-horizontal" data-parsley-validate method="POST" id="form_edit_precursor">

                <div class="modal-body">
                    <input id="decomisoprecursorId" name="decomisoprecursorId" type="hidden" value="">
                    <input name="_token" value="{{ csrf_token() }}" type="hidden">
                    <input name="_method" value="PUT" type="hidden">
                    <input id="precursor_decomiso_id_" name="precursor_decomiso_id_" type="hidden" value={{$decomiso->id}}>

                    <div class="form-group row">
                        <label for="precursor_id_" class="col-sm-3 col-form-label">Descripción</label>
                        <div class="col-sm-9">
                            <select class="form-select" name="precursor_id_" id="precursor_id_"
                                value="{{old('precursor_id_')}}">
                                @foreach($precursores as $precursor)
                                {{--  <option value="" selected disabled hidden>Selecciona el precursor</option>  --}}
                                <option value={{$precursor->id}}>{{$precursor->descripcion}}</option>
                                @endforeach
                            </select>
                            @include('admin.partials.mensages_error', ['nombre' => 'precursor_id_'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="presentacion_precursor_id_" class="col-sm-3 col-form-label">Presentación</label>
                        <div class="col-sm-9">
                            <select class="form-select" name="presentacion_precursor_id_" id="presentacion_precursor_id_"
                                value="{{old('presentacion_precursor_id_')}}">
                                @foreach($presentacion_precursores as $presentacion_precursor)
                                {{--  <option value="" selected disabled hidden>Selecciona la presentación</option>  --}}
                                <option value={{$presentacion_precursor->id}}>{{$presentacion_precursor->descripcion}}</option>
                                @endforeach
                            </select>
                            @include('admin.partials.mensages_error', ['nombre' => 'presentacion_precursor_id_'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="cantidadPrecursor_" class="col-sm-3 col-form-label">Cantidad</label>
                        <div class="col-sm-9">
                            <input type="text" name="cantidadPrecursor_" class="form-control" id="cantidadPrecursor_"
                                placeholder="cantidad" value="{{old('cantidadPrecursor_')}}"  required min="0" max="900000" step="100" 
                            data-parsley-validation-threshold="1" data-parsley-trigger="keyup" 
                            data-parsley-type="integer" autocomplete="off">
                            @include('admin.partials.mensages_error', ['nombre' => 'cantidadPrecursor_'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="volumen_" class="col-sm-3 col-form-label">Volumen (L)</label>
                        <div class="col-sm-9">
                            <input type="text" name="volumen_" class="form-control" id="volumen_" 
                            placeholder="Volumen" value="{{old('volumen_')}}" required min="0" max="90000" step="0.01" 
                            data-parsley-validation-threshold="1" data-parsley-trigger="keyup" 
                            data-parsley-type="number">
                           @include('admin.partials.mensages_error', ['nombre' => 'volumen_'])
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    @can('editar decomisos de precursores')
                    <button type="submit" class="btn btn-primary" id="precursor-b">Editar</button>
                    @endcan
                    </form>
                    @can('borrar decomisos de precursores')
                    <form method="POST" style="display: inline;" id="form_delete_precursor">
                            {{csrf_field()}} {{method_field('DELETE')}}
                            <button type="submit" class="btn  btn-danger" id="precursor-borrar" onclick="return confirm('¿Borrar?');">Borrar</button>
                            
                    </form>
                    @endcan
                    @can('ver decomisos de drogas deshabilitados')
                    <form method="GET" style="display: inline;" id="form_restore_precursor">
                        {{csrf_field()}}
                        <button type="submit" id="restaurar_prec" class="btn btn-success">Restaurar</button>
                    </form>
                    @endcan
                </div>
            
        </div>
    </div>
</div>
@push('scripts')
<script>
    $('#precursor_id_, #presentacion_precursor_id_').select2({
        dropdownParent: $("#editPrecursorModal"),
        theme: 'bootstrap4',
    });
</script>
@endpush