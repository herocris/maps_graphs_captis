@extends('admin.layout')
@section('header')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-6">
            <h1 class="m-0">Crear usuarios</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('user.index')}}">Usuarios</a></li>
                <li class="breadcrumb-item active">Crear usuarios</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
@stop
@section('content')

<div class="container" style="width: 60%;">


    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Nuevo usuario</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form class="form-horizontal" data-parsley-validate action="{{route('user.store')}}" method="POST" id="form_usuario">
            {{csrf_field()}}
            <div class="card-body">
                <div class="form-group row">
                    <label for="inputNombre" class="col-sm-2 col-form-label">Nombre</label>
                    <div class="col-sm-10">
                        <input type="text" name="name" class="form-control" id="inputNombre" placeholder="Nombre"
                            value="{{old('name')}}" autocomplete="off" required data-parsley-trigger="keyup">
                        @include('admin.partials.mensages_error', ['nombre' => 'name'])
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">Correo</label>
                    <div class="col-sm-10">
                        <input type="email" name="email" class="form-control" id="inputEmail3" placeholder="Correo"
                            value="{{old('email')}}" required autocomplete="off" data-parsley-type="email" data-parsley-trigger="keyup">
                        @include('admin.partials.mensages_error', ['nombre' => 'email'])
                    </div>
                </div>
                <div class="form-group row">
                    <label for="institucion_id" class="col-sm-2 col-form-label">Institución</label>
                    <div class="col-sm-10">
                    <select class="form-select" required data-placeholder="Selecciona la institución" name="institucion_id" id="institucion_id" >
                        @foreach($instituciones as $institucion)
                            <option value="" {{ old('institucion_id') == null ? 'selected' : '' }} disabled hidden>Selecciona la institución</option>
                            <option value={{$institucion->id}} {{ old('institucion_id') == $institucion->id ? 'selected' : '' }}>{{$institucion->nombre}}</option>   
                        @endforeach             
                    </select>
                        @include('admin.partials.mensages_error', ['nombre' => 'institucion_id'])
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-2 col-form-label">Contraseña</label>
                    <div class="col-sm-10">
                        <input type="password" name="password" class="form-control" id="inputContraseña"
                            placeholder="Contraseña" value="{{old('password')}}" autocomplete="off" required data-parsley-minlength="8" data-parsley-caractero="" data-parsley-numero="" data-parsley-mayuscula="" data-parsley-minuscula="" data-parsley-trigger="keyup">
                        @include('admin.partials.mensages_error', ['nombre' => 'password'])
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-2 col-form-label">Confirmar Contraseña</label>
                    <div class="col-sm-10">
                        <input type="password" name="password_confirmation" class="form-control"
                            id="inputConfirmar_Contraseña" placeholder="Confirmar Contraseña"
                            value="{{old('password_confirmation')}}" required  autocomplete="off" data-parsley-trigger="keyup" data-parsley-equalto="#inputContraseña">
                        @include('admin.partials.mensages_error', ['nombre' => 'password_confirmation'])
                    </div>
                </div>
                <div class="form-group row">
                    <div class="form-group col-md-6">
                        <label>Roles</label>
                        @include('admin.partials.roles')
                    </div>
                    <div class="form-group col-md-6">
                        <label>Permisos</label><br>
                        @include('admin.partials.permisos',['model' => $user])
                    </div>
                </div>

            </div>


            <!-- /.card-body -->
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
            <!-- /.card-footer -->
        </form>
    </div>


</div>
</div>


</div>

@stop

@push('scripts')
<script>
//alert($("#inputNombre").val());
$('#roles').select2({
    theme: 'bootstrap4',
});
$('#institucion_id').select2({
    theme: 'bootstrap4',
});

var mayuscula= $("<input data-parsley-pattern='^(?=.{1,}$)(?=.*[A-Z]).*$'>").parsley();
    window.Parsley.addValidator('mayuscula',{
        validateString: function(data){
            return mayuscula.isValid(true,data);
        },
        messages: {
            es: 'Al menos una mayuscula',
        },
    });


var minuscula= $("<input data-parsley-pattern='^(?=.{1,}$)(?=.*[a-z]).*$'>").parsley();
    window.Parsley.addValidator('minuscula',{
        validateString: function(data){
            return minuscula.isValid(true,data);
        },
        messages: {
            es: 'Al menos una minuscula',
        },
    });

var numero= $("<input data-parsley-pattern='^(?=.{1,}$)(?=.*[0-9]).*$'>").parsley();
    window.Parsley.addValidator('numero',{
        validateString: function(data){
            return numero.isValid(true,data);
        },
        messages: {
            es: 'Al menos un numero',
        },
    });

var caractero= $("<input data-parsley-pattern='^(?=.{1,}$)(?=.*[@$!%*#?&_+-]).*$'>").parsley();
    window.Parsley.addValidator('caractero',{
        validateString: function(data){
            return caractero.isValid(true,data);
        },
        messages: {
            es: 'Al menos un caracter especial',
        },
    });    
</script>
@endpush

    
