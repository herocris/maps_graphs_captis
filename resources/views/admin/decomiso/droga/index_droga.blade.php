@can('ver decomisos de drogas deshabilitados')
<div class="nav-item ml-auto">
    Ver registros:
    <input type="checkbox" id="desabi_dro" data-size="sm" checked data-toggle="toggle" data-on="Habilitados" data-off="Deshabilitados" data-onstyle="success" data-offstyle="danger">
</div>
@endcan
@can('crear decomisos de droga')
<a href="#" class="btn btn-success ml-auto" onclick="limpiar_droga()" data-toggle="modal"
    data-target="#createDrogaModal" data-toggle="tooltip" data-placement="bottom" title="Crear decomiso de droga"><i class="fas fa-plus-circle"></i></a>
@endcan
<table id="droga-table" class="cell-border display compact hover stripe" style="width:100%">
    <thead>
        <tr>
            <th>Descripción</th>
            <th>Presentación</th>
            <th>Cantidad</th>
            <th>Peso (Kg)</th>
            <th>presentacion_droga_id</th>
            <th>droga_id</th>
            <th>id</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>

@push('scripts')
<script>
@include('admin.decomiso.base_tabla_subdecomiso')
base_tabla('#droga-table', [4, 5, 6, 7], true);
 //-------------------droga
@can('ver decomisos de drogas deshabilitados')
$('#restaurar_drog').hide();
@endcan

 ///////////////evento click en cualquier fila de la tabla de drogas que muestra el modal para editar o para visualizar segun sean los permisos///////////////////////////////
$('#droga-table tbody').on('click', 'td', function () {
    $('#form_edit_droga').parsley().reset();
    var table = $('#droga-table').DataTable();

    if (table.data().count() != 0) {
        $("#droga_id_").val(table.row(this).data()[5]).trigger('change'); //setear select con texto y no con valor
        $('#presentacion_droga_id_').val(table.row(this).data()[4]).trigger('change');
        $('#drogaCantidad_').val(table.row(this).data()[2]);
        $('#drogaPeso_').val(table.row(this).data()[3]);
        $('#drogaOcultoId_').val(table.row(this).data()[6]);
        @can('editar decomisos de droga')
        $('#form_edit_droga').attr('action', "/decomisodroga/" + table.row(this).data()[6]);
        @endcan
        @can('borrar decomisos de droga')
        $('#form_delete_droga').attr('action', "/decomisodroga/" + table.row(this).data()[6]);
        @endcan
        $('#editDrogaModal').modal('toggle');

        //$('.form-select').trigger('change');

        if(table.row(this).data()[7]==null){
            @can('ver decomisos de drogas deshabilitados')
            $('#restaurar_drog').hide();
            @endcan
            @can('editar decomisos de droga')
            $('#droga_b').show();
            @endcan
            @can('borrar decomisos de droga')
            $('#droga-borrar').show();
            @endcan
        }
        else{
            @can('ver decomisos de drogas deshabilitados')
            $('#restaurar_drog').show();
            @endcan
            @can('editar decomisos de droga')
            $('#droga_b').hide();
            @endcan
            @can('borrar decomisos de droga')
            $('#droga-borrar').hide();
            @endcan
            //$('#form_restore_droga').attr('action', "/decomisodrogaa/" + table.row(this).data()[6]);
            //var jds=table.row(this).data()[6];
            //var ruta="{{route('decomisodroga.restaurar','')}}/"+table.row(this).data()[6];
            //alert(ruta);
            @can('ver decomisos de drogas deshabilitados')
            $('#form_restore_droga').attr('action', "{{route('decomisodroga.restaurar','')}}/"+table.row(this).data()[6] );
            @endcan
        }
    }
});


/////////////////////////////////boton de dehabilitados que habilita o dehabilita dichos registros///////////////////////////////////
@can('ver decomisos de drogas deshabilitados')
$('#desabi_dro').change(function() {
    if (this.checked) {
        $('#droga-table').DataTable().clear().draw();
        registros_dro("habilitados");
    } else {
        $('#droga-table').DataTable().clear().draw();
        registros_dro("hasbilitados");
    }
});
@endcan
var pres_drogs=@json($presentacion_drogas);
///////////////////////////funcion ajax para llenar tabla de registros habilitados o deshabilitados segun sea el criterio////////////////////////////
function registros_dro(estado){

    var url="{{route('decomisodroga.habilitados',['Id' => 'idd', 'tipo' => 'tip'])}}",
        url=url.replace('idd', @json($decomiso->id));
        url=url.replace('tip', estado);

    $.ajax({
        url:url,
        type:'get',
        dataType:'json',
        success: function (response) {
            response.decomisos_habilitados.forEach(element=>{
                var presenta = pres_drogs.find(element1 => element1.id == element.pivot.presentacion_droga_id);
                $('#droga-table').DataTable().row.add([
                    element.descripcion,
                    presenta.descripcion,
                    element.pivot.cantidad,
                    element.pivot.peso,
                    element.pivot.presentacion_droga_id,
                    element.id,
                    element.pivot.id,
                    element.pivot.deleted_at,
                ]).draw( false );
            });
        },
        statusCode: {
            404: function() {
                alert('web not found');
            }
        },
    });
}

registros_dro("habilitados");
</script>
@endpush
