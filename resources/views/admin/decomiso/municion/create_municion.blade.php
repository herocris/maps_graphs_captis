<div class="modal fade" id="createMunicionModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agregar munición</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form-horizontal" data-parsley-validate method="POST" action="{{route('decomisomunicion.store')}}" id="form_create_municion">

                <div class="modal-body">
                    <input id="municionOcultoId" name="municionOcultoId" type="hidden" value="">
                    <input name="_token" value="{{ csrf_token() }}" type="hidden">
                    <input id="municion_decomiso_id" name="municion_decomiso_id" type="hidden" value={{$decomiso->id}}>

                    <div class="form-group row">
                        <label for="municion_id" class="col-sm-3 col-form-label">Descripción</label>
                        <div class="col-sm-9">
                            <select required class="form-select" data-placeholder="Selecciona munición" name="municion_id" id="municion_id"
                                value="{{old('municion_id')}}">
                                @foreach($municiones as $municion)
                                <option value="" selected disabled hidden>Selecciona el municion</option>
                                <option value={{$municion->id}}>{{$municion->descripcion}}</option>
                                @endforeach
                            </select>
                            @include('admin.partials.mensages_error', ['nombre' => 'municion_id'])
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label for="cantidadMunicion" class="col-sm-3 col-form-label">Cantidad</label>
                        <div class="col-sm-9">
                            <input type="text" name="cantidadMunicion" class="form-control" id="cantidadMunicion"
                                placeholder="cantidad" value="{{old('cantidadMunicion')}}" required min="0" max="90000" step="100" 
                            data-parsley-validation-threshold="1" data-parsley-trigger="keyup" 
                            data-parsley-type="integer" autocomplete="off">
                            @include('admin.partials.mensages_error', ['nombre' => 'cantidadMunicion'])
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
    function limpiar_municion() {
        limpiar_errores();
        //$('#municion_id').val("");
        //$('#cantidadMunicion').val("");
        
        $('#form_create_municion').parsley().reset();
        document.getElementById("form_create_municion").reset();
    }
    $('#municion_id').select2({
        dropdownParent: $("#createMunicionModal"),
        theme: 'bootstrap4',
    });
</script>
@endpush