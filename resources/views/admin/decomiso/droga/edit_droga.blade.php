<div class="modal fade" id="editDrogaModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Editar droga</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <form class="form-horizontal" data-parsley-validate method="POST" id="form_edit_droga">        
            <div class="modal-body">
                <input id="drogaOcultoId_" name="drogaOcultoId_" type="hidden" value="">
                <input name="_token" value="{{ csrf_token() }}" type="hidden">
                <input name="_method" value="PUT" type="hidden">
                <input id="droga_decomiso_id_" name="droga_decomiso_id_" type="hidden" value={{$decomiso->id}}>



                <div class="form-group row" id="descripcion">
                    <label for="droga_id_" class="col-sm-3 col-form-label">Descripción</label>
                    <div class="col-sm-9">
                        <select class="form-select" name="droga_id_" id="droga_id_"
                            value="{{old('droga_id_')}}">
                            @foreach($drogas as $droga)
                            {{--  <option value="" selected disabled hidden>Selecciona la droga</option>  --}}
                            <option value={{$droga->id}}>{{$droga->descripcion}}</option>
                            @endforeach
                        </select>
                        @include('admin.partials.mensages_error', ['nombre' => 'droga_id_'])
                    </div>
                </div>
                <div class="form-group row" id="presentacion">
                    <label for="presentacion_droga_id_" class="col-sm-3 col-form-label">Presentación</label>
                    <div class="col-sm-9">
                        <select class="form-select" name="presentacion_droga_id_" id="presentacion_droga_id_"
                            value="{{old('presentacion_droga_id_')}}">
                            @foreach($presentacion_drogas as $presentacion_droga)
                            {{--  <option value="" selected disabled hidden>Selecciona la presentación</option>  --}}
                            <option value={{$presentacion_droga->id}}>{{$presentacion_droga->descripcion}}</option>
                            @endforeach
                        </select>
                        @include('admin.partials.mensages_error', ['nombre' => 'presentacion_droga_id_'])
                    </div>
                </div>
                <div class="form-group row" id="cantidad">
                    <label for="drogaCantidad_" class="col-sm-3 col-form-label">Cantidad</label>
                    <div class="col-sm-9">
                        <input type="text" name="drogaCantidad_" class="form-control" id="drogaCantidad_"
                            placeholder="cantidad" value="{{old('drogaCantidad_')}}" required min="0" max="900000" step="100" 
                        data-parsley-validation-threshold="1" data-parsley-trigger="keyup" 
                        data-parsley-type="integer" autocomplete="off">
                        @include('admin.partials.mensages_error', ['nombre' => 'drogaCantidad_'])
                    </div>
                </div>
                <div class="form-group row" id="peso">
                    <label for="drogaPeso_" class="col-sm-3 col-form-label">Peso (Kg)</label>
                    <div class="col-sm-9">
                        <input type="text" name="drogaPeso_" class="form-control" id="drogaPeso_"
                        placeholder="Peso" value="{{old('drogaPeso_')}}" required min="0" max="90000" step="0.01" 
                        data-parsley-validation-threshold="1" data-parsley-trigger="keyup" 
                        data-parsley-type="number" autocomplete="off">
                        @include('admin.partials.mensages_error', ['nombre' => 'drogaPeso_'])
                    </div>
                </div>
                
            </div>
            <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                @can('editar decomisos de droga')
                    <button type="submit" class="btn btn-primary" id="droga_b">Editar</button>
                @endcan
        </form>
                @can('borrar decomisos de droga')
                <form method="POST" style="display: inline;" id="form_delete_droga">
                        {{csrf_field()}} {{method_field('DELETE')}}
                        <button type="submit" id="droga-borrar" class="btn  btn-danger" onclick="return confirm('¿Borrar?');">Borrar</button>
                        
                </form>
                @endcan
                @can('ver decomisos de drogas deshabilitados')
                <form method="GET" style="display: inline;" id="form_restore_droga">
                    {{csrf_field()}}
                    <button type="submit" id="restaurar_drog" class="btn btn-success">Restaurar</button>
                </form>
                @endcan
            </div>
            
            </div>
    </div>
</div>
@push('scripts')
<script>
///////////////////////////inicilizando combobox de modal de creación/////////////////////////////////
$('#droga_id_, #presentacion_droga_id_').select2({
    dropdownParent: $("#editDrogaModal"),
    theme: 'bootstrap4',
});
</script>
@endpush