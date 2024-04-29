{{-----  precursores  --------------------------------------------------------}}
<div class="container">
    <div class="collapse multi-collapse" id="multiCollapseExample3">
        <div class="form-group row">
            <div class="col-sm-12">
                <div class="form-group" id="nombre_precursor2">
                    <label>Precursor</label>
                    {{--  @include('admin.partials.nombres_de_precursores' ,['tipo' => 'radio'])  --}}
                    <div id="pr_nom" required></div>
                </div>
            {{--  </div>

            <div class="col-sm-6">  --}}
                <div class="form-group" id="presentacion_precursores2">
                    <label>Presentaciones</label>
                    {{--  @include('admin.partials.nombres_de_presentaciones_de_precursores' ,['tipo' => 'radio'])  --}}
                    <div id="pre_pr_nom" required></div>
                </div>
            </div>
        </div>
        <label for="parametro_prec" class="col-sm-5 col-form-label">Magnitud</label>
        <input type="checkbox" id="parametro_prec" unchecked data-toggle="toggle" data-size="sm"
            data-on='<i class="fas fa-sort-amount-up-alt" data-toggle="tooltip" data-placement="right" title="Cantidad"></i>'
            data-off='<i class="fas fa-prescription-bottle" data-toggle="tooltip" data-placement="right" title="Volumen"></i>'
            data-onstyle="primary" data-offstyle="info">
    </div>
</div>
{{-----  precursores end  ----------------------------------------------------}}
@push('scripts')
<script>
function iterar_arr4(arr){
    var contenido="";
        arr.forEach(ele=>{
            //console.log("ele");
            //console.log(ele);

            contenido=contenido+'<div id="content" style="width:100px;">' +
            '<p style="color:#17202A"><strong>Precursor</strong>:'+ele['descripcion']+'<br>'+
            '<strong>Cantidad</strong>: '+ele['cantidad']+'<br>'+
            '<strong>Volumen</strong>: '+ele['volumen']+'<br>'+
            '<strong>Presentación</strong>: '+ele['presentacion']+'<br>'+
            '</p>'+
            '</div>';

        });
    return contenido;
}

var precursores_select=[];
@json($prec_noms).forEach(element=>{
    precursores_select.push({ label: element.descripcion, value: element.id, alias: 'custom label for search' });
});

var pre_precursores_select=[];
@json($prec_pres).forEach(element=>{
    pre_precursores_select.push({ label: element.descripcion, value: element.id, alias: 'custom label for search' });
});

@include('admin.mapa.virtual_select')

virtual_select_ini(precursores_select, true, '#pr_nom', 'Selecciona precursor');
virtual_select_ini(pre_precursores_select, true, '#pre_pr_nom', 'Selecciona presentación');

$("input[name='pres_precursores']").removeAttr("type");
$("input[name='pres_precursores']").attr("type","radio");

var prec=[];
$("input[name='precursores']").on('click', function(){
    prec=[];
    $("input[name='precursores']:checked").each(function () {
        prec.push($(this).val());
    });
    if($("input[name='crit_prec']:checked").val()=="presentaciones"){
        titulo_de_grafica="";
        titulo_de_grafica=$("input[name='precursores']:checked").val();

    }
});
var pre_prec=[];
$("input[name='pres_precursores']").on('click', function(){
    pre_prec=[];
    $("input[name='pres_precursores']:checked").each(function () {
        pre_prec.push($(this).val());
    });
    if($("input[name='crit_prec']:checked").val()=="precursores"){
        titulo_de_grafica="";
        titulo_de_grafica=($("input[name='pres_precursores']:checked").parent().text()).trim();
    }
});

function validar_precursor(){
    var res=false;
    $('#fecha_ini').parsley().validate();
    $('#fecha_fin').parsley().validate();

    $('#pr_nom').parsley().validate();
    $('#pre_pr_nom').parsley().validate();

    if($('#fecha_ini').parsley().validate()==true && $('#fecha_fin').parsley().validate()==true && $('#pr_nom').parsley().validate()==true && $('#pre_pr_nom').parsley().validate()==true){
        res=true;
        //alert("valida");
    }
    return res;
}
////////////////
function decomisos_precursores(){
    if (validar_precursor()) {
        $('#spinner-div').show();
        closeNav();
        var arr_precu=$('#pr_nom').val();
        var arr_pres_precu=$('#pre_pr_nom').val();
        console.table([
        $('#fecha_ini').val(),
        $('#fecha_fin').val(),
        //$('#tipo_tiempo').val(),
        //$('#tipo_decomiso').val(),
        //arr_precu,
        //arr_pres_precu,
        $('input[name="tipo_map"]:checked').val(),
        $("#parametro_prec").is(':checked')==true?"cantidad":"volumen",
        $("#cantpor").is(':checked')==true?"porcentaje":"cantidad",
    ]);
        $.ajax({
            url:'/mapa_precursores',
            data:{
                fecha_ini:$('#fecha_ini').val(),
                fecha_fin:$('#fecha_fin').val(),
                //tipo_tiempo:$('#tipo_tiempo').val(),
                //tipo_decomiso:$('#tipo_decomiso').val(),
                precursores:arr_precu,
                pres_precursores:arr_pres_precu,
                tipo_mapa:$('input[name="tipo_map"]:checked').val(),

                parametro:$("#parametro_prec").is(':checked')==true?"cantidad":"volumen",
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
                    console.log(response.decomisos);
                    if (response.cant_total==0) {
                        alert("No hay datos");
                    }

                    var decomisos=response.decomisos;

                    var vector_deptos=deptos;
                    var vector_mun=muni;
                    heatmap = new google.maps.visualization.HeatmapLayer();
                    calor=[];
                    capas_calor.push(heatmap);

                    if($('input[name="tipo_map"]:checked').val()=="departamentos"){
                        decomisos_por_departamento(map,decomisos,vector_deptos,vector_mun, ventana_info_precursores,'/detalles_precursor_lugar','/detalles_precursor_lugar')
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

function ventana_info_precursores(tipo,ruta,id, nombre, my_callback){

    var arr_precu=$('#pr_nom').val();
    var arr_pres_precu=$('#pre_pr_nom').val();

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
            precursores:arr_precu,
            pres_precursores:arr_pres_precu,
            tipo_mapa:$('input[name="tipo_map"]:checked').val(),

            parametro:$("#parametro").is(':checked')==true?"cantidad":"volumen",
            parametro2:$("#cantpor").is(':checked')==true?"porcentaje":"cantidad",

            },
        type:'get',
        success: function (response) {
            console.log(response,"repuesta");

            contentString += info_ventana_content(response.precursores_detalles, nombre)
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
////////////////
{/* $('#guardado').click(function(e){
    var stylesa={
        default: [],
        vacio: [{
            elementType: 'all',
            stylers: [{visibility: 'off'}]
        }],
    };

    if($('input[name="tipo_decomiso"]:checked').val()=="Precursor" && validar_precursor()){
        $('#spinner-div').show();
        closeNav();
        var arr_precu=$('#pr_nom').val();
        var arr_pres_precu=$('#pre_pr_nom').val();
        $.ajax({
          url:'/mapa_precursores',
          data:{
              fecha_ini:$('#fecha_ini').val(),
              fecha_fin:$('#fecha_fin').val(),
              tipo_tiempo:$('#tipo_tiempo').val(),
              tipo_decomiso:$('#tipo_decomiso').val(),
              precursores:arr_precu,
              pres_precursores:arr_pres_precu,
              tipo_mapa:$('input[name="tipo_map"]:checked').val(),

              parametro:$("#parametro_prec").is(':checked')==true?"cantidad":"volumen",
              parametro2:$("#cantpor").is(':checked')==true?"porcentaje":"cantidad",

              },
          type:'get',
          success: function (response) {
               var decomisos=response.decomisos;
               var calor=[];
               function initMap() {
                        map = new google.maps.Map(document.getElementById("mapa"), {
                            center: { lat: 14.52887, lng:  -86.66262 },
                            zoom: 7.5,
                            mapTypeControl: false,
                            streetViewControl: false,
                            //disableDefaultUI: true,
                        });
                        //controles_mapa();

                        if($('input[name="tipo_map"]:checked').val()=="departamentos"){
                            map.data.addGeoJson(oiio);
                            map.setOptions({ styles: stylesa.vacio });

                            var featureStyle = {
                                fillColor: "#A6A6A6",
                                fillOpacity: 1,
                                strokeColor: '#ffffff',
                                strokeWeight: 1,


                            }

                            map.data.setStyle(featureStyle);

                            map.data.addListener('mouseover', function(event) {
                                document.getElementById('info-box').textContent =
                                event.feature.getProperty('NAME_1');
                                document.getElementById("info-box").style.display = "block";

                                map.data.overrideStyle(event.feature, {strokeWeight: 5});
                            });

                            map.data.addListener('mouseout', function(event) {
                                map.data.overrideStyle(event.feature, {strokeWeight: 1});
                                 document.getElementById("info-box").style.display = "none";
                            });
                        }


                        decomisos.forEach(element=>{
                            if($('input[name="tipo_map"]:checked').val()=="departamentos"){
                                if(element['cantidades']!=0){
                                    //var infowindow = new google.maps.InfoWindow({
                                    //content:
                                      //  '<p style="color:#17202A"><strong>'+element['cantidades']+''+(($("#parametro_prec").is(':checked')==true?"cantidad":"volumen")=="volumen"?" litros":"")+'</strong>'+
                                        //'</p>',
                                    //});
                                    var textolabel="";
                                    if($("#cantpor").is(':checked')==true){
                                        textolabel="%";
                                        console.log("esta en porcentages");
                                    }else{
                                        textolabel=($("#parametro_prec").is(':checked')==true?"cantidad":"volumen")=="volumen"?" L":"";
                                        console.log("no esta en porcentages");
                                    }

                                    var marker = new google.maps.Marker({
                                        position: { lat: element['latitud'], lng: element['longitud'] },
                                        map,
                                        icon:$('#selepur').val(),
                                        label: {text: ''+element['cantidades']+''+textolabel, className: 'clase_label'},

                                    });
                                    //infowindow.open({
                                      //  map,
                                        //anchor: marker,
                                        //shouldFocus: false,
                                    //});
                                    for (var i in map.data.h.h) {//iteración de poligonos en la data del mapa para setear colores en base a cantidad de decomisos
                                        if((element['nombre']).normalize("NFD").replace(/[\u0300-\u036f]/g, "")==(map.data.h.h[i].j.NAME_1).normalize("NFD").replace(/[\u0300-\u036f]/g, "")){
                                            map.data.overrideStyle(map.data.h.h[i], {fillColor: element['color']});
                                        }
                                    }

                                }
                            }else if($('input[name="tipo_map"]:checked').val()=="ubicaciones"){
                                //if(element['precursores'].length >0){
                                    map.setOptions({ styles: stylesa.default });
                                    var infowindow = new google.maps.InfoWindow({
                                    content:
                                        iterar_arr4(element['precursores']),
                                    });
                                    var marker = new google.maps.Marker({
                                        position: { lat: element['latitud'], lng: element['longitud'] },
                                        map,
                                        icon:$('#selepur').val(),
                                    });
                                    marker.addListener("click", () => {
                                        infowindow.open({
                                        anchor: marker,
                                        map,
                                        shouldFocus: false,
                                        });
                                    });
                                //}
                            }else if($('input[name="tipo_map"]:checked').val()=="calor"){
                                map.setOptions({ styles: stylesa.default });
                                if(element['precursores'].length >0){
                                    var data=new google.maps.LatLng(element['latitud'], element['longitud']);
                                    calor.push( data );
                                }
                            }
                        });
                        var heatmap = new google.maps.visualization.HeatmapLayer({
                            data: calor
                        });
                        heatmap.setMap(map);

                    }
                    initMap();
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
}); */}

$("#parametro_prec").change(function(){
    unidades_de_grafica=$("#parametro_prec").val()=="volumen"?"Litros":"Unidades";
});

$("input[name='tipo_decomiso']").change(function(){
    if($('input[name="tipo_decomiso"]:checked').val()=="Precursor"){
        $('#pr_nom').parsley().reset();
        $('#pre_pr_nom').parsley().reset();

        $("#multiCollapseExample3").collapse('show');
        $('#parametro_prec').bootstrapToggle('off');

        document.querySelector('#pr_nom').reset();
        document.querySelector('#pre_pr_nom').reset();
    }else{
        $("#multiCollapseExample3").collapse('hide');
        $("input[name='precursores']").prop('checked',false);
        $("input[name='pres_precursores']").prop('checked',false);
    }
});

</script>
@endpush
