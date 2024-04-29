<div class="modal fade" id="createDrogaModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar droga</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form-horizontal" data-parsley-validate method="POST" action="{{route('decomisodroga.store')}}" id="form_create_droga">

                <div class="modal-body">
                    <input id="drogaOcultoId" name="drogaOcultoId" type="hidden" value="">
                    <input name="_token" value="{{ csrf_token() }}" type="hidden">
                    <input id="droga_decomiso_id" name="droga_decomiso_id" type="hidden" value={{$decomiso->id}}>

                    <div class="form-group row">
                        <label for="droga_id" class="col-sm-3 col-form-label">Descripción</label>
                        <div class="col-sm-9">
                            <select required class="form-select" data-placeholder="Selecciona droga" name="droga_id" id="droga_id">
                                @foreach($drogas as $droga)
                                <option value="" {{ old('droga_id') == null ? ' selected' : '' }} disabled hidden>Selecciona la droga</option>
                                <option value={{$droga->id}}{{ old('droga_id') == $droga->id ? ' selected' : '' }}>{{$droga->descripcion}}</option>
                                @endforeach
                            </select>
                            @include('admin.partials.mensages_error', ['nombre' => 'droga_id'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="presentacion_droga_id" class="col-sm-3 col-form-label">Presentación</label>
                        <div class="col-sm-9"> 
                        {{--  data-placeholder="Selecciona droga"  --}}
                            <select required class="form-select"  data-placeholder="Selecciona presentación" name="presentacion_droga_id" id="presentacion_droga_id">
                                @foreach($presentacion_drogas as $presentacion_droga)
                                <option value="" {{ old('presentacion_droga_id') == null ? ' selected' : '' }} disabled hidden>Selecciona la presentación</option>
                                <option value={{$presentacion_droga->id}}{{ old('presentacion_droga_id') == $presentacion_droga->id ? ' selected' : '' }}>{{$presentacion_droga->descripcion}}</option>
                                @endforeach
                            </select>
                            @include('admin.partials.mensages_error', ['nombre' => 'presentacion_droga_id'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="drogaCantidad" class="col-sm-3 col-form-label">Cantidad</label>
                        <div class="col-sm-9">
                            <input required min="0" max="900000" step="100" 
                            data-parsley-validation-threshold="1" data-parsley-trigger="keyup" 
                            data-parsley-type="integer" type="text" name="drogaCantidad" class="form-control" id="drogaCantidad"
                                placeholder="cantidad" value="{{old('drogaCantidad')}}" autocomplete="off">
                            @include('admin.partials.mensages_error', ['nombre' => 'drogaCantidad'])
                        </div>
                    </div>
                    <div class="form-group row" id="peso">
                        <label for="drogaPeso" class="col-sm-3 col-form-label">Peso (Kg)</label>
                        <div class="col-sm-9">
                            <input required min="0" max="90000" step="0.01" 
                            data-parsley-validation-threshold="1" data-parsley-trigger="keyup" 
                            data-parsley-type="number" type="text" name="drogaPeso" class="form-control" id="drogaPeso" placeholder="Peso" value="{{old('drogaPeso')}}" autocomplete="off">
                            @include('admin.partials.mensages_error', ['nombre' => 'drogaPeso'])
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
///////////////////////////////funcion para limpiar modal de creación///////////////////////////
function limpiar_droga() {
    $('#droga_id, #presentacion_droga_id').val(null).trigger('change');
    //$('#droga_id').val("");
    //$('#presentacion_droga_id').val("");
    //$('#drogaCantidad').val("");
    //$('#drogaPeso').val("");
    //$('#peso_2').val("");
    //$("#form_create_arma").trigger('reset');
    //$('#form_create_arma')[0].reset();
    //console.log(document.getElementById("form_create_arma").reset());
    $('#form_create_droga').parsley().reset();
    document.getElementById("form_create_droga").reset();
}
///////////////////////////inicilizando combobox de modal de creación/////////////////////////////////
$('#droga_id, #presentacion_droga_id').select2({
    dropdownParent: $("#createDrogaModal"),
    theme: 'bootstrap4',
});
</script>
@endpush