{{--------------------------- criterio de municiones --}}
<div class="collapse multi-collapse" id="multiCollapseExample4">
    <div class="col-sm-12">
        <div class="form-group" id="nombre_municion">
            <label>Municiones</label>
            {{--  @include('admin.partials.nombres_de_municiones' ,['tipo' => 'checkbox'])  --}}
            <div id="nom_mun" required></div>
        </div>
    </div>
</div>

@push('scripts')
<script>

function validar_municion(){
    var res=false;
    $('#fecha_ini').parsley().validate();
    $('#fecha_fin').parsley().validate();
    $('#nom_mun').parsley().validate();
    $('#institucion').parsley().validate();
    //$('#drogas_nombress2').parsley().validate();
    //$('#presentaciones_de_drogas2').parsley().validate();
    if($('#fecha_ini').parsley().validate()==true && $('#fecha_fin').parsley().validate()==true && $('#nom_mun').parsley().validate()==true){
        res=true;
    }
    return res;
}

$('#guardado').click(function(e){
    if($('input[name="tipo_decomiso"]:checked').val()=="Municion"){
        if(validar_municion()){  
            $('#spinner-div').show();  
            $.ajax({
                url:'/grafica_municion',
                data:{
                    fecha_ini:$('#fecha_ini').val(),
                    fecha_fin:$('#fecha_fin').val(),
                    tipo_tiempo:$('#periodos').val(),
                    tipo_decomiso:$('input[name="tipo_decomiso"]:checked').val(),
                    grafica:$('input[name="tipo_grafica"]:checked').val(),
                    municiones:$('#nom_mun').val(),
                    parametro2:$("#cantpor").is(':checked')==true?"porcentaje":"cantidad",
                    instituciones:$('#institucion').val(),
                    
                    },
                type:'get',
                success: function (response) {
                    reset_graph($('input[name="tipo_grafica"]:checked').val());                  
                    myChart['options']['plugins']['title']['text']= "Municiones";

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

var municiones_select=[];
@json($mun_noms).forEach(element=>{
    municiones_select.push({ label: element.descripcion, value: element.id, alias: 'custom label for search' });
});

$("input[name='tipo_decomiso']").change(function(){
    $('#nom_mun').parsley().reset();
    if($('input[name="tipo_decomiso"]:checked').val()=="Municion"){
        $("#multiCollapseExample4").collapse('show');
        $("#criterio_municiones").show();
        virtual_select_ini(municiones_select, true, '#nom_mun', 'Selecciona municiones');
    }else{
        $("#multiCollapseExample4").collapse('hide');
        $("#criterio_municiones").hide();   
    }
});
</script>
@endpush