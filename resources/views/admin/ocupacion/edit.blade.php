@extends('admin.layout')
@section('header')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-6">
            <h1 class="m-0">Editar ocupación</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('ocupacion.index')}}">Ocupaciones</a></li>
                <li class="breadcrumb-item active">Editar ocupación</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
@stop
@section('content')
<div class="container" style="width: 60%;">

    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Editar ocupación</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form data-parsley-validate class="form-horizontal" action="{{route('ocupacion.update',$ocupacion)}}" method="POST" id="form_ocupacion">
            {{csrf_field()}}
            {{method_field('PUT')}}
            @include('admin.ocupacion.form')
            <!-- /.card-footer -->
        </form> 
        
    </div>

</div>


@stop