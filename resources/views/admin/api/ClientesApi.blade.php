@extends('admin.layout')

@section('header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Clientes</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('index')}}">Inicio</a></li>
                <li class="breadcrumb-item active">Lista de clientes</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
@stop
@section('content')

<div class="modal fade" id="createClientModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Crear cliente</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
                <div class="modal-body">
                    <input id="client_id" value="" type="hidden">
                    <div class="form-group row" id="nombreCliente">
                        <label for="clienteNombre" class="col-sm-3 col-form-label" id="labelClienteNombre">Nombre</label>
                        <div class="col-sm-9">
                            <input type="text" name="clienteNombre" class="form-control" id="clienteNombre" placeholder="Nombre" value="" autocomplete="off" required>
                        </div>
                    </div>
                    <div class="form-group row" id="Url">
                        <label for="urlredireccion" class="col-sm-3 col-form-label" id="labelUrlRedireccion">URL de redirección</label>
                        <div class="col-sm-9">
                            <input type="text" name="urlredireccion" class="form-control" id="urlredireccion" placeholder="URL" value="" autocomplete="off" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" id="AddCliente" class="btn btn-primary" onclick="agregarCliente()"  style="display: block">Agregar</button>
                    <button type="button" id="UpdateCliente" class="btn btn-primary" onclick="editarCliente()"  style="display: block">Editar</button>
                </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex">
        <h3 class="card-title">Listado de Clientes</h3>
        <a href="#" class="btn btn-success ml-auto" id="createButton" onclick="showModalAddClient()" data-toggle="modal"
    data-target="#createClientModal" data-toggle="tooltip" data-placement="bottom" title="Crear cliente"><i class="fas fa-plus-circle"></i></a>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <table id="Clients-table" class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>Id Cliente</th>
                    <th>Nombre</th>
                    <th>Redirect</th>
                    <th>Secret</th>
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
var table=$('#Clients-table').DataTable({
        columnDefs: [
                    {
                        "targets": [2],
                        "visible": false,
                        "searchable": false
                    },
                ],
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

    $('#Clients-table tbody').on( 'click', '#editButton', function () {
        var data = table.row($(this).parents('tr')).data();
        $('#client_id').val(data[0]);
        $('#clienteNombre').val(data[1]);
        $('#urlredireccion').val(data[2]);
        $('#AddCliente').css("display" , "none");
        $('#UpdateCliente').css("display" , "block");
        $("#exampleModalLabel").html("Editar cliente");
        $('#createClientModal').modal('show')
    } );

    function showModalAddClient() {
        $("#exampleModalLabel").html("Crear cliente");
        $('#AddCliente').css("display" , "block");
        $('#UpdateCliente').css("display" , "none");
        limpiar();
    }

    function limpiar() {
        $('#clienteNombre').val("");
        $('#urlredireccion').val("");
    }

    function agregarCliente(){
        if ($('#clienteNombre').val()!="" && $('#urlredireccion').val()!="") {
            $.ajax({
                url:"{{route('passport.clients.store')}}",
                type: 'POST',
                dataType: 'json',
                data: {
                    name: $('#clienteNombre').val(),
                    redirect: $('#urlredireccion').val(),
                    _token: "{{ csrf_token() }}",
                },
                success: function (response, status, xhr) {
                    table.clear().draw()
                    indexClientes()
                    $('#createClientModal').modal('hide')
                    console.log(status);
                },
                error: function (jqXhr, textStatus, errorMessage) {
                        console.log('Error ' + errorMessage);
                }
            });
        }else{
            alert("El campo nombre y url no pueden ir vacíos");
        }
    }


    indexClientes()
    function indexClientes(){
        $.ajax({
            url:"{{route('passport.clients.index')}}",
            type: 'GET',
            success: function (response, status, xhr) {
                console.log(response,"aslkdf");
                response.forEach(function(cliente, index) {
                    table.row.add([
                        cliente.id,
                        cliente.name,
                        cliente.redirect,
                        cliente.secret,
                        "<button class='btn btn-sm btn-danger' data-toggle='tooltip' data-placement='bottom' title='Borrar token' onclick=borrarCliente('"+cliente.id+"',this)><i class='fas fa-trash-alt'></i></button>"
                        +"<button class='btn btn-sm btn-info' data-toggle='tooltip' data-placement='bottom' id='editButton' title='Editar cliente token' ><i class='fas fa-edit'></i></button>"
                    ],).draw(false);
                });
            },
            error: function (jqXhr, textStatus, errorMessage) {
                    console.log('Error ' + errorMessage);
            }
        });
    }

    function borrarCliente(idClient,e){
        var result = confirm("Seguro que desea borrar?");
        if (result) {
            $.ajax({
                url:"{{route('passport.clients.destroy','')}}/"+idClient,
                type: 'DELETE',
                data: {
                    _token: "{{ csrf_token() }}",
                },
                success: function (response, status, xhr) {
                    console.log(status);
                    table.clear().draw()
                    indexClientes()
                },
                error: function (jqXhr, textStatus, errorMessage) {
                        console.log('Error ' + errorMessage);
                }
            });
        }

    }

    function editarCliente(){
        if ($('#clienteNombre').val()!="" && $('#urlredireccion').val()!="") {
            $.ajax({
                url:"{{route('passport.clients.update','')}}/"+$('#client_id').val(),
                type: 'PUT',
                data: {
                    name: $('#clienteNombre').val(),
                    redirect: $('#urlredireccion').val(),
                    _token: "{{ csrf_token() }}",
                },
                success: function (response, status, xhr) {
                    console.log(status);
                    $('#createClientModal').modal('hide')
                    table.clear().draw()
                    indexClientes()
                },
                error: function (jqXhr, textStatus, errorMessage) {
                        console.log('Error ' + errorMessage);
                }
            });
        }else{
            alert("El campo nombre y url no pueden ir vacíos");
        }
    }
</script>
@endpush
