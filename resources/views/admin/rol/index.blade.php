@extends('admin.layout')

@section('header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Roles</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('index')}}">Inicio</a></li>
                <li class="breadcrumb-item active">Roles</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
@stop
@section('content')
<div class="card">
    <div class="card-header d-flex">
        <h3 class="card-title">Listado de Roles</h3>
        <!-- <button class="btn btn-success ml-auto"><i class="fas fa-plus-circle"></i></button> -->
        @can('ver roles deshabilitados')
        <div class="nav-item ml-auto">
          @if($dehabilitado)
            <form method="GET" action="{{route('role.show', 1)}}">
            Ver registros:
                <input type="checkbox" onChange="this.form.submit()" checked data-toggle="toggle" data-on="Habilitados" data-off="Deshabilitados" data-onstyle="success" data-offstyle="danger">
            </form>
          @else
            <form method="GET" action="{{route('role.index')}}">
            Ver registros:
                <input type="checkbox" onChange="this.form.submit()" unchecked data-toggle="toggle" data-on="Habilitados" data-off="Deshabilitados" data-onstyle="success" data-offstyle="danger">
            </form>
          @endif
        </div>
        @endcan
        <a href="{{route('role.create')}}" class="btn btn-success ml-auto"><i class="fas fa-plus-circle" data-toggle="tooltip" data-placement="bottom" title="Crear rol"></i></a>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <table id="user-table" class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>creacion</th>
                    <th>Nombre</th>
                    <th>Permisos</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($roles as $rol)
                <tr>
                    <td>{{$rol->created_at}}</td>
                    <td>{{$rol->name}}</td>
                    <td>{{$rol->permissions->pluck('name')->implode(', ')}}</td>
                    <td>
                    <div class="row">
                    
                        @if ($rol->name !='SuperAdministrador')
                            @if ($rol->deleted_at!=null)
                            <form method="GET" action="{{route('role.restore',$rol->id)}}" style="display: inline;">
                                    {{csrf_field()}}
                                    <button class="btn btn-sm btn-success" data-toggle="tooltip" data-placement="bottom" title="Restaurar usuario"><i class="fas fa-times-circle"></i></button>
                            </form>
                            @else
                            @can('editar roles')
                                <a href="{{route('role.edit',$rol)}}" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="bottom" title="Editar rol"><i class="fas fa-pencil-alt"></i></a>
                            @endcan
                            @can('borrar roles')
                                <form method="POST" action="{{route('role.destroy',$rol)}}" style="display: inline;">
                                    {{csrf_field()}} {{method_field('DELETE')}}
                                    <button class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="bottom" title="Borrar rol" onclick="return confirm('¿Borrar?');"><i class="fas fa-times-circle"></i></button>
                                </form>
                            @endcan
                            @endif
                            
                        @endif 
                      
                    </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <!-- /.card-body -->
</div>
@stop