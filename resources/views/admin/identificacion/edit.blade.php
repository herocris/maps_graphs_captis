@extends('admin.layout')
@section('header')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-6">
            <h1 class="m-0">Editar identificación</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('identificacion.index')}}">Identificaciones</a></li>
                <li class="breadcrumb-item active">Editar identificación</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
@stop
@section('content')
<div class="container" style="width: 60%;">

    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Editar identificación</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form data-parsley-validate class="form-horizontal" action="{{route('identificacion.update',$identificacion)}}" method="POST" id="form_identificacion">
            {{csrf_field()}}
            {{method_field('PUT')}}
            @include('admin.identificacion.form')
            <!-- /.card-footer -->
        </form> 
        
    </div>

</div>


@stop