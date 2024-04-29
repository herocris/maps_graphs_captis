<div class="collapse multi-collapse" id="multiCollapseExample3">

    <div class="col-sm-12">
        <div class="form-group" id="nombre_arma">
            <label>Armas</label>
            {{--  @include('admin.partials.nombres_de_armas' ,['tipo' => 'checkbox'])  --}}
            <div id="nom_armas" required></div>
        </div>
    </div>

</div>
{{-- - -------------------------------------------}}
@push('scripts')
<script>

function validar_arma(){
    var res=false;
    $('#fecha_ini').parsley().validate();
    $('#fecha_fin').parsley().validate();
    $('#nom_armas').parsley().validate();
    $('#institucion').parsley().validate();
    //$('#drogas_nombress2').parsley().validate();
    //$('#presentaciones_de_drogas2').parsley().validate();
    
    if($('#fecha_ini').parsley().validate()==true && $('#fecha_fin').parsley().validate()==true && $('#nom_armas').parsley().validate()==true){
        res=true;
        //alert("valida");
    }
    return res;
}

$('#guardado').click(function(e){
    if($('input[name="tipo_decomiso"]:checked').val()=="Arma"){
        if(validar_arma()){   
            $('#spinner-div').show(); 
            $.ajax({
                url:'/grafica_arma',
                data:{
                    fecha_ini:$('#fecha_ini').val(),
                    fecha_fin:$('#fecha_fin').val(),
                    tipo_tiempo:$('#periodos').val(),
                    tipo_decomiso:$('input[name="tipo_decomiso"]:checked').val(),
                    //armas:arm_prec
                    grafica:$('input[name="tipo_grafica"]:checked').val(),

                    armas:$('#nom_armas').val(),
                    parametro2:$("#cantpor").is(':checked')==true?"porcentaje":"cantidad",
                    instituciones:$('#institucion').val(),
                    
                    },
                type:'get',
                success: function (response) {

                    reset_graph($('input[name="tipo_grafica"]:checked').val());
                    myChart['options']['plugins']['title']['text']= "Armas";

                    myChart.options.scales = {
                        y: {
                            title: {
                                display: true,
                                text: "Unidades",
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
                                text: "Unidades",
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
                    }else if($('input[name="tipo_grafica"]:checked').val()=='pie' || $('input[name="tipo_grafica"]:checked').val()=='doughnut'){
                        myChart.options.scales = '';
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

var armas_select=[];
@json($arm_noms).forEach(element=>{
    armas_select.push({ label: element.descripcion, value: element.id, alias: 'custom label for search' });
});

$("input[name='tipo_decomiso']").change(function(){
    $('#nom_armas').parsley().reset();
    if($('input[name="tipo_decomiso"]:checked').val()=="Arma"){
        $("#multiCollapseExample3").collapse('show');
        $("#criterio_armas").show();
        virtual_select_ini(armas_select, true, '#nom_armas', 'Selecciona armas');
    }else{
        $("#multiCollapseExample3").collapse('hide');
        $("#criterio_armas").hide();   
    }
});
</script>
@endpush