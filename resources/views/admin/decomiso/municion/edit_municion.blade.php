<div class="modal fade" id="editMunicionModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar munición</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form-horizontal" data-parsley-validate method="POST" id="form_edit_municion">

                <div class="modal-body">
                    <input id="municionOcultoId_" name="municionOcultoId_" type="hidden" value="">
                    <input name="_token" value="{{ csrf_token() }}" type="hidden">
                    <input name="_method" value="PUT" type="hidden">
                    <input id="municion_decomiso_id_" name="municion_decomiso_id_" type="hidden" value={{$decomiso->id}}>

                    <div class="form-group row">
                        <label for="municion_id_" class="col-sm-3 col-form-label">Descripción</label>
                        <div class="col-sm-9">
                            <select  class="form-select" name="municion_id_" id="municion_id_"
                                value="{{old('municion_id_')}}">
                                @foreach($municiones as $municion)
                                
                                {{--  @if ($municion->deleted_at==null)
                                <option value={{$municion->id}} >{{$municion->descripcion}}</option>
                                
                                @else
                                <option value="" selected disabled hidden >Selecciona el municion</option>
                                <option value={{$municion->id}} disabled>{{$municion->descripcion}}</option>
                                @endif  --}}
                                
                                {{--  <option value="" selected disabled hidden>Selecciona el municion</option>  --}}
                                <option value={{$municion->id}}>{{$municion->descripcion}}</option>
                                @endforeach
                            </select>
                            @include('admin.partials.mensages_error', ['nombre' => 'municion_id_'])
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label for="cantidadMunicion_" class="col-sm-3 col-form-label">Cantidad</label>
                        <div class="col-sm-9">
                            <input type="text" name="cantidadMunicion_" class="form-control" id="cantidadMunicion_"
                                placeholder="cantidad" value="{{old('cantidadMunicion_')}}" required min="0" max="90000" step="100" 
                            data-parsley-validation-threshold="1" data-parsley-trigger="keyup" 
                            data-parsley-type="integer" autocomplete="off">
                            @include('admin.partials.mensages_error', ['nombre' => 'cantidadMunicion_'])
                        </div>
                    </div>
 

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    @can('editar decomisos de municiones')
                    <button type="submit" class="btn btn-primary" id="municion-b">Editar</button>
                    @endcan
                    </form>
                    @can('borrar decomisos de municiones')
                    <form method="POST" style="display: inline;" id="form_delete_municion">
                            {{csrf_field()}} {{method_field('DELETE')}}
                            <button type="submit" class="btn  btn-danger" id="municion-borrar" onclick="return confirm('¿Borrar?');">Borrar</button>
                            
                    </form>
                    @endcan
                    @can('ver decomisos de municiones deshabilitados')
                    <form method="GET" style="display: inline;" id="form_restore_municion">
                        {{csrf_field()}}
                        <button type="submit" id="restaurar_mun" class="btn btn-success">Restaurar</button>
                    </form>
                    @endcan
                </div>
            
        </div>
    </div>
</div>
@push('scripts')
<script>
    $('#municion_id_').select2({
        dropdownParent: $("#editMunicionModal"),
        theme: 'bootstrap4',
    });
</script>
@endpush