{{-----  drogas  -------------------------------------------------------------}}
<div class="container">
    <div class="collapse multi-collapse" id="multiCollapseExample2">
        <div class="form-group row">
            <div class="col-sm-12">
                <div class="form-group" id="nombre_droga2">
                    <label>Droga </label>
                    {{--  @include('admin.partials.nombres_de_drogas' ,['tipo' => 'radio'])  --}}
                    <div id="drogas_nom" required></div>
                </div>
            {{--  </div>
            <div class="col-sm-6">  --}}
                <div class="form-group" id="presentacion_droga2">
                    <label>Presentaciones</label>
                    {{--  @include('admin.partials.nombres_de_presentaciones_de_droga' ,['tipo' => 'radio'])  --}}
                    <div id="pres_drogas_nom" required></div>
                </div>
            </div>
        </div>
        <label for="parametro" class="col-sm-5 col-form-label">Magnitud</label>
        <input type="checkbox" id="parametro" unchecked data-toggle="toggle" data-size="sm"
        data-on='<i class="fas fa-sort-amount-up-alt" data-toggle="tooltip" data-placement="right" title="Cantidad"></i>'
        data-off='<i class="fas fa-weight-hanging" data-toggle="tooltip" data-placement="right" title="Peso"></i>'
        data-onstyle="primary" data-offstyle="info">
    </div>
</div>
{{-----  drogas  end----------------------------------------------------------}}
@push('scripts')
<script>
//var vector_map =[];
var estado_de_mapa=0;


var drogas_select=[];
@json($dro_noms).forEach(element=>{
    drogas_select.push({ label: element.descripcion, value: element.id, alias: 'custom label for search' });
});

var pre_drogas_select=[];
@json($dro_pres).forEach(element=>{
    pre_drogas_select.push({ label: element.descripcion, value: element.id, alias: 'custom label for search' });
});

@include('admin.mapa.virtual_select')
virtual_select_ini(drogas_select, true, '#drogas_nom', 'Selecciona droga');
virtual_select_ini(pre_drogas_select, true, '#pres_drogas_nom', 'Selecciona presentación');

$("input[name='pres_drogas']").removeAttr("type");
$("input[name='pres_drogas']").attr("type","radio");

var drog=[];
$("input[name='drogas']").on('click', function(){
    drog=[];
    $("input[name='drogas']:checked").each(function () {
        drog.push($(this).val());
    });

    if($("input[name='tipo_gr']:checked").val()=="presentaciones"){
        titulo_de_grafica="";
        titulo_de_grafica=$("input[name='drogas']:checked").val();
    }
});

var pre_dro=[];
$("input[name='pres_drogas']").on('click', function(){
    pre_dro=[];
    $("input[name='pres_drogas']:checked").each(function () {
        pre_dro.push($(this).val());
    });
    if($("input[name='tipo_gr']:checked").val()=="drogas"){
        titulo_de_grafica="";
        titulo_de_grafica=($("input[name='pres_drogas']:checked").parent().text()).trim();
    }
});

function errores_droga(){
    $("input[name='drogas']:checked").val()==undefined?$("#dro_valid1").show():$("#dro_valid1").hide();
    $("input[name='drogas']:checked").val()==undefined?$("#dro_valid2").show():$("#dro_valid2").hide();
    $("input[name='pres_drogas']:checked").val()==undefined?$("#pres_valid1").show():$("#pres_valid1").hide();
    $("input[name='pres_drogas']:checked").val()==undefined?$("#pres_valid2").show():$("#pres_valid2").hide();
    $("#parametro").val()==null && $("#tipo_decomiso").val()=="Droga"?$("#mag_dro_valid").show():$("#mag_dro_valid").hide();
}

function validar_droga(){
   var res=false;
   $('#fecha_ini').parsley().validate();
   $('#fecha_fin').parsley().validate();
   $('#drogas_nom').parsley().validate();
   $('#pres_drogas_nom').parsley().validate();

   if($('#fecha_ini').parsley().validate()==true && $('#fecha_fin').parsley().validate()==true && $('#drogas_nom').parsley().validate()==true && $('#pres_drogas_nom').parsley().validate()==true){
       res=true;
       //alert("valida");
   }
   return res;
}



$("#parametro").change(function(){
    unidades_de_grafica=$("#parametro").val()=="peso"?"Kilogramos":"Unidades";
});

$("#multiCollapseExample2").collapse('show');
$("input[name='tipo_decomiso']").change(function(){
    //alert("hacer");
    if($('input[name="tipo_decomiso"]:checked').val()=="Droga"){
        $('#drogas_nom').parsley().reset();
        $('#pres_drogas_nom').parsley().reset();
        document.querySelector('#drogas_nom').reset();
        document.querySelector('#pres_drogas_nom').reset();
        $("#multiCollapseExample2").collapse('show');
        $('#parametro').bootstrapToggle('off');
        $("#parametro").val("");
    }else{
        $("#multiCollapseExample2").collapse('hide');
        //$("input[name='drogas']").prop('checked',false);
        //$("input[name='pres_drogas']").prop('checked',false);
    }
});

function decomisos_drogas(){
    if (validar_droga()) {
        $('#spinner-div').show();
        closeNav();
        var arr_drog=$('#drogas_nom').val();
        var arr_pres_drog=$('#pres_drogas_nom').val();
        $.ajax({
            url:'/mapa_drogas',
            data:{
                fecha_ini:$('#fecha_ini').val(),
                fecha_fin:$('#fecha_fin').val(),
                tipo_tiempo:$('#tipo_tiempo').val(),
                tipo_decomiso:$('#tipo_decomiso').val(),
                drogas:arr_drog,
                pres_drogas:arr_pres_drog,
                tipo_mapa:$('input[name="tipo_map"]:checked').val(),

                parametro:$("#parametro").is(':checked')==true?"cantidad":"peso",
                parametro2:$("#cantpor").is(':checked')==true?"porcentaje":"cantidad",

                },
            type:'get',
            success: function (response) {

                //limpiar_mapa()
                function initMap() {
                    map = new google.maps.Map(document.getElementById("mapa"), {
                        center: { lat: 14.52887, lng:  -86.25262 },
                        zoom: 7.3,
                        mapTypeControl: false,
                        streetViewControl: false,
                    });
                    console.log("decomisos de base de datos");
                    //console.log(response.decomisos);
                    var decomisos=response.decomisos;

                    if (response.cant_total==0) {
                        alert("No hay datos");
                    }
                    var vector_deptos=deptos;
                    var vector_mun=muni;
                    heatmap = new google.maps.visualization.HeatmapLayer();
                    calor=[];
                    capas_calor.push(heatmap);

                    if($('input[name="tipo_map"]:checked').val()=="departamentos"){
                        decomisos_por_departamento(map,decomisos,vector_deptos,vector_mun, ventana_info_drogas,'/detalles_droga_lugar','/detalles_droga_lugar')
                    }else if($('input[name="tipo_map"]:checked').val()=="ubicaciones"){
                        decomisos_por_ubicacion(decomisos,map,vector_deptos)
                    }else if($('input[name="tipo_map"]:checked').val()=="calor"){
                        decomisos_por_calor(decomisos,map,calor,heatmap,vector_deptos)
                    }
                }
                initMap()
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

function ventana_info_drogas(tipo,ruta,id, nombre, my_callback){
    var arr_drog=$('#drogas_nom').val();
    var arr_pres_drog=$('#pres_drogas_nom').val();
    var contentString ='';
    $.ajax({
        url:ruta,
        data:{
            id_pl:id,
            tip_pl:tipo,
            fecha_ini:$('#fecha_ini').val(),
            fecha_fin:$('#fecha_fin').val(),
            //tipo_tiempo:$('#tipo_tiempo').val(),
            //tipo_decomiso:$('#tipo_decomiso').val(),
            drogas:arr_drog,
            pres_drogas:arr_pres_drog,
            tipo_mapa:$('input[name="tipo_map"]:checked').val(),

            parametro:$("#parametro").is(':checked')==true?"cantidad":"peso",
            parametro2:$("#cantpor").is(':checked')==true?"porcentaje":"cantidad",

            },
        type:'get',
        success: function (response) {
            console.log(response);

            contentString += info_ventana_content(response.drogas_detalles, nombre)
            my_callback(contentString);
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
    return contentString;
}

function info_depto_op2(id,nombre, map, vector_map, vector_deptos,event,markers){
    var arr_drog=$('#drogas_nom').val();
    var arr_pres_drog=$('#pres_drogas_nom').val();
    $.ajax({
            url:'/detalles_droga_departamento',
            data:{
                id_dep:id,
                fecha_ini:$('#fecha_ini').val(),
                fecha_fin:$('#fecha_fin').val(),
                tipo_tiempo:$('#tipo_tiempo').val(),
                tipo_decomiso:$('#tipo_decomiso').val(),
                drogas:arr_drog,
                pres_drogas:arr_pres_drog,
                tipo_mapa:$('input[name="tipo_map"]:checked').val(),

                parametro:$("#parametro").is(':checked')==true?"cantidad":"peso",
                parametro2:$("#cantpor").is(':checked')==true?"porcentaje":"cantidad",

                },
            type:'get',
            success: function (response) {
                /////////removiendo todos los vectores del mapa
                for (var i = 0; i < vector_map.length; i++)
                map.data.remove(vector_map[i]);


                /////////agreando vector del departamento seleccionado en base al ID_1 del GeoJson
                map.data.addGeoJson(vector_deptos['features'][event.customInfo[0]-1]);
                /////////estableciendo centro del mapa previamente seteado
                map.setCenter(event.position);
                ///////////estableciendo zoom
                map.setZoom(9.5);


                ///////linea para borrar todos los marcadores al momento de hacer click en un departamento especifico y dejar solamente datos de la cantidad
                for (let i = 0; i < markers.length; i++) {
                    if (!(event.position.toJSON().lat==markers[i].position.toJSON().lat && event.position.toJSON().lng==markers[i].position.toJSON().lng)) {
                        markers[i].setMap(null);
                    }
                }

                //console.log(event);
                //map.data.overrideStyle(event.feature, {fillColor: "red"});
                map.data.overrideStyle(event.feature, {strokeWeight: 5});


                console.log(response);
                //////////seteando la infobox, borrarndo encabezado
                //$("#info-box2").css("width", "240px");
                //limpiar_info_box2();

                $("#encabezado").append(nombre);
                var tabla = $("#detalle");
                var tamaño=1;
                //var tamcol=12/response.drogas_detalles.length
                //tabla.append("<div class='row'>");

                response.drogas_detalles.forEach(function(element){
                    //console.log(element);
                    //$("#info-box2").css("width", "170"*tamaño);
                    $("#info-box2").css("left", "830"-((tamaño-1)*130));

                    tabla.append("<div id='"+element.nombre+"' class='col-sm'></div>");
                    //$("#detalle < thead < tr").append("<h5 class='card-title'>"+element.nombre+"</h5>");
                    //tabla.append("<p style='line-height:0.5em;'>");
                        $("#"+element.nombre+"").append("<small><b>"+element.nombre+"</b></small><br>");
                    //tabla.append("<ul class='list-group list-group-flush' style='background-color: white;'>");


                    //tabla.append("<li class='list-group-item'>"+element.nombre+"</li>");
                    element.presentaciones.forEach(function(element2){
                        //console.log(element2.nombre);
                        $("#"+element.nombre+"").append("<font size='2'>"+element2.nombre+" <b>"+element2.peso+" Kg</b></font><br>");
                        //tabla.append("<li class='list-group-item'>"+element2.nombre+" "+element2.peso+" Kg</li>");

                    });
                    //tabla.append("</p>");
                    //tabla.append("</div>");



                    tamaño++;
                    //tabla.append("<tr><td>"+element.presentacion+"</td></tr>");
                });
                //tabla.append("</div>");
                //tabla.append("</div>");
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








</script>
@endpush
