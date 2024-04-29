@can('ver decomisos de transportes deshabilitados')
<div class="nav-item ml-auto">
    Ver registros:
    <input type="checkbox" data-width="120" id="desabi_transporte" data-size="sm" checked data-toggle="toggle" data-on="Habilitados" data-off="Deshabilitados" data-onstyle="success" data-offstyle="danger">
</div>
@endcan
@can('crear decomisos de transportes')
<a href="#" onclick="limpiar_transporte()" class="btn btn-success ml-auto" data-toggle="modal"
    data-target="#createTransporteModal" data-toggle="tooltip" data-placement="bottom" title="Crear decomiso de transporte"><i class="fas fa-plus-circle"></i></a>
@endcan
<table id="transporte-table" class="cell-border display compact hover stripe" style="width:100%">
    <thead style="width:100%">
        <tr>
            <th>Tipo</th>
            <th>Marca</th>
            <th>Modelo</th>
            <th>Color</th>
            <th>Placa</th>
            <th>País de procedencia</th>
            <th>Departamento de procedencia</th>
            <th>Municipio de procedencia</th>
            <th>País de destino</th>
            <th>Departamento de destino</th>
            <th>Municipio de destino</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>

@push('scripts')
<script>
@include('admin.decomiso.base_tabla_subdecomiso')
base_tabla('#transporte-table', [11, 12, 13, 14, 15, 16, 17, 18], true);
@can('ver decomisos de transportes deshabilitados')
$('#restaurar_trans').hide();
@endcan

$('#transporte-table tbody').on('click', 'td', function () {
    $('#form_edit_transporte').parsley().reset();
    var table = $('#transporte-table').DataTable();
    //alert(table.row( this ).data()[12]);
    if (table.data().count() != 0) {
        if (table.row(this).data()[12] != "") {
            //alert(table.row( this ).data()[11]);
            //llenarDepartamentos_(table.row(this).data()[11]);

            //llenarMunicipios_(table.row(this).data()[12]);

            $('#departa_pro_').show();
            $('#municip_pro_').show();
        } else {
            $('#departa_pro_').hide();
            $('#municip_pro_').hide();
        }
        if (table.row(this).data()[15] != "") {
            //alert(table.row( this ).data()[11]);
            llenarDepartamentos2_(table.row(this).data()[14]);
            llenarMunicipios2_(table.row(this).data()[15]);
            $('#departa_des_').show();
            $('#municip_des_').show();
        } else {
            $('#departa_des_').hide();
            $('#municip_des_').hide();
        }
        //alert(table.row(this).data()[11]);
        $('#tipo_transporte_').val(table.row(this).data()[0]);
        $('#marca_').val(table.row(this).data()[1]);
        $('#modelo_').val(table.row(this).data()[2]);
        $('#color_').val(table.row(this).data()[3]);
        $('#placa_').val(table.row(this).data()[4]);
        $('#pais_pro_').val(table.row(this).data()[11]).trigger('change');
        $('#dep_pro_').val(table.row(this).data()[12]).trigger('change');
        $('#mun_pro_').val(table.row(this).data()[13]).trigger('change');
        $('#pais_des_').val(table.row(this).data()[14]).trigger('change');
        $('#dep_des_').val(table.row(this).data()[15]).trigger('change');
        $('#mun_des_').val(table.row(this).data()[16]).trigger('change');
        @can('editar decomisos de transportes')
        $('#form_edit_transporte').attr('action', "/decomisotransporte/" + table.row(this).data()[17]);
        @endcan
        @can('borrar decomisos de transportes')
        $('#form_delete_transporte').attr('action', "/decomisotransporte/" + table.row(this).data()[17]);
        @endcan
        $('#editTransporteModal').modal('toggle');

        //$('#pais_pro_, #dep_pro_, #mun_pro_, #pais_des_, #dep_des_, #mun_des_').trigger('change');
        //$('#pais_pro_').trigger('change');

        if(table.row(this).data()[18]==null){
            @can('ver decomisos de transportes deshabilitados')
            $('#restaurar_trans').hide();
            @endcan
            @can('editar decomisos de transportes')
            $('#transporte-b2').show();
            @endcan
            @can('borrar decomisos de transportes')
            $('#transporte-borrar').show();
            @endcan
        }
        else{
            @can('ver decomisos de transportes deshabilitados')
            $('#restaurar_trans').show();
            @endcan
            @can('editar decomisos de transportes')
            $('#transporte-b2').hide();
            @endcan
            @can('borrar decomisos de transportes')
            $('#transporte-borrar').hide();
            @endcan
            @can('ver decomisos de transportes deshabilitados')
            $('#form_restore_transporte').attr('action', "{{route('decomisotransporte.restaurar','')}}/"+table.row(this).data()[17] );
            @endcan
        }
    }

});
@can('ver decomisos de transportes deshabilitados')
$('#desabi_transporte').change(function() {
    if (this.checked) {
        $('#transporte-table').DataTable().clear().draw();
        registros_tra("habilitados");
    } else {
        $('#transporte-table').DataTable().clear().draw();
        registros_tra("dehabilitados");
    }
});
@endcan



function registros_tra(estado){
    //alert("llega");
    var url="{{route('decomisotransporte.habilitados',['Id' => 'idd', 'tipo' => 'tip'])}}",
        url=url.replace('idd', @json($decomiso->id));
        url=url.replace('tip', estado);

    $.ajax({
        url:url,
        type:'get',
        dataType:'json',
        success: function (response) {
            //console.log(response.decomisos_habilitados);
            response.decomisos_habilitados.forEach(element=>{

                $('#transporte-table').DataTable().row.add([
                    element.tipo,
                    element.marca,
                    element.modelo,
                    element.color,
                    element.placa,
                    element.pais_pro,
                    element.dep_pro,
                    element.mun_pro,
                    element.pais_des,
                    element.dep_des,
                    element.mun_des,

                    element.pais_pro_id,
                    element.dep_pro_id,
                    element.mun_pro_id,
                    element.pais_des_id,
                    element.dep_des_id,
                    element.mun_des_id,
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


registros_tra("habilitados");
</script>
@endpush