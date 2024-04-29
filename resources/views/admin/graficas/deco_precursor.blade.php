<div class="collapse multi-collapse" id="multiCollapseExample2">
    <label>Criterio</label>
    <div class="checkbox">
        <input name="crit_prec" id="crit_prec" type="radio" value="presentaciones" required>
        Presentaciones por precursor
    <br>
        <input name="crit_prec" id="crit_prec" type="radio" value="precursores" required>
        Precursores por presentación
    <br>
        <input name="crit_prec" id="crit_prec" type="radio" value="comp_precursores" required>
        Comparativo de precursores
    </div>
    
    <br>
    <div class="form-group row" id="criterios_prec">
        <div class="col-sm-12">
            <div class="form-group" id="nombre_precursor" style="display:none;">
                <label>Precursores</label>
                {{--  @include('admin.partials.nombres_de_precursores' ,['tipo' => 'checkbox'])  --}}
                <div id="pr_nom" required></div>
            </div>
            {{--  <div class="form-group" id="nombre_precursor2" style="display:none;">
                <label>Precursores</label>
                @include('admin.partials.nombres_de_precursores' ,['tipo' => 'radio'])
            </div>  --}}
        {{--  </div>

        <div class="col-sm-6">  --}}
            <div class="form-group" id="presentacion_precursores" style="display:none;">
                <label>Presentaciones</label>
                {{--  @include('admin.partials.nombres_de_presentaciones_de_precursores' ,['tipo' => 'checkbox'])  --}}
                <div id="pre_pr_nom" required></div>
            </div>
            {{--  <div class="form-group" id="presentacion_precursores2" style="display:none;">
                <label>Presentaciones de precursor</label>
                @include('admin.partials.nombres_de_presentaciones_de_precursores' ,['tipo' => 'radio'])
            </div>  --}}
        </div>
    </div>

    <label for="parametro_prec" class="col-sm-5 col-form-label">Magnitud</label>
    <input type="checkbox" id="parametro_prec" unchecked data-toggle="toggle" data-size="sm"
        data-on='<i class="fas fa-sort-amount-up-alt" data-toggle="tooltip" data-placement="right" title="Cantidad"></i>'
        data-off='<i class="fas fa-prescription-bottle" data-toggle="tooltip" data-placement="right" title="Volumen"></i>'
        data-onstyle="primary" data-offstyle="info">

</div>
{{-- - -------------------------------------------}}
@push('scripts')
<script>
var prec=[];
var pre_prec=[];

$("#pr_nom").change('click', function(){
    console.log("seleccion de precursor")
    prec=[];
    if(Array.isArray($('#pr_nom').val())){
        prec=$('#pr_nom').val();
        titulo_de_grafica=$('#pr_nom').val();
    }else{
        prec.push($('#pr_nom').val());
    }
    $('#pr_nom').parsley().reset();
});

$("#pre_pr_nom").change('click', function(){
    console.log("seleccion de presentacion precursor")
    pre_prec=[];
    if(Array.isArray($('#pre_pr_nom').val())){
        pre_prec=$('#pre_pr_nom').val();
        titulo_de_grafica=$('#pre_pr_nom').val();
    }else{
        pre_prec.push($('#pre_pr_nom').val());
    }
    $('#pre_pr_nom').parsley().reset();
});

function validar_precursor(){
    console.log("validando precursor");
    var res=false;
    $('#fecha_ini').parsley().validate();
    $('#fecha_fin').parsley().validate();
    $('#crit_prec').parsley().validate();
    $('#institucion').parsley().validate();
    if($("input[name='crit_prec']:checked").val()!="comp_precursores"){
        $('#pr_nom').parsley().validate();
        $('#pre_pr_nom').parsley().validate();
    }else{
        $('#pr_nom').parsley().validate();
    }
    if($("input[name='crit_prec']:checked").val()!="comp_precursores"){
        if($('#fecha_ini').parsley().validate()==true && $('#fecha_fin').parsley().validate()==true && $('#crit_prec').parsley().validate()==true && $('#pr_nom').parsley().validate()==true && $('#pre_pr_nom').parsley().validate()==true){
            res=true;
        }
    }else{
        if($('#fecha_ini').parsley().validate()==true && $('#fecha_fin').parsley().validate()==true && $('#crit_prec').parsley().validate()==true && $('#pr_nom').parsley().validate()==true){
            res=true;
        }
    }
    return res;
}

$('#guardado').click(function(e){
    if($('input[name="tipo_decomiso"]:checked').val()=="Precursor"){
        if(validar_precursor()){
            console.log("generando grafica de precursores");
            $('#spinner-div').show();
            $.ajax({
                url:'/grafica_precursores',
                data:{
                    fecha_ini:$('#fecha_ini').val(),
                    fecha_fin:$('#fecha_fin').val(),
                    tipo_tiempo:$('#periodos').val(),
                    tipo_decomiso:$('input[name="tipo_decomiso"]:checked').val(),
                    crit_prec:$('input[name="crit_prec"]:checked').val(),
                    precursores:prec,
                    pres_precursores:pre_prec,
                    
                    grafica:$('input[name="tipo_grafica"]:checked').val(),
                    parametro:$("#parametro_prec").is(':checked')==true?"cantidad":"volumen",
                    parametro2:$("#cantpor").is(':checked')==true?"porcentaje":"cantidad",
                    instituciones:$('#institucion').val(),
                    
                    },
                type:'get',
                success: function (response) {

                            //var periodo=response.periodo;
                            //var decomisos=response.decomisos;
                            reset_graph($('input[name="tipo_grafica"]:checked').val());
                            
                            myChart['options']['plugins']['title']['text']= response.nombre_prese;

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
                            if($('input[name="tipo_grafica"]:checked').val()=='line'){
                                myChart.data=response.otros_datos;
                            }else if($('input[name="tipo_grafica"]:checked').val()=='bar'){
                                myChart.data=response.otros_datos;
                            //myChart.update();
                            }else if($('input[name="tipo_grafica"]:checked').val()=='pie' || $('input[name="tipo_grafica"]:checked').val()=='doughnut'){
                                myChart.options.scales ='';
                                myChart.data=response.otros_datos;                     
                            }
                            
                            myChart.update();
                            console.log("datos nuevos");
                            console.log(response.otros_datos);
                            console.log("datos viejos");
                            console.log(myChart.data);
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

$("#parametro_prec").change(function(){
    unidades_de_grafica=$("#parametro_prec").val()=="volumen"?"Litros":"Unidades";
});

var precursores_select=[];
@json($prec_noms).forEach(element=>{
    precursores_select.push({ label: element.descripcion, value: element.id, alias: 'custom label for search' });
});

var preset_precursores_select=[];
@json($prec_pres).forEach(element=>{
    preset_precursores_select.push({ label: element.descripcion, value: element.id, alias: 'custom label for search' });
});

$("input[name='tipo_decomiso']").change(function(){
        
    if($('input[name="tipo_decomiso"]:checked').val()=="Precursor"){
        $('#pr_nom').parsley().reset();
        $('#pre_pr_nom').parsley().reset();
        $('#crit_prec').parsley().reset();
        $("#multiCollapseExample2").collapse('show');
        $('#parametro_prec').bootstrapToggle('off');
        unidades_de_grafica="Litros";

        $("#criterio_precursores").show();
        $("#parametro_precursor").show();
        $("#parametro_prec").val("");
        
    }else{
        $("#multiCollapseExample2").collapse('hide');
        $("#criterio_precursores").hide();   
        $("#nombre_precursor").hide();
        $("#presentacion_precursores").hide();
        $("#parametro_precursor").hide();
        $("input[name='crit_prec']").prop('checked',false);
    }
});

$("input[name='crit_prec']").change(function(){
    console.log("selccion de decomiso de precursores");
    cambio_categoria2_();
});

function cambio_categoria2_(){
    if($("input[name='crit_prec']:checked").val()=='presentaciones'){
        console.log("presentaciones por precursor");
        $('#pr_nom').parsley().reset();
        $('#pre_pr_nom').parsley().reset();

        $("#nombre_precursor").show();
        $("#presentacion_precursores").show();

        virtual_select_ini(precursores_select, false, '#pr_nom', 'Selecciona precursor');
        virtual_select_ini(preset_precursores_select, true, '#pre_pr_nom', 'Selecciona presentaciones');
        
    }else if($("input[name='crit_prec']:checked").val()=='precursores'){
        console.log("precursores por presentacion");
        virtual_select_ini(precursores_select, true, '#pr_nom', 'Selecciona precursores');
        virtual_select_ini(preset_precursores_select, false, '#pre_pr_nom', 'Selecciona presentación precursor');

        $('#pr_nom').parsley().reset();
        $('#pre_pr_nom').parsley().reset();
        
        $("#nombre_precursor").show();
        $("#presentacion_precursores").show();
    }else{
        console.log("comparativo de precursores");
        virtual_select_ini(precursores_select, true, '#pr_nom', 'Selecciona precursores');

        $('#pr_nom').parsley().reset();
        
        $("#nombre_precursor").show();
        $("#presentacion_precursores").hide();
    }
}
</script>
@endpush