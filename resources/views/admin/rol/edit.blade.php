@extends('admin.layout')
@section('header')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-6">
            <h1 class="m-0">Editar rol</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('role.index')}}">Roles</a></li>
                <li class="breadcrumb-item active">Editar rol</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
@stop
@section('content')
<!-- <div class="container">
    <div class="row">
        <div class="md-col-4">
        </div>
        <div class="md-col-4">
        </div>
        <div class="md-col-4">
        </div>
    </div>  
</div> -->
<div class="container" style="width: 60%;">

    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Editar rol</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form data-parsley-validate class="form-horizontal" action="{{route('role.update',$role)}}" method="POST" id="form_rol">
            
            {{method_field('PUT')}}
            @include('admin.rol.form')
            <!-- /.card-footer -->
        </form>
        
    </div>

</div>


@stop