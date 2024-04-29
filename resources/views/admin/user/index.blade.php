@extends('admin.layout')

@section('header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Usuarios</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('index')}}">Inicio</a></li>
                <li class="breadcrumb-item active">Usuarios</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
@stop
@section('content')
<div class="card">
    <div class="card-header d-flex">
        <h3 class="card-title">Listado de usuarios</h3>
        <!-- <button class="btn btn-success ml-auto"><i class="fas fa-plus-circle"></i></button> -->
        @can('ver usuarios deshabilitados')
        <div class="nav-item ml-auto">
          @if($dehabilitado)
            <form method="GET" action="{{route('user.index')}}">
            Ver registros:
                <input type="checkbox" onChange="this.form.submit()" unchecked data-toggle="toggle" data-on="Habilitados" data-off="Deshabilitados" data-onstyle="success" data-offstyle="danger">
            </form>
          @else
            <form method="GET" action="{{route('user.show', 1)}}">
            Ver registros:
                <input type="checkbox" onChange="this.form.submit()" checked data-toggle="toggle" data-on="Habilitados" data-off="Deshabilitados" data-onstyle="success" data-offstyle="danger">
            </form>
          @endif
        </div>
        @endcan
        <a href="{{route('user.create')}}" class="btn btn-success ml-auto" data-toggle="tooltip" data-placement="bottom" title="Crear usuario"><i class="fas fa-plus-circle"></i></a>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <table id="user-table" class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>creacion</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Institución</th>
                    <th>Roles</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>{{$user->created_at}}</td>
                    <td>{{$user->name}}</td>
                    <td>{{$user->email}}</td>
                    <td>{{$user->institucion->nombre}}</td>
                    <td>{{$user->getRoleNames()->implode(', ')}}</td>                 
                    <td>
                        @if (!$user->hasRole('SuperAdministrador'))
                            
                            @if ($user->deleted_at!=null)
                            <form method="GET" action="{{route('user.restore',$user->id)}}" style="display: inline;">
                                    {{csrf_field()}}
                                    <button class="btn btn-sm btn-success" data-toggle="tooltip" data-placement="bottom" title="Restaurar usuario"><i class="fas fa-times-circle"></i></button>
                            </form> 
                            @else
                            @can('editar usuarios')
                                <a href="{{route('user.edit',$user)}}" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="bottom" title="Editar usuario"><i class="fas fa-pencil-alt"></i></a>
                            @endcan
                            @can('borrar usuarios')    
                                <form method="POST" action="{{route('user.destroy',$user)}}" style="display: inline;">
                                    {{csrf_field()}} {{method_field('DELETE')}}
                                    <button class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="bottom" title="Borrar usuario" onclick="return confirm('¿Borrar?');"><i class="fas fa-times-circle"></i></button>
                                </form> 
                            @endcan
                            @endif   
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <!-- /.card-body -->
</div>
@stop