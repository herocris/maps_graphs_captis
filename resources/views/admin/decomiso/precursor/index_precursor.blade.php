@can('ver decomisos de precursores deshabilitados')
<div class="nav-item ml-auto">
    Ver registros:
    <input type="checkbox" data-width="120" id="desabi_prec" data-size="sm" checked data-toggle="toggle" data-on="Habilitados" data-off="Deshabilitados" data-onstyle="success" data-offstyle="danger">
</div>
@endcan
@can('crear decomisos de precursores')
<a href="#" onclick="limpiar_precursor()" class="btn btn-success ml-auto" data-toggle="modal"
    data-target="#createPrecursorModal" data-toggle="tooltip" data-placement="bottom" title="Crear decomiso de precursor"><i class="fas fa-plus-circle"></i></a>
@endcan
<table id="precursor-table" class="cell-border display compact hover stripe" style="width:100%">
    <thead>
        <tr>
            <th>Descripción</th>
            <th>Presentación</th>
            <th>Cantidad</th>
            <th>Volumen (L)</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>
@push('scripts')
<script>
@include('admin.decomiso.base_tabla_subdecomiso')
base_tabla('#precursor-table', [4, 5, 6, 7], false);
//-------------------precursores
@can('ver decomisos de precursores deshabilitados')
$('#restaurar_prec').hide();
@endcan

$('#precursor-table tbody').on('click', 'td', function () {
    $('#form_edit_precursor').parsley().reset();
    var table = $('#precursor-table').DataTable();

    if (table.data().count() != 0) {
        $("#precursor_id_").val(table.row(this).data()[5]).trigger('change'); //setear select con texto y no con valor
        //alert($("#descripcion_droga").val());
        $('#presentacion_precursor_id_').val(table.row(this).data()[4]).trigger('change');
        $('#cantidadPrecursor_').val(table.row(this).data()[2]);
        $('#volumen_').val(table.row(this).data()[3]);
        $('#decomisoprecursorId').val(table.row(this).data()[6]);
        @can('editar decomisos de precursores')
        $('#form_edit_precursor').attr('action', "/decomisoprecursor/" + table.row(this).data()[6]);
        @endcan
        @can('borrar decomisos de precursores')
        $('#form_delete_precursor').attr('action', "/decomisoprecursor/" + table.row(this).data()[6]);
        @endcan
        $('#editPrecursorModal').modal('toggle');

        //$('.form-select').trigger('change');

        if(table.row(this).data()[7]==null){
            @can('ver decomisos de precursores deshabilitados')
            $('#restaurar_prec').hide();
            @endcan
            @can('editar decomisos de precursores')
            $('#precursor-b').show();
            @endcan
            @can('borrar decomisos de precursores')
            $('#precursor-borrar').show();
            @endcan
        }
        else{
            @can('ver decomisos de precursores deshabilitados')
            $('#restaurar_prec').show();
            @endcan
            @can('editar decomisos de precursores')
            $('#precursor-b').hide();
            @endcan
            @can('borrar decomisos de precursores')
            $('#precursor-borrar').hide();
            @endcan
            //$('#form_restore_droga').attr('action', "/decomisodrogaa/" + table.row(this).data()[6]);
            //var jds=table.row(this).data()[6];
            //var ruta="{{route('decomisodroga.restaurar','')}}/"+table.row(this).data()[6];
            //alert(ruta);
            @can('ver decomisos de precursores deshabilitados')
            $('#form_restore_precursor').attr('action', "{{route('decomisoprecursor.restaurar','')}}/"+table.row(this).data()[6] );
            @endcan
        }

    }
});

/////////////////////////////////llenar precursores///////////////////////////////////
@can('ver decomisos de precursores deshabilitados')
$('#desabi_prec').change(function() {
    if (this.checked) {
        $('#precursor-table').DataTable().clear().draw();
        registros_prec("habilitados");
    } else {
        $('#precursor-table').DataTable().clear().draw();
        registros_prec("dehabilitados");
    }
});
@endcan

var pres_precu=@json($presentacion_precursores);

function registros_prec(estado){
    //alert("llega");
    var url="{{route('decomisoprecursor.habilitados',['Id' => 'idd', 'tipo' => 'tip'])}}",
        url=url.replace('idd', @json($decomiso->id));
        url=url.replace('tip', estado);

    $.ajax({
        url:url,
        type:'get',
        dataType:'json',
        success: function (response) {
            response.decomisos_habilitados.forEach(element=>{
                var presenta = pres_precu.find(element1 => element1.id == element.pivot.presentacion_precursor_id);
                //console.log(presenta);
                $('#precursor-table').DataTable().row.add([
                    element.descripcion,
                    presenta.descripcion,
                    element.pivot.cantidad,
                    element.pivot.volumen,
                    element.pivot.presentacion_precursor_id,
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


registros_prec("habilitados");
</script>
@endpush