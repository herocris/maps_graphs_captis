{{csrf_field()}}
<div class="card-body">
    @if ($institucion->exists)
        <div class="form-group row">
            <label for="inputNombre" class="col-sm-2 col-form-label">Nombre</label>
            <div class="col-sm-10">
                <input type="text" required name="nombre" class="form-control" id="inputNombre"
                    value="{{old('nombre',$institucion->nombre)}}" placeholder="Nombre">
            </div>
        </div>
        @include('admin.partials.mensages_error', ['nombre' => 'nombre'])
        <div class="form-group row">
            <label for="inputContacto" class="col-sm-2 col-form-label">Contacto</label>
            <div class="col-sm-10">
                <input type="text" required name="contacto" class="form-control" id="inputContacto"
                    value="{{old('contacto',$institucion->contacto)}}" placeholder="Contacto">
            </div>
        </div>
        @include('admin.partials.mensages_error', ['nombre' => 'contacto'])
        <div class="form-group row">
            <label for="inputTelefono" class="col-sm-2 col-form-label">Teléfono</label>
            <div class="col-sm-10">
                <input type="text" required name="telefono" class="form-control" id="inputTelefono"
                    value="{{old('telefono',$institucion->telefono)}}" placeholder="Telefono" data-parsley-trigger="keyup" autocomplete="off" data-parsley-pattern-message="El formato del telefono es incorrecto" data-parsley-pattern="^\d{4}[-]\d{4}$">
            </div>
        </div>
        @include('admin.partials.mensages_error', ['nombre' => 'telefono'])
    @else
        <div class="form-group row">
            <label for="inputNombre" class="col-sm-2 col-form-label">Nombre</label>
            <div class="col-sm-10">
                <input type="text" required name="nombre" class="form-control" id="inputNombre"
                    value="{{old('nombre')}}" placeholder="Nombre">
            </div>
        </div>
        @include('admin.partials.mensages_error', ['nombre' => 'nombre'])
        <div class="form-group row">
            <label for="inputContacto" class="col-sm-2 col-form-label">Contacto</label>
            <div class="col-sm-10">
                <input type="text" required name="contacto" class="form-control" id="inputContacto"
                    value="{{old('contacto')}}" placeholder="Contacto">
            </div>
        </div>
        @include('admin.partials.mensages_error', ['nombre' => 'contacto'])
        <div class="form-group row">
            <label for="inputTelefono" class="col-sm-2 col-form-label">Teléfono</label>
            <div class="col-sm-10">
                <input type="text" required name="telefono" class="form-control" id="inputTelefono"
                    value="{{old('telefono')}}" placeholder="Telefono" data-parsley-trigger="keyup" data-parsley-pattern-message="El formato del telefono es incorrecto" data-parsley-pattern="^\d{4}[-]\d{4}$">
            </div>
        </div>
        @include('admin.partials.mensages_error', ['nombre' => 'telefono'])
    @endif
</div>
<div class="card-footer">
    <button type="submit" class="btn btn-primary">Guardar</button>
</div>