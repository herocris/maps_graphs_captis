@can('ver decomisos de municiones deshabilitados')
<div class="nav-item ml-auto">
    Ver registros:
    <input type="checkbox" data-width="120" id="desabi_municion" data-size="sm" checked data-toggle="toggle" data-on="Habilitados" data-off="Deshabilitados" data-onstyle="success" data-offstyle="danger">
</div>
@endcan
@can('crear decomisos de municiones')
<a href="#" onclick="limpiar_municion()" class="btn btn-success ml-auto" data-toggle="modal"
    data-target="#createMunicionModal" data-toggle="tooltip" data-placement="bottom" title="Crear decomiso de munición"><i class="fas fa-plus-circle"></i></a>
@endcan
<table id="municion-table" class="cell-border display compact hover stripe" style="width:100%">
    <thead>
        <tr>
            <th>Descripción</th>
            <th>Cantidad</th>
            <th>municion_id</th>
            <th>decomiso_municion_id</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>

@push('scripts')
<script>
@include('admin.decomiso.base_tabla_subdecomiso')
base_tabla('#municion-table', [2, 3, 4], false);
//------------------------municiones
@can('ver decomisos de municiones deshabilitados')
$('#restaurar_mun').hide();
@endcan

$('#municion-table tbody').on('click', 'td', function () {
    $('#form_edit_municion').parsley().reset();
    var table = $('#municion-table').DataTable();
    if (table.data().count() != 0) {
        $("#municion_id_").val(table.row(this).data()[2]).trigger('change'); //setear select con texto y no con valor
        $('#cantidadMunicion_').val(table.row(this).data()[1]);
        $('#municionOcultoId_').val(table.row(this).data()[3]);
        @can('editar decomisos de municiones')
        $('#form_edit_municion').attr('action', "/decomisomunicion/" + table.row(this).data()[3]);
        @endcan
        @can('borrar decomisos de municiones')
        $('#form_delete_municion').attr('action', "/decomisomunicion/" + table.row(this).data()[3]);
        @endcan
        $('#editMunicionModal').modal('toggle');

        //$('.form-select').trigger('change');

        if(table.row(this).data()[4]==null){
            @can('ver decomisos de municiones deshabilitados')
            $('#restaurar_mun').hide();
            @endcan
            @can('editar decomisos de municiones')
            $('#municion-b').show();
            @endcan
            @can('borrar decomisos de municiones')
            $('#municion-borrar').show();
            @endcan
        }
        else{
            @can('ver decomisos de municiones deshabilitados')
            $('#restaurar_mun').show();
            @endcan
            @can('editar decomisos de municiones')
            $('#municion-b').hide();
            @endcan
            @can('borrar decomisos de municiones')
            $('#municion-borrar').hide();
            @endcan
            @can('ver decomisos de municiones deshabilitados')
            $('#form_restore_municion').attr('action', "{{route('decomisomunicion.restaurar','')}}/"+table.row(this).data()[3] );
            @endcan
        }
    }
});

/////////////////////////////////llenar municiones///////////////////////////////////
@can('ver decomisos de municiones deshabilitados')
$('#desabi_municion').change(function() {
    if (this.checked) {
        $('#municion-table').DataTable().clear().draw();
        registros_mun("habilitados");
    } else {
        $('#municion-table').DataTable().clear().draw();
        registros_mun("dehabilitados");
    }
});
@endcan



function registros_mun(estado){
    //alert("llega");
    var url="{{route('decomisomunicion.habilitados',['Id' => 'idd', 'tipo' => 'tip'])}}",
        url=url.replace('idd', @json($decomiso->id));
        url=url.replace('tip', estado);

    $.ajax({
        url:url,
        type:'get',
        dataType:'json',
        success: function (response) {
            //console.log(response.decomisos_habilitados);
            response.decomisos_habilitados.forEach(element=>{

                $('#municion-table').DataTable().row.add([
                    element.descripcion,
                    element.pivot.cantidad,
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


registros_mun("habilitados");
</script>
@endpush