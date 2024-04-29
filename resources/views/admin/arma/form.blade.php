            {{csrf_field()}}
            <div class="card-body">
                <div class="form-group row">
                    <label for="inputDescripcion" class="col-sm-2 col-form-label">Descripción</label>
                    <div class="col-sm-10">
                        @if ($arma->exists)
                            <input type="text" required name="descripcion" class="form-control" id="inputDescripcion"
                            value="{{old('name',$arma->descripcion)}}" placeholder="Descripción">
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
