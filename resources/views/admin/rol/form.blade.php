            {{csrf_field()}}
            <div class="card-body">
                <div class="form-group row">
                    <label for="inputNombre" class="col-sm-2 col-form-label">Nombre</label>
                    <div class="col-sm-10">
                        @if ($role->exists)
                            <input type="text" required name="name" class="form-control" id="inputNombre"
                            value="{{old('name',$role->name)}}" placeholder="Nombre">
                        @else  
                            <input type="text" required name="name" class="form-control" id="inputNombre"
                            value="{{old('name')}}" placeholder="Nombre">
                        @endif
                        @include('admin.partials.mensages_error', ['nombre' => 'name'])
                    </div>
                </div>
                {{--  <div class="form-group row">
                    <div class="form-group col-md-6">
                        <label>Permisos</label>
                        @include('admin.partials.permisos',['model' => $role])
                    </div>
                </div>  --}}
                <div class="form-group row">
                    <label for="inputNombre" class="col-sm-2 col-form-label">Permisos</label>
                    <div class="col-sm-10">
                        @include('admin.partials.permisos',['model' => $role])
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>


