<div class="modal fade" id="createArmaModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agregar arma</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form-horizontal" data-parsley-validate method="POST" action="{{route('decomisoarma.store')}}" id="form_create_arma">

                <div class="modal-body">
                    <input id="armaOcultoId" name="armaOcultoId" type="hidden" value="">
                    <input name="_token" value="{{ csrf_token() }}" type="hidden">
                    <input id="arma_decomiso_id" name="arma_decomiso_id" type="hidden" value={{$decomiso->id}}>

                    <div class="form-group row">
                        <label for="arma_id" class="col-sm-3 col-form-label">Descripción</label>
                        <div class="col-sm-9">
                            <select required class="form-select" data-placeholder="Selecciona arma" name="arma_id" id="arma_id"
                                value="{{old('arma_id')}}">
                                @foreach($armas as $arma)
                                <option value="" selected disabled hidden>Selecciona el arma</option>
                                <option value={{$arma->id}}>{{$arma->descripcion}}</option>
                                @endforeach
                            </select>
                            @include('admin.partials.mensages_error', ['nombre' => 'arma_id'])
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label for="cantidadArma" class="col-sm-3 col-form-label">Cantidad</label>
                        <div class="col-sm-9">
                            <input type="text" name="cantidadArma" class="form-control" id="cantidadArma"
                                placeholder="cantidad" value="{{old('cantidadArma')}}" required min="0" max="90000" step="100" 
                            data-parsley-validation-threshold="1" data-parsley-trigger="keyup" 
                            data-parsley-type="integer" autocomplete="off">
                            @include('admin.partials.mensages_error', ['nombre' => 'cantidadArma'])
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
<style>
    .muis{
        color:red;
    }
</style>
@push('scripts')
<script>
    function limpiar_arma() {
        //limpiar_errores();
        $('#arma_id').val(null).trigger('change');
        //alert("asñdlkfh");
        //$("#form_create_arma")[0].reset();form_edit_arma
        document.getElementById("form_create_arma").reset();
        $('#form_create_arma').parsley().reset();
        

        //$('#arma_id').val("");
        //$('#cantidadArma').val("");

    }
    $('#arma_id').select2({
        dropdownParent: $("#createArmaModal"),
        theme: 'bootstrap4',
    });
</script>
@endpush