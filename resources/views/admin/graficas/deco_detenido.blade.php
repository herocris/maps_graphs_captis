{{-----  detenidos  ----------------------------------------------------------}}
{{--  <label>Criterio</label>  --}}
{{--  <div class="checkbox">
    <input name="crit_dete" type="radio" value="genero">
    Por genero
</div>
<div class="checkbox">
    <input name="crit_dete" type="radio" value="estructura">
    Por estructura criminal
</div>
<span style="color:red">
    <strong id="crit_dro_valid" style="display:none;">"El criterio es requerido"</strong>
</span>
<br>  --}}
<div class="container">
    <div class="collapse multi-collapse" id="multiCollapseExample5">    
        <div class="form-group row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label>Género</label>
                    <div class="checkbox">
                        <input name="genero" id="genero_" required data-parsley-mincheck="1" type="checkbox" value="M">
                        Hombre
                        {{--  <br>  --}}
                        <input name="genero" id="genero_" required data-parsley-mincheck="1" type="checkbox" value="F">
                        Mujer
                    </div>
                </div> 
                <div class="form-group">
                    <label style="padding-bottom: 10%">Rango de edad</label>
                    <div class="range-example-1 col-sm-12" ></div>
                </div>
                <div class="form-group row">
                    <label>Estructura criminal</label>
                    {{--  @include('admin.partials.nombres_de_estructuras')  --}}
                    <div id="estructura_" required></div>
                </div>
            </div>
        </div> 
    </div>       
</div>

@push('scripts')
<link rel="stylesheet" href="/jquery_asRange_master/css/asRange.min.css">
<script src="/jquery_asRange_master/js/jquery-asRange.min.js"></script>
<script>

$(".range-example-1").asRange({
    range: true,
    limit: false,
    step:1,
    direction:'h',
});
$(".range-example-1").asRange('set',[0,100]);

var dete_gen=[];
$("input[name='genero']").on('click', function(){
    dete_gen=[];
    $("input[name='genero']:checked").each(function () {
        dete_gen.push($(this).val());
    });
    titulo_de_grafica="Detenidos";
});


function validar_detenido(){
    var res=false;
    $('#fecha_ini').parsley().validate();
    $('#fecha_fin').parsley().validate();
    $('#genero_').parsley().validate();
    $('#estructura_').parsley().validate();
    $('#institucion').parsley().validate();
    
    if($('#fecha_ini').parsley().validate()==true && $('#fecha_fin').parsley().validate()==true && $('#genero_').parsley().validate()==true && $('#estructura_').parsley().validate()==true){
        res=true;
    }
    return res;
}

$('#guardado').click(function(e){
    if($('input[name="tipo_decomiso"]:checked').val()=="Detenido"){
        if(validar_detenido()){
            $('#spinner-div').show();
            titulo_de_grafica="Detenidos";
            $.ajax({
                url:'/grafica_detenido',
                data:{
                    fecha_ini:$('#fecha_ini').val(),
                    fecha_fin:$('#fecha_fin').val(),
                    tipo_tiempo:$('#periodos').val(),
                    tipo_decomiso:$('input[name="tipo_decomiso"]:checked').val(),
                    grafica:$('input[name="tipo_grafica"]:checked').val(),
                    dete_gen:dete_gen,
                    dete_estr:$('#estructura_').val(),
                    parametro2:$("#cantpor").is(':checked')==true?"porcentaje":"cantidad",
                    rango_edad:$(".range-example-1").asRange('get'),
                    instituciones:$('#institucion').val(),
                    
                    },
                type:'get',
                success: function (response) {
                    reset_graph($('input[name="tipo_grafica"]:checked').val());                  

                    myChart.options.scales = {
                        y: {
                                title: {
                                    display: true,
                                    text: "Personas",
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
                                text: "Personas",
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
                    };
                    if($('input[name="tipo_grafica"]:checked').val()=='line'){
                        myChart.data=response.otros_datos;
                    }else if($('input[name="tipo_grafica"]:checked').val()=='bar'){
                        myChart.data=response.otros_datos;
                    }else if($('input[name="tipo_grafica"]:checked').val()=='pie' || $('input[name="tipo_grafica"]:checked').val()=='doughnut'){
                        myChart.options.scales ='';
                        myChart.data=response.otros_datos;                 
                    }
                    myChart.update();
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

var detenidos_select=[];
@json($estr_noms).forEach(element=>{
    detenidos_select.push({ label: element.descripcion, value: element.id, alias: 'custom label for search' });
});

$("input[name='tipo_decomiso']").change(function(){
    $('#genero_').parsley().reset();
    $('#estructura_').parsley().reset();
    $(".range-example-1").asRange('set',[0,100]);
    if($('input[name="tipo_decomiso"]:checked').val()=="Detenido"){
        $("#multiCollapseExample5").collapse('show');
        virtual_select_ini(detenidos_select, true, '#estructura_', 'Selecciona estructuras');
    }else{
        $("#multiCollapseExample5").collapse('hide'); 
        $("input[name='genero']").prop('checked',false);
        //$("input[name='estructura']").prop('checked',false);
    }
});
</script>
@endpush
