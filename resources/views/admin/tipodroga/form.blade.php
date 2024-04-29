            {{csrf_field()}}
            <div class="card-body">
                <div class="form-group row">
                    <label for="inputDescripcion" class="col-sm-2 col-form-label">Descripción</label>
                    <div class="col-sm-10">
                        @if ($tipodroga->exists)
                            <input type="text" required name="descripcion" class="form-control" id="inputNombre"
                            value="{{old('name',$tipodroga->descripcion)}}" placeholder="Descripción">
                            {{--  data-parsley-pattern="^[a-zA-Z\u00C0-\u017F\s]+$" data-parsley-pattern-message='Solo se admiten letras' data-parsley-trigger="keyup"  --}}
                        @else  
                            <input type="text" required name="descripcion" class="form-control" id="inputDescripcion"
                            value="{{old('descripcion')}}" placeholder="Descripción">
                        @endif
                        @include('admin.partials.mensages_error', ['nombre' => 'descripcion'])
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
