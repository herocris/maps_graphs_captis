@extends('admin.layout')

@section('header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Clientes Autorizados</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('index')}}">Inicio</a></li>
                <li class="breadcrumb-item active">Lista de clientes autorizados</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
@stop
@section('content')

<div class="card">
    <div class="card-header d-flex">
        <h3 class="card-title">Listado de Clientes</h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <table id="AuthorizedClients-table" class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>Id Cliente</th>
                    <th>Nombre</th>
                    <th>Scopes</th>
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
var table=$('#AuthorizedClients-table').DataTable({
        columnDefs: [
                    {
                        "targets": [0],
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

    indexAuthorizedClientes()
    function indexAuthorizedClientes(){
        $.ajax({
            url:"{{route('passport.tokens.index')}}", 
            type: 'GET', 
            success: function (response, status, xhr) {
                console.log(response);
                response.forEach(function(cliente, index) {
                    table.row.add([
                        cliente.client.id,
                        cliente.client.name,
                        cliente.scopes,
                        "<button class='btn btn-sm btn-danger' data-toggle='tooltip' data-placement='bottom' title='Borrar token' onclick=borrarCliente('"+cliente.id+"',this)><i class='fas fa-trash-alt'></i></button>"
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
                url:"{{route('passport.tokens.destroy','')}}/"+idClient, 
                type: 'DELETE', 
                data: {
                    _token: "{{ csrf_token() }}",
                },
                success: function (response, status, xhr) {
                    console.log(status);
                    table.clear().draw()
                    indexAuthorizedClientes()
                },
                error: function (jqXhr, textStatus, errorMessage) {
                        console.log('Error ' + errorMessage);
                }
            });
        }   
    }
</script>
@endpush