@extends('admin.layout')

@section('header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Tokens personales</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('index')}}">Inicio</a></li>
                <li class="breadcrumb-item active">Lista de Tokens personales</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
@stop
@section('content')

<div class="modal fade" id="createTokeModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Crear token</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
                <div class="modal-body">
                    <div class="form-group row" id="nombre">
                        <label for="tokenNombre" class="col-sm-3 col-form-label" id="labelTokenNombre">Nombre</label>
                        <div class="col-sm-9">
                            <input type="text" name="tokenNombre" class="form-control" id="tokenNombre" placeholder="Nombre" value="" autocomplete="off" required>
                        </div>
                    </div>
                    <div class="form-group row" id="scopes">

                    </div>
                    <div class="form-group row" id="tokenContent_">
                        <div class="col-sm-12">
                            <textarea class="form-control" id="tokenContent" rows="23" readonly autocomplete="off" style="display: none"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" id="AddToken" class="btn btn-primary" onclick="agregarToken()" id="agregar_token" style="display: block">Agregar</button>
                </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex">
        <h3 class="card-title">Listado de Tokens</h3>
        <a href="#" class="btn btn-success ml-auto" onclick="showModalAddToken()" data-toggle="modal"
    data-target="#createTokeModal" data-toggle="tooltip" data-placement="bottom" title="Crear token personal"><i class="fas fa-plus-circle"></i></a>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <table id="tokensPersonal-table" class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    <!-- /.card-body -->
</div>
@stop
@push('scripts')
<script>
    ////inicialización de tabla tipo datatables
var table=$('#tokensPersonal-table').DataTable({
        language: {
            "decimal": "",
            "emptyTable": "No hay información",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
            "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
            "infoFiltered": "(Filtrado de _MAX_ total entradas)",
            "infoPostFix": "",
            "thousands": ",",
            "lengthMenu": "Mostrar _MENU_ Entradas",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "search": "Buscar:",
            "zeroRecords": "Sin resultados encontrados",
            "paginate": {
                "first": "Primero",
                "last": "Ultimo",
                "next": "Siguiente",
                "previous": "Anterior"
            }
        },
    });

    ////funcion para motrar modal al momento de presionar boton de nuevo token
    function showModalAddToken() {
        limpiar();
        cleanCheckbox();
        $('#labelTokenNombre').css("display" , "block");
        $('#tokenNombre').css("display" , "block");
        $('#AddToken').css("display" , "block");
        $('#scopes').css("display" , "block");
        $('#tokenContent').css("display" , "none");
    }

    /////funcion para limpiar campos de modal
    function limpiar() {
        $('#tokenNombre').val("");
        $('#tokenContent').val("");
    }

    ///////ajax para agregar token que cuando se ejecuta oculta elementos del modal y unicamente deja el token generado
    function agregarToken(){
        if ($('#tokenNombre').val()!="") {
            $.ajax({
                url:"{{route('passport.personal.tokens.store')}}",
                type: 'POST',  // http method
                dataType: 'json',
                data: {
                    name: $('#tokenNombre').val(),
                    scopes: scope_list,
                    _token: "{{ csrf_token() }}",
                },
                success: function (response, status, xhr) {
                    table.clear().draw()
                    indexTokens()
                    limpiar();
                    $('#labelTokenNombre').css("display" , "none");
                    $('#tokenNombre').css("display" , "none");
                    $('#AddToken').css("display" , "none");
                    $('#scopes').css("display" , "none");
                    $('#tokenContent').css("display" , "block");
                    $('#tokenContent').val(response.accessToken);
                    console.log(status);
                },
                error: function (jqXhr, textStatus, errorMessage) {
                        console.log('Error ' + errorMessage);
                }
            });
        }else{
            alert("El campo nombre no puede ir vacío");
        }
    }


    indexTokens()
    /////////////ajax que muestra la lista de tokens personales
    function indexTokens(){
        $.ajax({
            url:"{{route('passport.personal.tokens.index')}}",
            type: 'GET',
            success: function (response, status, xhr) {
                console.log(status,"aslkdf");
                response.forEach(function(token, index) {
                    var onclickFunction="borrarToken('"+token.id+"'),this";
                    table.row.add([
                        token.name,
                        "<button class='btn btn-sm btn-danger' data-toggle='tooltip' data-placement='bottom' title='Borrar token' onclick="+onclickFunction+"><i class='fas fa-times-circle'></i></button>"
                    ],).draw(false);
                });
            },
            error: function (jqXhr, textStatus, errorMessage) {
                    console.log('Error ' + errorMessage);
            }
        });
    }

    ////////ajax que borra los tokens
    function borrarToken(idToken){
        var result = confirm("Seguro que desea borrar?");
        if (result) {
            $.ajax({
                url:"{{route('passport.personal.tokens.destroy','')}}/"+idToken,
                type: 'DELETE',
                data: {
                    _token: "{{ csrf_token() }}",
                },
                success: function (response, status, xhr) {
                    console.log(status);
                    table.clear().draw()
                    indexTokens()
                },
                error: function (jqXhr, textStatus, errorMessage) {
                        console.log('Error ' + errorMessage);
                }
            });
        }

    }
    scopes()
    ///////ajax que muestra los scopes en el modal para agregar tokens
    function scopes(){
        $.ajax({
            url:"{{route('passport.scopes.index')}}",
            type: 'GET',
            success: function (response, status, xhr) {
                console.log(status);
                response.forEach(function(scope, index) {
                    $('#scopes').append(
                    "<div class='form-check'>"
                        +"<div class='col-sm-3'>"
                            +"<input type='checkbox' class='form-check-input' id='check1' onclick='che(this.value)' name='option1' value="+scope.id+">"
                        +"</div>"
                        +"<label class='form-check-label col-sm-9' for='check1'>"+scope.id+"</label>"
                    +"</div>"
                    );
                });

            },
            error: function (jqXhr, textStatus, errorMessage) {
                    console.log('Error ' + errorMessage);
            }
        });
    }
/////////////arreglo que contiene los scopes seleccionados en los checkbox
    var scope_list=[];
/////////////////////funcion para tomar el valor de las checkbox y ponerlo en el arreglo anteriormente mencionado
    function che(e){
        scope_list=[];
        $("input[name='option1']:checked").each(function () {
            scope_list.push($(this).val());
        });
    }
/////funcion para limpiar las checkbox
    function cleanCheckbox(){
        $("input[name='option1']:checked").each(function () {
            this.checked = false;
        });
    }



</script>
@endpush
