@extends('admin.layout')

@section('header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Precursores químicos</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('index')}}">Inicio</a></li>
                <li class="breadcrumb-item active">Precursores químicos</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
@stop
@section('content')
<div class="card">
    <div class="card-header d-flex">
        <h3 class="card-title">Listado de precursores químicos</h3>
        <!-- <button class="btn btn-success ml-auto"><i class="fas fa-plus-circle"></i></button> -->
        @can('ver precursores deshabilitados')
        <div class="nav-item ml-auto">
          @if($dehabilitado)
            <form method="GET" action="{{route('precursor.index')}}">
            Ver registros:
                <input type="checkbox" onChange="this.form.submit()" unchecked data-toggle="toggle" data-on="Habilitados" data-off="Deshabilitados" data-onstyle="success" data-offstyle="danger">
            </form>
          @else
            <form method="GET" action="{{route('precursor.show', 1)}}">
            Ver registros:
                <input type="checkbox" onChange="this.form.submit()" checked data-toggle="toggle" data-on="Habilitados" data-off="Deshabilitados" data-onstyle="success" data-offstyle="danger">
            </form>
          @endif
        </div>
        @endcan
        @can('crear precursor')
        <a href="{{route('precursor.create')}}" class="btn btn-success ml-auto" data-toggle="tooltip" data-placement="bottom" title="Crear precursor químico"><i class="fas fa-plus-circle"></i></a>
        @endcan
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <table id="user-table" class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>Creacion</th>
                    <th>Descripción</th>
                    {{--  <th>Presentacion</th>  --}}
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($precursores as $precursor)
                <tr>
                    <td>{{$precursor->created_at}}</td>
                    <td>{{$precursor->descripcion}}</td>
                    {{--  <td>{{$precursor->presentacion}}</td>  --}}
                    <td>
                    @if ($precursor->deleted_at!=null)
                    <form method="GET" action="{{route('precursor.restore',$precursor->id)}}" style="display: inline;">
                            {{csrf_field()}}
                            <button class="btn btn-sm btn-success" data-toggle="tooltip" data-placement="bottom" title="Restaurar precursor"><i class="fas fa-trash-restore-alt"></i></i></button>
                    </form>
                    @else
                    @can('editar precursor')
                        <a href="{{route('precursor.edit',$precursor)}}" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="bottom" title="Editar precursor químico"><i class="fas fa-pencil-alt"></i></a>
                    @endcan
                    @can('borrar precursor')
                        <form method="POST" action="{{route('precursor.destroy',$precursor)}}" style="display: inline;">
                            {{csrf_field()}} {{method_field('DELETE')}}
                            <button class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="bottom" title="Borrar precursor químico" onclick="return confirm('¿Borrar?');"><i class="fas fa-times-circle"></i></button>
                        </form>
                    @endcan
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