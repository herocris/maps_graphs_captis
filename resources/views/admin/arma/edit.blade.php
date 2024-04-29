@extends('admin.layout')
@section('header')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-6">
            <h1 class="m-0">Editar arma</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('arma.index')}}">Armas</a></li>
                <li class="breadcrumb-item active">Editar arma</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
@stop
@section('content')
<div class="container" style="width: 60%;">

    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Editar arma</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form data-parsley-validate class="form-horizontal" action="{{route('arma.update',$arma)}}" method="POST" id="form_arma">
            {{csrf_field()}}
            {{method_field('PUT')}}
            @include('admin.arma.form')
            <!-- /.card-footer -->
        </form> 
        
    </div>

</div>


@stop