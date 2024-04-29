<div class="modal fade" id="editArmaModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edita arma</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form-horizontal" data-parsley-validate method="POST" id="form_edit_arma">

                <div class="modal-body">
                    <input id="armaOcultoId_" name="armaOcultoId_" type="hidden" value="">
                    <input name="_token" value="{{ csrf_token() }}" type="hidden">
                    <input name="_method" value="PUT" type="hidden">
                    <input id="arma_decomiso_id_" name="arma_decomiso_id_" type="hidden" value={{$decomiso->id}}>

                    <div class="form-group row">
                        <label for="arma_id_" class="col-sm-3 col-form-label">Descripción</label>
                        <div class="col-sm-9">
                            <select class="form-select" name="arma_id_" id="arma_id_"
                                value="{{old('arma_id_')}}">
                                @foreach($armas as $arma)
                                {{--  <option value="" selected disabled hidden>Selecciona el arma</option>  --}}
                                <option value={{$arma->id}}>{{$arma->descripcion}}</option>
                                @endforeach
                            </select>
                            @include('admin.partials.mensages_error', ['nombre' => 'arma_id_'])
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label for="cantidadArma_" class="col-sm-3 col-form-label">Cantidad</label>
                        <div class="col-sm-9">
                            <input type="text" name="cantidadArma_" class="form-control" id="cantidadArma_"
                                placeholder="cantidad" value="{{old('cantidadArma_')}}" required min="0" max="90000" step="100" 
                            data-parsley-validation-threshold="1" data-parsley-trigger="keyup" 
                            data-parsley-type="integer" autocomplete="off">
                            @include('admin.partials.mensages_error', ['nombre' => 'cantidadArma_'])
                        </div>
                    </div>
 

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    @can('editar decomisos de armas')
                    <button type="submit" class="btn btn-primary" id="arma-b">Editar</button>
                    @endcan
                     </form>
                    @can('borrar decomisos de armas')
                    <form method="POST" style="display: inline;" id="form_delete_arma">
                            {{csrf_field()}} {{method_field('DELETE')}}
                            <button type="submit" id="arma-borrar" class="btn  btn-danger" onclick="return confirm('¿Borrar?');">Borrar</button>
                            
                    </form>
                    @endcan
                    @can('ver decomisos de armas deshabilitados')
                    <form method="GET" style="display: inline;" id="form_restore_arma">
                        {{csrf_field()}}
                        <button type="submit" id="restaurar_arm" class="btn btn-success">Restaurar</button>
                    </form>
                    @endcan
                </div>
            
        </div>
    </div>
</div>
@push('scripts')
<script>
    $('#arma_id_').select2({
        dropdownParent: $("#editArmaModal"),
        theme: 'bootstrap4',
    });
</script>
@endpush