<div class="modal fade" id="editTransporteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar transporte</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form-horizontal" data-parsley-validate method="POST" id="form_edit_transporte">

                <div class="modal-body">
                    <input id="transporteOcultoId_" name="transporteOcultoId_" type="hidden" value="">
                    <input name="_token" value="{{ csrf_token() }}" type="hidden">
                    <input name="_method" value="PUT" type="hidden">
                    <input id="transporte_decomiso_id_" name="transporte_decomiso_id_" type="hidden" value={{$decomiso->id}}>

                    <div class="form-group row" id="tip_tr_div_">
                        <label for="tipo_transporte_" class="col-sm-3 col-form-label">Tipo de transporte</label>
                        <div class="col-sm-9">
                            <select class="form-select" name="tipo_transporte_" id="tipo_transporte_">
                                {{--  <option value="" {{ old('tipo_transporte_') == null ? ' selected' : '' }} disabled hidden>Selecciona el tipo de transporte</option>  --}}
                                <option value="Terrestre" {{ old('tipo_transporte_') == "Terrestre" ? ' selected' : '' }}>Terrestre</option>
                                <option value="Maritimo" {{ old('tipo_transporte_') == "Maritimo" ? ' selected' : '' }}>Maritimo</option>
                                <option value="Aereo" {{ old('tipo_transporte_') == "Aereo" ? ' selected' : '' }}>Aereo</option>
                            </select>
                            @include('admin.partials.mensages_error', ['nombre' => 'tipo_transporte_'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="marca_" class="col-sm-3 col-form-label">Marca</label>
                        <div class="col-sm-9">
                            <input type="text" name="marca_" class="form-control" id="marca_" value="{{old('marca_')}}"
                                placeholder="Marca" autocomplete="off">
                            @include('admin.partials.mensages_error', ['nombre' => 'marca_'])
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label for="modelo_" class="col-sm-3 col-form-label">Modelo</label>
                        <div class="col-sm-9">
                            <input type="text" name="modelo_" class="form-control" id="modelo_" value="{{old('modelo_')}}"
                                placeholder="Modelo" autocomplete="off">
                            @include('admin.partials.mensages_error', ['nombre' => 'modelo_'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="color_" class="col-sm-3 col-form-label">Color</label>
                        <div class="col-sm-9">
                            <input type="text" required name="color_" class="form-control" id="color_" value="{{old('color_')}}"
                                placeholder="Color" autocomplete="off">
                            @include('admin.partials.mensages_error', ['nombre' => 'color_'])
                        </div>
                    </div>
                    

                    <div class="form-group row" id="placa">
                        <label for="placa_" class="col-sm-3 col-form-label">Placa</label>
                        <div class="col-sm-9">
                            <input type="text" name="placa_" class="form-control" id="placa_" value="{{old('placa_')}}"
                                placeholder="Placa" autocomplete="off">
                            @include('admin.partials.mensages_error', ['nombre' => 'placa_'])
                        </div>
                    </div>
                    <div class="form-group row" id="pais_pro_div_">
                        <label for="pais_pro_" class="col-sm-4 col-form-label">País de procedencia</label>
                        <div class="col-sm-8">
                            <select class="form-select" name="pais_pro_" id="pais_pro_" value="{{old('pais_pro_')}}">
                                @foreach($paises as $pais)
                                {{--  <option value="" {{ old('pais_pro_') == null ? ' selected' : '' }} disabled hidden>Selecciona el país</option>  --}}
                                <option value={{$pais->id}} {{ old('pais_pro_') == $pais->id ? ' selected' : '' }}>{{$pais->nombre}}</option>
                                @endforeach
                            </select>
                            @include('admin.partials.mensages_error', ['nombre' => 'pais_pro_'])
                        </div>
                    </div>
                    <div class="form-group row" id="departa_pro_" style="display:none;">
                        <label for="dep_pro_" class="col-sm-6 col-form-label">Departamento de procedencia</label>
                        <div class="col-sm-6">
                            <select class="form-select" data-placeholder="Selecciona departamento" name="dep_pro_" id="dep_pro_" value="{{old('dep_pro_')}}">
                               
                                {{--  <option value="" {{ old('dep_pro_') == null ? ' selected' : '' }} disabled hidden>Selecciona el departamento4</option>  --}}
                                
                            </select>
                            @include('admin.partials.mensages_error', ['nombre' => 'dep_pro_'])
                        </div>
                    </div>
                    <div class="form-group row" id="municip_pro_" style="display:none;">
                        <label for="mun_pro_" class="col-sm-6 col-form-label">Municipio de procedencia</label>
                        <div class="col-sm-6">
                            <select class="form-select" data-placeholder="Selecciona municipio" name="mun_pro_" id="mun_pro_" value="{{old('mun_pro_')}}">
                                {{--  <option value="" {{ old('mun_pro_') == null ? ' selected' : '' }} disabled hidden>Selecciona el municipio</option>  --}}
                            </select>
                            @include('admin.partials.mensages_error', ['nombre' => 'mun_pro_'])
                        </div>
                    </div>
                    <div class="form-group row" id="pais_des_div_">
                        <label for="pais_des_" class="col-sm-4 col-form-label">País de destino</label>
                        <div class="col-sm-8">
                            <select class="form-select" name="pais_des_" id="pais_des_" value="{{old('pais_des_')}}">
                                @foreach($paises as $pais)
                                {{--  <option value="" {{ old('pais_des_') == null ? ' selected' : '' }} disabled hidden>Selecciona el país</option>  --}}
                                <option value={{$pais->id}} {{ old('pais_des_') == $pais->id ? ' selected' : '' }}>{{$pais->nombre}}</option>
                                @endforeach
                            </select>
                            @include('admin.partials.mensages_error', ['nombre' => 'pais_des_'])
                        </div>
                    </div>
                    
                    <div class="form-group row" id="departa_des_" style="display:none;">
                        <label for="dep_des_" class="col-sm-6 col-form-label">Departamento de destino</label>
                        <div class="col-sm-6">
                            <select class="form-select" data-placeholder="Selecciona departamento" name="dep_des_" id="dep_des_" value="{{old('dep_des_')}}">
                                
                                {{--  <option value="" {{ old('dep_des_') == null ? ' selected' : '' }} disabled hidden>Selecciona el departamento</option>  --}}
                                
                            </select>
                            @include('admin.partials.mensages_error', ['nombre' => 'dep_des_'])
                        </div>
                    </div>
                    
                    <div class="form-group row" id="municip_des_" style="display:none;">
                        <label for="mun_des_" class="col-sm-6 col-form-label">Municipio de destino</label>
                        <div class="col-sm-6">
                            <select class="form-select" data-placeholder="Selecciona municipio" name="mun_des_" id="mun_des_" value="{{old('mun_des_')}}">
                                {{--  <option value="" {{ old('mun_des_') == null ? ' selected' : '' }} disabled hidden>Selecciona el municipio</option>  --}}
                            </select>
                            @include('admin.partials.mensages_error', ['nombre' => 'mun_des_'])
                        </div>
                    </div>
 

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    @can('editar decomisos de transportes')
                    <button type="submit" class="btn btn-primary" id="transporte-b2">Editar</button>
                    @endcan
                    </form>
                    @can('borrar decomisos de transportes')
                    <form method="POST" style="display: inline;" id="form_delete_transporte">
                            {{csrf_field()}} {{method_field('DELETE')}}
                            <button type="submit" class="btn  btn-danger" id="transporte-borrar" onclick="return confirm('¿Borrar?');">Borrar</button>
                            
                    </form>
                    @endcan
                    @can('ver decomisos de transportes deshabilitados')
                    <form method="GET" style="display: inline;" id="form_restore_transporte">
                        {{csrf_field()}}
                        <button type="submit" id="restaurar_trans" class="btn btn-success">Restaurar</button>
                    </form>
                    @endcan
                </div>
            
        </div>
    </div>
</div>
@push('scripts')
<script>
$('#tipo_transporte_').select2({
    dropdownParent: $("#tip_tr_div_"),
    theme: 'bootstrap4',
});
$('#pais_pro_').select2({
    dropdownParent: $("#pais_pro_div_"),
    theme: 'bootstrap4',
});
$('#dep_pro_').select2({
    dropdownParent: $("#departa_pro_"),
    theme: 'bootstrap4',
});
$('#mun_pro_').select2({
    dropdownParent: $("#municip_pro_"),
    theme: 'bootstrap4',
});
$('#pais_des_').select2({
    dropdownParent: $("#pais_des_div_"),
    theme: 'bootstrap4',
});
$('#dep_des_').select2({
    dropdownParent: $("#departa_des_"),
    theme: 'bootstrap4',
});
$('#mun_des_').select2({
    dropdownParent: $("#municip_des_"),
    theme: 'bootstrap4',
});
    
$('#transporte-b2').click(function(e){
    if($('#pais_pro_').val()==87){
        $('#pais_pro_').attr("required","");
        $('#pais_pro_').parsley().validate();
        $("#dep_pro_").attr("required","");
        $('#dep_pro_').parsley().validate();
        $("#mun_pro_").attr("required","");
        $('#mun_pro_').parsley().validate();
    }else{
        $("#pais_pro_").removeAttr("required");
        $("#dep_pro_").removeAttr("required");
        $("#mun_pro_").removeAttr("required");
    }

    if($('#pais_des_').val()==87){
        $('#pais_des_').attr("required","");
        $('#pais_des_').parsley().validate();
        $("#dep_des_").attr("required","");
        $('#dep_des_').parsley().validate();
        $("#mun_des_").attr("required","");
        $('#mun_des_').parsley().validate();
    }else{
        $("#pais_des_").removeAttr("required");
        $("#dep_des_").removeAttr("required");
        $("#mun_des_").removeAttr("required");
    }
});
//////////////////////////procedencia edit


$("#pais_pro_").on('change', function () {
    llenarDepartamentos_($("#pais_pro_").val());
    if ($("#pais_pro_").val() == 87) {
        $("#departa_pro_").show();
        $("#municip_pro_").show();
    } else {
        $("#departa_pro_").hide();
        $("#municip_pro_").hide();
    }
});

function llenarDepartamentos_(id) {
    //alert("utut");

    $("#dep_pro_").empty();
    //$("#dep_pro_").append('<option value="" {{ old('dep_pro_') == null ? ' selected' : '' }} disabled hidden>Selecciona el departamento</option>');
    @json($departamentos).forEach(function (item) {
        if (item.pais_id == id) {
            $("#dep_pro_").append('<option value="' + item.id + '" {{ old('dep_pro_') == "' + item.id + '" ? ' selected' : '' }}>' + item.nombre + '</option>' +
                '<option value="" {{ old('dep_pro_') == null ? ' selected' : '' }} disabled hidden>Selecciona el departamento</option>');

            //$("#dep_pro_").append('<option value="' + item.id + '" {{ old('dep_pro_') == "' + item.id + '" ? ' selected' : '' }}>' + item.nombre + '</option>');
        } else {

            //$("#dep_pro_").attr("data-placeholder:'Selecciona arasma");
            $("#dep_pro_").append('<option value="" {{ old('dep_pro_') == null ? ' selected' : '' }} disabled hidden>Selecciona el departamento</option>');
            $("#mun_pro_").empty();
            $("#mun_pro_").append('<option value="" {{ old('mun_pro_') == null ? ' selected' : '' }} disabled hidden>Selecciona el municipio</option>');
        }
    });
}


$("#dep_pro_").on('change', function () {
    llenarMunicipios_($("#dep_pro_").val());

});

function llenarMunicipios_(id) {
    //alert(id);
    $("#mun_pro_").empty();
    @json($municipios).forEach(function (item) {
        if (item.departamento_id == id) {
            $("#mun_pro_").append('<option value="' + item.id + '" {{ old('mun_pro_') == "' + item.id + '" ? ' selected' : '' }}>' + item.nombre + '</option>' +
                '<option value="" {{ old('mun_pro_') == null ? ' selected' : '' }} disabled hidden>Selecciona el municipio</option>');
            //$("#mun_pro_").append('<option value="' + item.id + '" {{ old('mun_pro_') == "' + item.id + '" ? ' selected' : '' }}>' + item.nombre + '</option>');
        } else {
            $("#mun_pro_").append('<option value="" {{ old('mun_pro_') == null ? ' selected' : '' }} disabled hidden>Selecciona el municipio</option>');
        }
    });
}

@if (old('pais_pro_') == 87)
    llenarDepartamentos_({{ old('pais_pro_') }});
llenarMunicipios_({{ old('dep_pro_') }});
$("#dep_pro_").val({{ old('dep_pro_') }});
$('#departa_pro_').show();
$('#municip_pro_').show();
@endif
@if (old('mun_pro_'))
    llenarMunicipios_({{ old('dep_pro_') }});
$("#mun_pro_").val({{ old('mun_pro_') }});
$('#municip_pro_').show();
@endif


$("#pais_des_").on('change', function () {
    llenarDepartamentos2_($("#pais_des_").val());
    if ($("#pais_des_").val() == 87) {
        $("#departa_des_").show();
        $("#municip_des_").show();
    } else {
        $("#departa_des_").hide();
        $("#municip_des_").hide();
    }
});

function llenarDepartamentos2_(id) {
    $("#dep_des_").empty();
    @json($departamentos).forEach(function (item) {
        if (item.pais_id == id) {
            //alert("aquie");
            $("#dep_des_").append('<option value="' + item.id + '" {{ old('dep_des_') == "' + item.id + '" ? ' selected' : '' }}>' + item.nombre + '</option>' +
                '<option value="" {{ old('dep_des_') == null ? ' selected' : '' }} disabled hidden>Selecciona el departamento</option>');
        } else {
            $("#dep_des_").append('<option value="" {{ old('dep_des_') == null ? ' selected' : '' }} disabled hidden>Selecciona el departamento</option>');
            $("#mun_des_").empty();
            $("#mun_des_").append('<option value="" {{ old('mun_des_') == null ? ' selected' : '' }} disabled hidden>Selecciona el municipio</option>');

        }
    });
}

$("#dep_des_").on('change', function () {
    llenarMunicipios2_($("#dep_des_").val());
});

function llenarMunicipios2_(id) {
    $("#mun_des_").empty();
    @json($municipios).forEach(function (item) {
        if (item.departamento_id == id) {
            $("#mun_des_").append('<option value="' + item.id + '" {{ old('mun_des_') == "' + item.id + '" ? ' selected' : '' }}>' + item.nombre + '</option>' +
                '<option value="" {{ old('mun_des_') == null ? ' selected' : '' }} disabled hidden>Selecciona el municipio</option>');
        } else {
            $("#mun_des_").append('<option value="" {{ old('mun_des_') == null ? ' selected' : '' }} disabled hidden>Selecciona el municipio</option>');
        }
    });
}

@if (old('pais_des_') == 87)
    llenarDepartamentos2_({{ old('pais_des_') }});
llenarMunicipios2_({{ old('dep_des_') }});
$("#dep_des_").val({{ old('dep_des_') }});
$('#departa_des_').show();
$('#municip_des_').show();
@endif
@if (old('mun_des_'))
    llenarMunicipios2_({{ old('dep_des_') }});
$("#mun_des_").val({{ old('mun_des_') }});
$('#municip_des_').show();
@endif
</script>
@endpush