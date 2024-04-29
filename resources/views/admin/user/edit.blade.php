@extends('admin.layout')
@section('header')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-6">
            <h1 class="m-0">Editar usuarios</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('user.index')}}">Usuarios</a></li>
                <li class="breadcrumb-item active">Editar usuario</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
@stop
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Editar usuario</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form class="form-horizontal" data-parsley-validate action="{{route('user.update',$user)}}" method="POST" id="form_usuario">
                    {{csrf_field()}}
                    {{method_field('PUT')}}
                    <div class="card-body">
                        <div class="form-group row">
                            <label for="inputNombre" class="col-sm-2 col-form-label">Nombre</label>
                            <div class="col-sm-10">
                                <input type="text" name="name" class="form-control" id="inputNombre"
                                    value="{{old('name',$user->name)}}" required placeholder="Nombre">
                                @include('admin.partials.mensages_error', ['nombre' => 'name'])
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-2 col-form-label">Correo</label>
                            <div class="col-sm-10">
                                <input type="email" name="email" class="form-control"
                                    value="{{old('email',$user->email)}}" id="inputEmail3" required placeholder="Correo" autocomplete="off" data-parsley-type="email" data-parsley-trigger="keyup">
                                @include('admin.partials.mensages_error', ['nombre' => 'email'])
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="institucion_id" class="col-sm-2 col-form-label">Institución</label>
                            <div class="col-sm-10">
                            <select class="form-select" required data-placeholder="Selecciona la institución" name="institucion_id" id="institucion_id" >
                                @foreach($instituciones as $institucion)
                                    {{--  <option value="" {{ old('institucion_id') == null ? 'selected' : '' }} disabled hidden>Selecciona la institución</option>  --}}
                                    {{--  <option value={{$institucion->id}}>{{$institucion->nombre}}</option>     --}}
                                    <option {{$institucion->id==$user->institucion_id?'selected':''}} value={{$institucion->id}}>{{$institucion->nombre}}</option>
                                @endforeach             
                            </select>
                                @include('admin.partials.mensages_error', ['nombre' => 'institucion_id'])
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputPassword3" class="col-sm-3 col-form-label">Nueva contraseña</label>
                            <div class="col-sm-9">
                                <input type="password" name="password" class="form-control" id="inputContraseña"
                                    placeholder="Contraseña" autocomplete="off"  data-parsley-minlength="8" data-parsley-caractero="" data-parsley-numero="" data-parsley-mayuscula="" data-parsley-minuscula="" data-parsley-trigger="keyup">
                                @include('admin.partials.mensages_error', ['nombre' => 'password'])
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputPassword3" class="col-sm-3 col-form-label">Confirmar nueva
                                contraseña</label>
                            <div class="col-sm-9">
                                <input type="password" name="password_confirmation" class="form-control"
                                    id="inputConfirmar_Contraseña" placeholder="Confirmar Contraseña"  autocomplete="off"  data-parsley-trigger="keyup" data-parsley-equalto="#inputContraseña">
                                @include('admin.partials.mensages_error', ['nombre' => 'password_confirmation'])
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                    <!-- /.card-footer -->
                </form>
                <div class="card card-primary">
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label for="inputPassword3" class="col-sm-8 col-form-label">Autentificación de dos
                                    factores</label>
                            </div>
                            <div class="col-sm-8">

                                @if (! $user->two_factor_secret)
                                <form method="POST" action="{{ url('user/two-factor-authentication') }}">
                                    @csrf
                                    <input name="codi_usu" type="hidden" value={{$user->id}}>
                                    <input type="checkbox" onChange="this.form.submit()" data-on="Activado"
                                        data-off="Desactivado" data-toggle="toggle" data-onstyle="success"
                                        data-offstyle="dark">
                                </form>
                                @else
                                <form method="POST" action="{{ url('user/two-factor-authentication') }}">
                                    @csrf
                                    <input name="codi_usu" type="hidden" value={{$user->id}}>
                                    @method('DELETE')
                                    <input type="checkbox" onChange="this.form.submit()" checked data-on="Activado"
                                        data-off="Desactivado" data-toggle="toggle" data-onstyle="success"
                                        data-offstyle="dark">
                                </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Roles</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form class="form-horizontal" method="POST" action="{{route('admin.users.roles.update', $user)}}" id="form_roles">
                    {{csrf_field()}}
                    {{method_field('PUT')}}
                    <div class="card-body">
                        <div>
                            <label>Roles</label>
                            @include('admin.partials.roles')
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Actualizar roles</button>
                    </div>
                    <!-- /.card-footer -->
                </form>
            </div>

            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Permisos</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form class="form-horizontal" action="{{route('admin.users.permissions.update', $user)}}" method="POST" id="form_permisos">
                    {{csrf_field()}}
                    {{method_field('PUT')}}
                    <div class="card-body">
                        <div>
                            <label>Permisos</label>
                            @include('admin.partials.permisos',['model' => $user])
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Actualizar permisos</button>
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
$('#roles').select2({
    theme: 'bootstrap4',
});
$('#institucion_id').select2({
    theme: 'bootstrap4',
});
//alert($("#inputNombre").val());
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