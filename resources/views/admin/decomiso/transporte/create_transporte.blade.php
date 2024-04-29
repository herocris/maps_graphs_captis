<div class="modal fade" id="createTransporteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agregar transporte</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form-horizontal" data-parsley-validate method="POST" action="{{route('decomisotransporte.store')}}" id="form_create_transporte">

                <div class="modal-body">
                    <input id="transporteOcultoId" name="transporteOcultoId" type="hidden" value="">
                    <input name="_token" value="{{ csrf_token() }}" type="hidden">
                    <input id="transporte_decomiso_id" name="transporte_decomiso_id" type="hidden" value={{$decomiso->id}}>

                    <div class="form-group row" id="tip_tr_div">
                        <label for="tipo_transporte" class="col-sm-3 col-form-label">Tipo de transporte</label>
                        <div class="col-sm-9">
                            <select required class="form-select" data-placeholder="Selecciona tipo" name="tipo_transporte" id="tipo_transporte">
                                <option value="" {{ old('tipo_transporte') == null ? ' selected' : '' }} disabled hidden>Selecciona el tipo de transporte</option>
                                <option value="Terrestre" {{ old('tipo_transporte') == "Terrestre" ? ' selected' : '' }}>Terrestre</option>
                                <option value="Maritimo" {{ old('tipo_transporte') == "Maritimo" ? ' selected' : '' }}>Maritimo</option>
                                <option value="Aereo" {{ old('tipo_transporte') == "Aereo" ? ' selected' : '' }}>Aereo</option>
                            </select>
                            @include('admin.partials.mensages_error', ['nombre' => 'tipo_transporte'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="marca" class="col-sm-3 col-form-label">Marca</label>
                        <div class="col-sm-9">
                            <input type="text" name="marca" class="form-control" id="marca" value="{{old('marca')}}"
                                placeholder="Marca" autocomplete="off">
                            @include('admin.partials.mensages_error', ['nombre' => 'marca'])
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label for="modelo" class="col-sm-3 col-form-label">Modelo</label>
                        <div class="col-sm-9">
                            <input type="text" name="modelo" class="form-control" id="modelo" value="{{old('modelo')}}"
                                placeholder="Modelo" autocomplete="off">
                            @include('admin.partials.mensages_error', ['nombre' => 'modelo'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="color" class="col-sm-3 col-form-label">Color</label>
                        <div class="col-sm-9">
                            <input required type="text" name="color" class="form-control" id="color" value="{{old('color')}}"
                                placeholder="Color" autocomplete="off">
                            @include('admin.partials.mensages_error', ['nombre' => 'color'])
                        </div>
                    </div>
                    

                    <div class="form-group row">
                        <label for="placa" class="col-sm-3 col-form-label">Placa</label>
                        <div class="col-sm-9">
                            <input type="text" name="placa" class="form-control" id="placa" value="{{old('placa')}}"
                                placeholder="Placa" autocomplete="off">
                            @include('admin.partials.mensages_error', ['nombre' => 'placa'])
                        </div>
                    </div>
                    <div class="form-group row" id="pais_pro_div">
                        <label for="pais_pro" class="col-sm-4 col-form-label">País de procedencia</label>
                        <div class="col-sm-8" >
                            <select class="form-select" data-placeholder="Selecciona país" name="pais_pro" id="pais_pro">
                                @foreach($paises as $pais)
                                <option value={{$pais->id}}{{$pais->id == 238 ? ' selected ' : '' }}>{{$pais->nombre}}</option>
                                
                                @endforeach
                            </select>
                            @include('admin.partials.mensages_error', ['nombre' => 'pais_pro'])
                        </div>
                    </div>
                    <div class="form-group row" id="departa_pro" style="display:none;">
                        <label for="dep_pro" class="col-sm-6 col-form-label">Departamento de procedencia</label>
                        <div class="col-sm-6">
                            <select class="form-select" data-placeholder="Selecciona departamento" name="dep_pro" id="dep_pro">
                               
                                <option value="" {{ old('dep_pro') == null ? ' selected' : '' }} disabled hidden>Selecciona el departamento</option>
                                
                            </select>
                            @include('admin.partials.mensages_error', ['nombre' => 'dep_pro'])
                        </div>
                    </div>
                    <div class="form-group row" id="municip_pro" style="display:none;">
                        <label for="mun_pro" class="col-sm-6 col-form-label">Municipio de procedencia</label>
                        <div class="col-sm-6">
                            <select class="form-select" data-placeholder="Selecciona municipio" name="mun_pro" id="mun_pro">
                                <option value="" {{ old('mun_pro') == null ? ' selected' : '' }} disabled hidden>Selecciona el municipio</option>
                            </select>
                            @include('admin.partials.mensages_error', ['nombre' => 'mun_pro'])
                        </div>
                    </div>
                    <div class="form-group row" id="pais_des_div">
                        <label for="pais_des" class="col-sm-4 col-form-label">País de destino</label>
                        <div class="col-sm-8">
                            <select class="form-select" data-placeholder="Selecciona país" name="pais_des" id="pais_des">
                                @foreach($paises as $pais)
                                <option value={{$pais->id}}{{$pais->id == 238 ? ' selected ' : '' }}>{{$pais->nombre}}</option>
                                @endforeach
                            </select>
                            @include('admin.partials.mensages_error', ['nombre' => 'pais_des'])
                        </div>
                    </div>
                    
                    <div class="form-group row" id="departa_des" style="display:none;">
                        <label for="dep_des" class="col-sm-6 col-form-label">Departamento de destino</label>
                        <div class="col-sm-6">
                            <select class="form-select" data-placeholder="Selecciona departamento" name="dep_des" id="dep_des">
                                
                                <option value="" {{ old('dep_des') == null ? ' selected' : '' }} disabled hidden>Selecciona el departamento</option>
                                
                            </select>
                            @include('admin.partials.mensages_error', ['nombre' => 'dep_des'])
                        </div>
                    </div>
                    
                    <div class="form-group row" id="municip_des" style="display:none;">
                        <label for="mun_des" class="col-sm-6 col-form-label">Municipio de destino</label>
                        <div class="col-sm-6">
                            <select class="form-select" data-placeholder="Selecciona municipio" name="mun_des" id="mun_des">
                                <option value="" {{ old('mun_des') == null ? ' selected' : '' }} disabled hidden>Selecciona el municipio</option>
                            </select>
                            @include('admin.partials.mensages_error', ['nombre' => 'mun_des'])
                        </div>
                    </div>
 

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary" id="transporte-b">Agregar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@push('scripts')
<script>
    function limpiar_transporte() {
        //limpiar_errores();
        //$('#tipo_transporte').val("");
        //$('#marca').val("");
        //$('#modelo').val("");
        //$('#color').val("");
        //$('#placa').val("");
        $('#pais_pro').val(238).change();
        //$('#dep_pro').val("");
        //$('#mun_pro').val("");
        $('#pais_des').val(238).change();
        //$('#dep_des').val("");
        //$('#mun_des').val("");

        $('#departa_pro').hide();
        $('#municip_pro').hide();
        $('#departa_des').hide();
        $('#municip_des').hide();
        
        $('#form_create_transporte').parsley().reset();
        
        document.getElementById("form_create_transporte").reset();
    }

    $('#tipo_transporte').select2({
        dropdownParent: $("#tip_tr_div"),
        theme: 'bootstrap4',
    });
    $('#pais_pro').select2({
        dropdownParent: $("#pais_pro_div"),
        theme: 'bootstrap4',
    });
    $('#dep_pro').select2({
        dropdownParent: $("#departa_pro"),
        theme: 'bootstrap4',
    });
    $('#mun_pro').select2({
        dropdownParent: $("#municip_pro"),
        theme: 'bootstrap4',
    });
    $('#pais_des').select2({
        dropdownParent: $("#pais_des_div"),
        theme: 'bootstrap4',
    });
    $('#dep_des').select2({
        dropdownParent: $("#departa_des"),
        theme: 'bootstrap4',
    });
    $('#mun_des').select2({
        dropdownParent: $("#municip_des"),
        theme: 'bootstrap4',
    });


    $('#transporte-b').click(function(e){
        if($('#pais_pro').val()==87){
            $('#pais_pro').attr("required","");
            $('#pais_pro').parsley().validate();
            $("#dep_pro").attr("required","");
            $('#dep_pro').parsley().validate();
            $("#mun_pro").attr("required","");
            $('#mun_pro').parsley().validate();
        }else{
            //$("#pais_pro").removeAttr("required");
            $("#dep_pro").removeAttr("required");
            $("#mun_pro").removeAttr("required");
        }
    
        if($('#pais_des').val()==87){
            $('#pais_des').attr("required","");
            $('#pais_des').parsley().validate();
            $("#dep_des").attr("required","");
            $('#dep_des').parsley().validate();
            $("#mun_des").attr("required","");
            $('#mun_des').parsley().validate();
        }else{
            //$("#pais_des").removeAttr("required");
            $("#dep_des").removeAttr("required");
            $("#mun_des").removeAttr("required");
        }
    });
    ///////////////////////////procedencia create
    $("#pais_pro").on('change', function () {
        llenarDepartamentos($("#pais_pro").val());
        if ($("#pais_pro").val() == 87) {
            $("#departa_pro").show();
            $("#municip_pro").show();
        } else {
            $("#departa_pro").hide();
            $("#municip_pro").hide();
        }
    });

    function llenarDepartamentos(id) {
        //alert("oop");
        $("#dep_pro").empty();
        @json($departamentos).forEach(function (item) {
            if (item.pais_id == id) {
                //$("#dep_pro").append('<option value="'+item.id+'">'+item.nombre+'</option>'+
                $("#dep_pro").append('<option value="' + item.id + '" {{ old('dep_pro') == "' + item.id + '" ? ' selected' : '' }}>' + item.nombre + '</option>' +
                    '<option value="" {{ old('dep_pro') == null ? ' selected' : '' }} disabled hidden>Selecciona el departamento</option>');
            } else {
                $("#dep_pro").append('<option value="" {{ old('dep_pro') == null ? ' selected' : '' }} disabled hidden>Selecciona el departamento</option>');
                $("#mun_pro").empty();
                $("#mun_pro").append('<option value="" {{ old('mun_pro') == null ? ' selected' : '' }} disabled hidden>Selecciona el municipio</option>');
            }
        });
    }



    $("#dep_pro").on('change', function () {
        llenarMunicipios($("#dep_pro").val());
    });

    function llenarMunicipios(id) {
        //alert(id);
        $("#mun_pro").empty();
        @json($municipios).forEach(function (item) {
            if (item.departamento_id == id) {
                $("#mun_pro").append('<option value="' + item.id + '" {{ old('mun_pro') == "' + item.id + '" ? ' selected' : '' }}>' + item.nombre + '</option>' +
                    '<option value="" {{ old('mun_pro') == null ? ' selected' : '' }} disabled hidden>Selecciona el municipio</option>');
            } else {
                $("#mun_pro").append('<option value="" {{ old('mun_pro') == null ? ' selected' : '' }} disabled hidden>Selecciona el municipio</option>');
            }
        });
    }

    @if (old('pais_pro') == 87)
        llenarDepartamentos({{ old('pais_pro') }});
    llenarMunicipios({{ old('dep_pro') }});
    $("#dep_pro").val({{ old('dep_pro') }});
    $('#departa_pro').show();
    $('#municip_pro').show();
    @endif
    @if (old('mun_pro'))
        llenarMunicipios({{ old('dep_pro') }});
    $("#mun_pro").val({{ old('mun_pro') }});
    $('#municip_pro').show();
    @endif



    $("#pais_des").on('change', function () {
        llenarDepartamentos2($("#pais_des").val());
        if ($("#pais_des").val() == 87) {
            $("#departa_des").show();
            $("#municip_des").show();
        } else {
            $("#departa_des").hide();
            $("#municip_des").hide();
        }
    });

    function llenarDepartamentos2(id) {
        $("#dep_des").empty();
        @json($departamentos).forEach(function (item) {
            if (item.pais_id == id) {
                //alert("aquie");
                $("#dep_des").append('<option value="' + item.id + '" {{ old('dep_des') == "' + item.id + '" ? ' selected' : '' }}>' + item.nombre + '</option>' +
                    '<option value="" {{ old('dep_des') == null ? ' selected' : '' }} disabled hidden>Selecciona el departamento</option>');
            } else {
                $("#dep_des").append('<option value="" {{ old('dep_des') == null ? ' selected' : '' }} disabled hidden>Selecciona el departamento</option>');
                $("#mun_des").empty();
                $("#mun_des").append('<option value="" {{ old('mun_des') == null ? ' selected' : '' }} disabled hidden>Selecciona el municipio</option>');

            }
        });
    }

    $("#dep_des").on('change', function () {
        llenarMunicipios2($("#dep_des").val());
    });

    function llenarMunicipios2(id) {
        //alert(id);
        $("#mun_des").empty();
        @json($municipios).forEach(function (item) {
            if (item.departamento_id == id) {
                $("#mun_des").append('<option value="' + item.id + '" {{ old('mun_des') == "' + item.id + '" ? ' selected' : '' }}>' + item.nombre + '</option>' +
                    '<option value="" {{ old('mun_des') == null ? ' selected' : '' }} disabled hidden>Selecciona el municipio</option>');
            } else {
                $("#mun_des").append('<option value="" {{ old('mun_des') == null ? ' selected' : '' }} disabled hidden>Selecciona el municipio</option>');
            }
        });
    }

    @if (old('pais_des') == 87)
        llenarDepartamentos2({{ old('pais_des') }});
    llenarMunicipios2({{ old('dep_des') }});
    $("#dep_des").val({{ old('dep_des') }});
    $('#departa_des').show();
    $('#municip_des').show();
    @endif
    @if (old('mun_des'))
        llenarMunicipios2({{ old('dep_des') }});
    $("#mun_des").val({{ old('mun_des') }});
    $('#municip_des').show();
    @endif
    
     
</script>
@endpush