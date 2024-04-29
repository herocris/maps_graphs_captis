<div class="collapse multi-collapse" id="multiCollapseExample1">
    <label>Criterio</label>
    <div class="checkbox">

        <input name="tipo_gr" type="radio" id="tipo_gr" value="presentaciones" required>
        Presentaciones por droga
        <br>
        <input name="tipo_gr" type="radio" id="tipo_gr" value="drogas" required>
        Drogas por presentación
        <br>
        <input name="tipo_gr" type="radio" id="tipo_gr" value="comp_drogas" required>
        Comparativo de drogas
        <div class="invalid-feedback">Debes seleccionar una opción.</div>

    </div>
    {{--  <label for="tipo_tiempo2" class="col-sm-5 col-form-label">Criterio</label>
    <div class="form-group row">
        <input type="checkbox" id="tipo_gr" data-size="xs" unchecked data-toggle="toggle" data-on='Drogas por presentación' data-off='Presentaciones por droga' data-onstyle="primary" data-offstyle="info">
    </div>  --}}
    <br>
    <div class="form-group row">
        <div class="col-sm-12">
            <div class="form-group" id="nombre_droga" style="display:none;">
                <label>Drogas</label>
                {{--  @include('admin.partials.nombres_de_drogas' ,['tipo' => 'radio'])  --}}
                <div id="drogas_nombress2" required></div>
            </div>
            {{--  <div class="form-group" id="nombre_droga2" style="display:none;">
                <label>Drogas</label>
                @include('admin.partials.nombres_de_drogas' ,['tipo' => 'checkbox'])
                <span style="color:red">
                    <strong id="dro_valid2" style="display:none;">"Selecciona una opción"</strong>
                </span>
            </div>  --}}
            <div class="form-group" id="presentacion_droga" style="display:none;">
                <label>Presentaciones</label>
                {{--  @include('admin.partials.nombres_de_presentaciones_de_droga' ,['tipo' => 'checkbox'])  --}}
                <div id="presentaciones_de_drogas2" required></div>
            </div>
        </div>
    </div>

    <label for="parametro" class="col-sm-5 col-form-label">Magnitud</label>
    <input type="checkbox" id="parametro" unchecked data-toggle="toggle" data-size="sm"
        data-on='<i class="fas fa-boxes" data-toggle="tooltip" data-placement="right" title="Unidades"></i>'
        data-off='<i class="fas fa-weight-hanging" data-toggle="tooltip" data-placement="right" title="Peso"></i>'
        data-onstyle="primary" data-offstyle="info">
</div>
{{-- ----------------------------------------------------------------- --}}
@push('scripts')
<script>
var drog=[];
var pre_dro=[];

$("#drogas_nombress2").change('click', function(){
    console.log("funcion1");
    drog=[];
    if(Array.isArray($('#drogas_nombress2').val())){
        drog=$('#drogas_nombress2').val();
        titulo_de_grafica=$('#drogas_nombress2').val();
    }else{
        drog.push($('#drogas_nombress2').val());
    }
    $('#drogas_nombress2').parsley().reset();
});

$("#presentaciones_de_drogas2").change('click', function(){
    console.log("funcion2");
    pre_dro=[];
    if(Array.isArray($('#presentaciones_de_drogas2').val())){
        pre_dro=$('#presentaciones_de_drogas2').val();
        titulo_de_grafica=$('#presentaciones_de_drogas2').val();
    }else{
        pre_dro.push($('#presentaciones_de_drogas2').val());
    }
    $('#presentaciones_de_drogas2').parsley().reset();
});

function validar_droga(){
    console.log("validando droga");
    var res=false;
    $('#fecha_ini').parsley().validate();
    $('#fecha_fin').parsley().validate();
    $('#tipo_gr').parsley().validate();
    $('#institucion').parsley().validate();
    if($("input[name='tipo_gr']:checked").val()!="comp_drogas"){
       $('#drogas_nombress2').parsley().validate();
       $('#presentaciones_de_drogas2').parsley().validate();
    }else{
        $('#drogas_nombress2').parsley().validate();
    }
    if($("input[name='tipo_gr']:checked").val()!="comp_drogas"){
       if($('#fecha_ini').parsley().validate()==true && $('#fecha_fin').parsley().validate()==true && $('#tipo_gr').parsley().validate()==true && $('#drogas_nombress2').parsley().validate()==true && $('#presentaciones_de_drogas2').parsley().validate()==true){
           res=true;
       }
    }else{
        if($('#fecha_ini').parsley().validate()==true && $('#fecha_fin').parsley().validate()==true && $('#tipo_gr').parsley().validate()==true && $('#drogas_nombress2').parsley().validate()==true){
           res=true;
       }
    }
    return res;
}

$('#guardado').click(function(e){
    if($('input[name="tipo_decomiso"]:checked').val()=="Droga"){
        if(validar_droga()){
            console.log("generando grafica de droga");
            $('#spinner-div').show();
            $.ajax({
                url:'/grafica_drogas',
                //url:'/grafica_comp_drogas',
                data:{
                    fecha_ini:$('#fecha_ini').val(),
                    fecha_fin:$('#fecha_fin').val(),
                    tipo_tiempo:$('#periodos').val(),
                    tipo_decomiso:$('input[name="tipo_decomiso"]:checked').val(),
                    tipo_gr:$('input[name="tipo_gr"]:checked').val(),
                    //tipo_gr:$("#tipo_tiempo").is(':checked')==true?"presentaciones":"drogas",
                    drogas:drog,
                    pres_drogas:pre_dro,
                    grafica:$('input[name="tipo_grafica"]:checked').val(),
                    parametro:$("#parametro").is(':checked')==true?"cantidad":"peso",
                    parametro2:$("#cantpor").is(':checked')==true?"porcentaje":"cantidad",
                    instituciones:$('#institucion').val(),

                    },
                type:'get',
                success: function (response) {
                            //var periodo=response.periodo;
                            //var decomisos=response.decomisos;
                            reset_graph($('input[name="tipo_grafica"]:checked').val());
                            myChart['options']['plugins']['title']['text']= response.nombre_prese;
                            console.log(response);

                            myChart.options.scales = {
                                y: {
                                        title: {
                                            display: true,
                                            text: unidades_de_grafica,
                                            font: {
                                                size: 20,
                                                weight: "bold"
                                            }
                                        }
                                    }
                                };
                            if($("#cantpor").is(':checked')){
                                myChart.options.scales.y = {
                                    title: {
                                        display: true,
                                        text: unidades_de_grafica,
                                        font: {
                                            size: 20,
                                            weight: "bold"
                                        }
                                    },
                                    ticks: {
                                        min: 0,
                                        max: this.max,// Your absolute max value
                                        callback: function (value) {
                                          return (value / this.max * 100).toFixed(0) + '%'; // convert it to percentage
                                        },
                                    },
                                }
                            }
                            //myChart.update();
                            if($('input[name="tipo_grafica"]:checked').val()=='line'){
                                myChart.data=response.otros_datos;
                                myChart.update();
                            }else if($('input[name="tipo_grafica"]:checked').val()=='bar'){
                                myChart.data=response.otros_datos;
                                myChart.update();
                            }else if($('input[name="tipo_grafica"]:checked').val()=='pie' || $('input[name="tipo_grafica"]:checked').val()=='doughnut'){
                                myChart.options.scales ='';
                                myChart.data=response.otros_datos;
                                myChart.update();
                            }
                            //myChart.update();
                            console.log(myChart.data);
                            console.log(response.otros_datos);
                },
                statusCode: {
                    404: function() {
                        alert('web not found');
                    }
                },
                complete: function () {
                    $('#spinner-div').hide();//Request is complete so hide spinner
                }

            });
        }
    }
});

$("input[name='tipo_gr']").change(function(){
    cambio_categoria2();
});

$("#parametro").change(function(){
    unidades_de_grafica=$("#parametro").is(':checked')==true?"Unidades":"Kilogramos";
});

var drogas_select=[];
@json($dro_noms).forEach(element=>{
    drogas_select.push({ label: element.descripcion, value: element.id, alias: 'custom label for search' });
});


var preset_drog_select=[];
@json($dro_pres).forEach(element=>{
    preset_drog_select.push({ label: element.descripcion, value: element.id, alias: 'custom label for search' });
});

function cambio_categoria2(){
    if($("input[name='tipo_gr']:checked").val()=='presentaciones'){
        console.log("cambio_categoria2 presentaciones");

        $("#nombre_droga").show();
        $("#presentacion_droga").show();

        virtual_select_ini(drogas_select, false, '#periodo', 'Selecciona droga');
        virtual_select_ini(drogas_select, false, '#drogas_nombress2', 'Selecciona droga');
        virtual_select_ini(preset_drog_select, true, '#presentaciones_de_drogas2', 'Selecciona presentaciones');

        $('#drogas_nombress2').parsley().reset();
        $('#presentaciones_de_drogas2').parsley().reset();

    }else if($("input[name='tipo_gr']:checked").val()=='drogas'){
        console.log("cambio_categoria2 drogas");

        virtual_select_ini(drogas_select, true, '#drogas_nombress2', 'Selecciona drogas');
        virtual_select_ini(preset_drog_select, false, '#presentaciones_de_drogas2', 'Selecciona presentación');

        $('#drogas_nombress2').parsley().reset();
        $('#presentaciones_de_drogas2').parsley().reset();

        $("#nombre_droga").show();
        $("#presentacion_droga").show();
    }else{
        console.log("cambio_categoria2 solo drogas");
        virtual_select_ini(drogas_select, true, '#drogas_nombress2', 'Selecciona drogas');
        $("#nombre_droga").show();
        $("#presentacion_droga").hide();

        $('#drogas_nombress2').parsley().reset();
        $('#presentaciones_de_drogas2').parsley().reset();
    }
}

$("#multiCollapseExample1").collapse('show');

$("input[name='tipo_decomiso']").change(function(){
    if($('input[name="tipo_decomiso"]:checked').val()=="Droga"){
        //$("#presentacion_droga").show();
        $('#tipo_gr').parsley().reset();
        $('#drogas_nombress2').parsley().reset();
        $('#presentaciones_de_drogas2').parsley().reset();

        $("#multiCollapseExample1").collapse('show');
        $('#parametro').bootstrapToggle('off');
        unidades_de_grafica="Kilogramos";
        $("#parametro_droga").show();
        //$("#nombre_droga").show();
        $("#tipo_gr_").show();
        $("#parametro").val("");
        //$("#nombre_droga2").show();
    }else{
        $("#multiCollapseExample1").collapse('hide');
        $("#presentacion_droga2").hide();
        $("#nombre_droga2").hide();
        $("#presentacion_droga").hide();
        $("#nombre_droga").hide();
        $("#parametro_droga").hide();
        $("#tipo_gr_").hide();
        $("input[name='tipo_gr']").prop('checked',false);
        //$("#nombre_droga2").hide();

    }
});
</script>
@endpush
