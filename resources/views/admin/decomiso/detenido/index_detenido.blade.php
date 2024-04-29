@can('ver detenidos en decomisos deshabilitados')
<div class="nav-item ml-auto">
    Ver registros:
    <input type="checkbox" data-width="120" id="desabi_detenido" data-size="sm" checked data-toggle="toggle" data-on="Habilitados" data-off="Deshabilitados" data-onstyle="success" data-offstyle="danger">
</div>
@endcan
@can('crear detenidos en decomisos')
<a href="#" onclick="limpiar_detenido()" class="btn btn-success ml-auto" data-toggle="modal"
    data-target="#createDetenidoModal" data-toggle="tooltip" data-placement="bottom" title="Crear captura de detenido"><i class="fas fa-plus-circle"></i></a>
@endcan
<table id="detenido-table" class="cell-border display compact hover stripe" style="width:100%">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Alias</th>
            <th>Identidad</th>
            <th>Género</th>
            <th>Edad</th>
            <th>Tipo de Id</th>
            <th>Estructura criminal</th>
            <th>Ocupación</th>
            <th>Estado civil</th>
            <th>Nacionalidad</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>

@push('scripts')
<script>
@include('admin.decomiso.base_tabla_subdecomiso')
base_tabla('#detenido-table', [10, 11, 12, 13, 14, 15, 16], true);

@can('ver detenidos en decomisos deshabilitados')
$('#restaurar_det').hide();
@endcan

$('#detenido-table tbody').on('click', 'td', function () {
    $('#form_edit_detenido').parsley().reset();
    var table = $('#detenido-table').DataTable();
    if (table.data().count() != 0) {

        $('#nombreDetenido_').val(table.row(this).data()[0]);
        $('#alias_').val(table.row(this).data()[1]);
        $('#identidad_').val(table.row(this).data()[2]);
        $('#genero_').val(table.row(this).data()[3]).trigger('change');
        $('#edad_').val(table.row(this).data()[4]);
        $('#identificacion_id_').val(table.row(this).data()[10]).trigger('change');
        $('#estructura_id_').val(table.row(this).data()[11]).trigger('change');
        $('#ocupacion_id_').val(table.row(this).data()[12]).trigger('change');
        $('#estado_civil_id_').val(table.row(this).data()[13]).trigger('change');
        $('#pais_id_').val(table.row(this).data()[14]).trigger('change');
        $('#genero_').val(table.row(this).data()[15]).trigger('change');
        @can('editar detenidos en decomisos')
        $('#form_edit_detenido').attr('action', "/decomisodetenido/" + table.row(this).data()[16]);
        @endcan
        @can('borrar detenidos en decomisos')
        $('#form_delete_detenido').attr('action', "/decomisodetenido/" + table.row(this).data()[16]);
        @endcan
        $('#editDetenidoModal').modal('toggle');

        //$('.form-select').trigger('change');

        if(table.row(this).data()[17]==null){
            @can('ver detenidos en decomisos deshabilitados')
            $('#restaurar_det').hide();
            @endcan
            @can('editar detenidos en decomisos')
            $('#detenido-b').show();
            @endcan
            @can('borrar detenidos en decomisos')
            $('#detenido-borrar').show();
            @endcan
        }
        else{
            @can('ver detenidos en decomisos deshabilitados')
            $('#restaurar_det').show();
            @endcan
            @can('editar detenidos en decomisos')
            $('#detenido-b').hide();
            @endcan
            @can('borrar detenidos en decomisos')
            $('#detenido-borrar').hide();
            @endcan
            @can('ver detenidos en decomisos deshabilitados')
            $('#form_restore_detenido').attr('action', "{{route('decomisodetenido.restaurar','')}}/"+table.row(this).data()[16] );
            @endcan
        }
    }

    if($("#nombreDetenido_").val()!="" || $("#alias_").val()!=""){
        $("#alias_").removeAttr( "required" );
        $("#nombreDetenido_").removeAttr( "required" );
    }else if($("#nombreDetenido_").val()=="" && $("#alias_").val()==""){
        $("#alias_").attr( "required",true );
        $("#nombreDetenido_").attr( "required",true );
    }
});

@can('ver detenidos en decomisos deshabilitados')
$('#desabi_detenido').change(function() {
    if (this.checked) {
        $('#detenido-table').DataTable().clear().draw();
        registros_det("habilitados");
    } else {
        $('#detenido-table').DataTable().clear().draw();
        registros_det("dehabilitados");
    }
});
@endcan



function registros_det(estado){
    //alert("llega");
    var url="{{route('decomisodetenido.habilitados',['Id' => 'idd', 'tipo' => 'tip'])}}",
        url=url.replace('idd', @json($decomiso->id));
        url=url.replace('tip', estado);

    $.ajax({
        url:url,
        type:'get',
        dataType:'json',
        success: function (response) {
            //console.log(response.decomisos_habilitados);
            response.decomisos_habilitados.forEach(element=>{

                $('#detenido-table').DataTable().row.add([
                    element.nombre,
                    element.alias,
                    element.identidad,
                    element.genero=="M"?"Masculino":"Femenino",
                    element.edad,
                    element.tipo_id,
                    element.estructura,
                    element.ocupacion,
                    element.estado_civil,
                    element.nacionalidad,

                    element.identificacion_id,
                    element.estructura_id,
                    element.ocupacion_id,
                    element.estado_civil_id,
                    element.pais_id,
                    element.genero,
                    element.id,
                    element.deleted_at,

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


registros_det("habilitados");
</script>
@endpush