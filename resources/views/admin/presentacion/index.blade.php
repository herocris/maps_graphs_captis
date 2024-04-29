@extends('admin.layout')

@section('header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Presentaciones de droga</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('index')}}">Inicio</a></li>
                <li class="breadcrumb-item active">Tipos de droga</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
@stop
@section('content')
<div class="card">
    <div class="card-header d-flex">
        <h3 class="card-title">Listado de presentaciones de droga</h3>
        <!-- <button class="btn btn-success ml-auto"><i class="fas fa-plus-circle"></i></button> -->
        <a href="{{route('presentacion.create')}}" class="btn btn-success ml-auto"><i class="fas fa-plus-circle"></i></a>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <table id="user-table" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Descripción</th>
                    <th>Peso aproximado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($presentaciones as $presentacion)
                <tr>
                    <td>{{$presentacion->descripcion}}</td>
                    <td>{{$presentacion->peso}}</td>
                    <td>
                        <a href="{{route('presentacion.edit',$presentacion)}}" class="btn btn-sm btn-info"><i class="fas fa-pencil-alt"></i></a>
                        <form method="POST" action="{{route('presentacion.destroy',$presentacion)}}" style="display: inline;">
                            {{csrf_field()}} {{method_field('DELETE')}}
                            <button class="btn btn-sm btn-danger"><i class="fas fa-times-circle" onclick="return confirm('¿Borrar?');"></i></button>
                        </form>
                        
                    </td>
                    
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <!-- /.card-body -->
</div>
@stop