@extends('admin.layout')

@section('header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Tipos de parametros</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('index')}}">Inicio</a></li>
                <li class="breadcrumb-item active">Tipos de parametros</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
@stop
@section('content')
<div class="card">
    <div class="card-header d-flex">
        <h3 class="card-title">Listado de tipos de parametros</h3>
        <!-- <button class="btn btn-success ml-auto"><i class="fas fa-plus-circle"></i></button> -->
        <a href="{{route('parametro.create')}}" class="btn btn-success ml-auto"><i class="fas fa-plus-circle"></i></a>
    </div>
    <!-- /.card-header -->
    <div class="card-body">

    <div class="form-group row">
        <label for="inputTipo" class="col-sm-2 col-form-label">Tipo de parametro</label>
        <div class="col-sm-10">
        <select class="form-select" name="tipo_parametro_id" id="inputTipo">
            @foreach($kkd as $kkd2)
                <option value="" selected disabled hidden>Selecciona el tipo</option>
                <option value={{$kkd2->id}}>{{$kkd2->descripcion}}</option>   
            @endforeach             
        </select>
            
        </div>
    </div>

        <table id="user-table" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Descripción</th>
                    <th>Tipo de parametro</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($parametros as $parametro)
                <tr>
                    <td>{{$parametro->descripcion}}</td>
                    <td>{{$parametro->tipo_parametro->descripcion}}</td>
                    <td>
                        <a href="{{route('parametro.edit',$parametro)}}" class="btn btn-sm btn-info"><i class="fas fa-pencil-alt"></i></a>
                        <form method="POST" action="{{route('parametro.destroy',$parametro)}}" style="display: inline;">
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