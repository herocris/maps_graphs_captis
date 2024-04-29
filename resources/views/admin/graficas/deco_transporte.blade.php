{{-----  transporte ----------------------------------------------------------}}
<div class="container">
    <div class="collapse multi-collapse" id="multiCollapseExample6">    
        <div class="form-group row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label>Tipo de transporte</label>
                    <div class="checkbox">
                        <input name="tipo_tra" id="tipo_tra" required data-parsley-mincheck="1" type="checkbox" value="Aereo">
                        Aéreo
                    <br>
                        <input name="tipo_tra" id="tipo_tra" type="checkbox" value="Terrestre">
                        Terrestre
                    <br>
                        <input name="tipo_tra" id="tipo_tra" type="checkbox" value="Maritimo">
                        Marítimo
                    </div>
                </div> 
            </div>
        </div> 
    </div>       
</div>
@push('scripts')
<script>
var trans_tip=[];
$("input[name='tipo_tra']").on('click', function(){
    trans_tip=[];
    $("input[name='tipo_tra']:checked").each(function () {
        trans_tip.push($(this).val());
    });
    titulo_de_grafica="Trasportes";
}); 

function validar_transporte(){
    var res=false;
    $('#fecha_ini').parsley().validate();
    $('#fecha_fin').parsley().validate();
    $('#tipo_tra').parsley().validate();
    $('#institucion').parsley().validate();
    
    if($('#fecha_ini').parsley().validate()==true && $('#fecha_fin').parsley().validate()==true && $('#tipo_tra').parsley().validate()==true){
        res=true;
    }
    return res;
}

$('#guardado').click(function(e){
    if($('input[name="tipo_decomiso"]:checked').val()=="Transporte"){
        if(validar_transporte()){   
            $('#spinner-div').show(); 
            titulo_de_grafica="Transportes";
            $.ajax({
                url:'/grafica_transporte',
                data:{
                    fecha_ini:$('#fecha_ini').val(),
                    fecha_fin:$('#fecha_fin').val(),
                    tipo_tiempo:$('#periodos').val(),
                    tipo_decomiso:$('input[name="tipo_decomiso"]:checked').val(),
                    grafica:$('input[name="tipo_grafica"]:checked').val(),
                    trans_tip:trans_tip,
                    parametro2:$("#cantpor").is(':checked')==true?"porcentaje":"cantidad",
                    instituciones:$('#institucion').val(),
                    
                    },
                type:'get',
                success: function (response) {
                    reset_graph($('input[name="tipo_grafica"]:checked').val()); 
                    
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

$("input[name='tipo_decomiso']").change(function(){
    $('#tipo_tra').parsley().reset();
    if($('input[name="tipo_decomiso"]:checked').val()=="Transporte"){
        $("#multiCollapseExample6").collapse('show');
        //$("#criterio_municiones").show();
    }else{
        $("#multiCollapseExample6").collapse('hide');
        //$("#criterio_municiones").hide();   
        $("input[name='tipo_tra']").prop('checked',false);
    }
}); 


</script>
@endpush