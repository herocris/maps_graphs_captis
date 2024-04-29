@can('ver decomisos de armas deshabilitados')
<div class="nav-item ml-auto">
    Ver registros:
    <input type="checkbox" data-width="120" id="desabi_arma" data-size="sm" checked data-toggle="toggle" data-on="Habilitados" data-off="Deshabilitados" data-onstyle="success" data-offstyle="danger">
</div>
@endcan
@can('crear decomisos de armas')
<a href="#" onclick="limpiar_arma()" class="btn btn-success ml-auto" data-toggle="modal"
    data-target="#createArmaModal" data-toggle="tooltip" data-placement="bottom" title="Crear decomiso de arma"><i class="fas fa-plus-circle"></i></a>
@endcan
<table id="arma-table" class="cell-border display compact hover stripe" style="width:100%">
    <thead>
        <tr>
            <th>Descripción</th>
            <th>Cantidad</th>
            <th>arma_id</th>
            <th>decomiso_arma_id</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>
@push('scripts')
<script>
@include('admin.decomiso.base_tabla_subdecomiso')
base_tabla('#arma-table', [2, 3, 4], false);
//-----------------armas
@can('ver decomisos de armas deshabilitados')
$('#restaurar_arm').hide();
@endcan

$('#arma-table tbody').on('click', 'td', function () {
    $('#form_edit_arma').parsley().reset();
    var table = $('#arma-table').DataTable();
    if (table.data().count() != 0) {
        $("#arma_id_").val(table.row(this).data()[2]).trigger('change'); //setear select con texto y no con valor
        $('#cantidadArma_').val(table.row(this).data()[1]);
        $('#armaOcultoId_').val(table.row(this).data()[3]);
        @can('editar decomisos de armas')
        $('#form_edit_arma').attr('action', "/decomisoarma/" + table.row(this).data()[3]);
        @endcan
        @can('borrar decomisos de armas')
        $('#form_delete_arma').attr('action', "/decomisoarma/" + table.row(this).data()[3]);
        @endcan
        $('#editArmaModal').modal('toggle');

        //$('.form-select').trigger('change');

        if(table.row(this).data()[4]==null){
            @can('ver decomisos de armas deshabilitados')
            $('#restaurar_arm').hide();
            @endcan
            @can('editar decomisos de armas')
            $('#arma-b').show();
            @endcan
            @can('borrar decomisos de armas')
            $('#arma-borrar').show();
            @endcan
        }
        else{
            @can('ver decomisos de armas deshabilitados')
            $('#restaurar_arm').show();
            @endcan
            @can('editar decomisos de armas')
            $('#arma-b').hide();
            @endcan
            @can('borrar decomisos de armas')
            $('#arma-borrar').hide();
            @endcan
            @can('ver decomisos de armas deshabilitados')
            $('#form_restore_arma').attr('action', "{{route('decomisoarma.restaurar','')}}/"+table.row(this).data()[3] );
            @endcan
        }
    }
});

/////////////////////////////////llenar armas///////////////////////////////////

@can('ver decomisos de armas deshabilitados')
$('#desabi_arma').change(function() {
    if (this.checked) {
        $('#arma-table').DataTable().clear().draw();
        registros_arm("habilitados");
    } else {
        $('#arma-table').DataTable().clear().draw();
        registros_arm("dehabilitados");
    }
});
@endcan



function registros_arm(estado){
    //alert("llega");
    var url="{{route('decomisoarma.habilitados',['Id' => 'idd', 'tipo' => 'tip'])}}",
        url=url.replace('idd', @json($decomiso->id));
        url=url.replace('tip', estado);

    $.ajax({
        url:url,
        type:'get',
        dataType:'json',
        success: function (response) {
            response.decomisos_habilitados.forEach(element=>{
                //console.log(presenta);
                $('#arma-table').DataTable().row.add([
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


            registros_arm("habilitados");
</script>
@endpush