{{-----  armas  --------------------------------------------------------------}}
<div class="form-group row" id="criterio_armas">
    <div class="collapse multi-collapse" id="multiCollapseExample1">
        <div class="col-sm-12">
            <div class="form-group" id="nombre_arma" >
            <label>Arma</label>
            {{--  @include('admin.partials.nombres_de_armas' ,['tipo' => 'radio'])  --}}
            <div id="nom_armas" required></div>
            </div>
        </div>
    </div>
</div>
{{-----  armas end  ----------------------------------------------------------}}
@push('scripts')
<script>

function iterar_arr5(arr){
    var contenido="";
        arr.forEach(ele=>{
            //console.log("ele");
            //console.log(ele);

            contenido=contenido+'<div id="content" style="width:100px;">' +
            '<p style="color:#17202A"><strong>Arma</strong>:'+ele['descripcion']+'<br>'+
            '<strong>Cantidad</strong>: '+ele['cantidad']+'<br>'+
            '</p>'+
            '</div>';

        });
    return contenido;
}

var armas_select=[];
@json($arm_noms).forEach(element=>{
    armas_select.push({ label: element.descripcion, value: element.id, alias: 'custom label for search' });
});

@include('admin.mapa.virtual_select')
virtual_select_ini(armas_select, true, '#nom_armas', 'Selecciona arma');

$("input[name='armas']").removeAttr("type");
$("input[name='armas']").attr("type","radio");
$("input[name='armas']").removeAttr("data-parsley-mincheck");

var arm_prec=[];
$("input[name='armas']").on('click', function(){
    arm_prec=[];
    $("input[name='armas']:checked").each(function () {
        arm_prec.push($(this).val());
    });
    titulo_de_grafica="Armas";
});

function validar_arma(){
    var res=false;
    $('#fecha_ini').parsley().validate();
    $('#fecha_fin').parsley().validate();
    $('#nom_armas').parsley().validate();

    if($('#fecha_ini').parsley().validate()==true && $('#fecha_fin').parsley().validate()==true && $('#nom_armas').parsley().validate()==true){
        res=true;
    }
    return res;
}
////////////////
function decomisos_armas(){
    if (validar_arma()) {
        $('#spinner-div').show();
        closeNav();
        var arr_armas=$('#nom_armas').val();
        $.ajax({
            url:'/mapa_armas',
            data:{
                fecha_ini:$('#fecha_ini').val(),
                fecha_fin:$('#fecha_fin').val(),
                tipo_tiempo:$('#tipo_tiempo').val(),
                tipo_decomiso:$('#tipo_decomiso').val(),
                tipo_mapa:$('input[name="tipo_map"]:checked').val(),
                armas:arr_armas,
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
                        decomisos_por_departamento(map,decomisos,vector_deptos,vector_mun, ventana_info_armas,'/detalles_arma_lugar','/detalles_arma_lugar')
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

function ventana_info_armas(tipo,ruta,id, nombre, my_callback){
    //console.log($('#nom_armas').val());
    var arr_armas=$('#nom_armas').val();

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
            armas:arr_armas,
            //pres_precursores:arr_pres_precu,
            tipo_mapa:$('input[name="tipo_map"]:checked').val(),

            //parametro:$("#parametro").is(':checked')==true?"cantidad":"volumen",
            parametro2:$("#cantpor").is(':checked')==true?"porcentaje":"cantidad",

            },
        type:'get',
        success: function (response) {
            console.log(response,"repuesta");

            contentString += info_ventana_content(response.armas_detalles, nombre)
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
/////////////
{/* $('#guardado').click(function(e){
    //errores_general();
    //errores_droga();

    var stylesa={
    default: [],
    vacio: [{
            elementType: 'all',
            stylers: [{visibility: 'off'}]
        }],
    };

    if($('input[name="tipo_decomiso"]:checked').val()=="Arma" && validar_arma()){
        $('#spinner-div').show();
        closeNav();
        var arr_armas=$('#nom_armas').val();
        $.ajax({
            url:'/mapa_armas',
            data:{
                fecha_ini:$('#fecha_ini').val(),
                fecha_fin:$('#fecha_fin').val(),
                tipo_tiempo:$('#tipo_tiempo').val(),
                tipo_decomiso:$('#tipo_decomiso').val(),
                tipo_mapa:$('input[name="tipo_map"]:checked').val(),
                armas:arr_armas,
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
                            });
                            //controles_mapa();

                            if($('input[name="tipo_map"]:checked').val()=="departamentos"){
                                map.data.addGeoJson(oiio);
                                map.setOptions({ styles: stylesa.vacio });
                                //console.log(map.MapOptions);
                                //console.log(map.controls.disableDefaultUI=true);
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
                                        //  '<p style="color:#17202A"><strong>'+element['cantidades']+'</strong>'+
                                            //'</p>',
                                        //});
                                        var textolabel="";
                                        if($("#cantpor").is(':checked')==true){
                                            textolabel="%";
                                        }else{
                                            textolabel="";
                                        }
                                        var marker = new google.maps.Marker({
                                            position: { lat: element['latitud'], lng: element['longitud'] },
                                            map,
                                            icon:"/mis_iconos/arma-01.png",
                                            label: {text: ''+element['cantidades']+''+textolabel, className: 'clase_label'},
                                        });
                                        //infowindow.open({
                                        //  map,
                                        //  anchor: marker,
                                        //  shouldFocus: false,
                                        //});
                                        for (var i in map.data.h.h) {//iteración de poligonos en la data del mapa para setear colores en base a cantidad de decomisos
                                            if((element['nombre']).normalize("NFD").replace(/[\u0300-\u036f]/g, "")==(map.data.h.h[i].j.NAME_1).normalize("NFD").replace(/[\u0300-\u036f]/g, "")){
                                                map.data.overrideStyle(map.data.h.h[i], {fillColor: element['color']});
                                            }
                                        }
                                    }
                                }else if($('input[name="tipo_map"]:checked').val()=="ubicaciones"){
                                    map.setOptions({ styles: stylesa.default });
                                    var infowindow = new google.maps.InfoWindow({
                                    content:
                                        iterar_arr5(element['armas']),
                                    });
                                    var marker = new google.maps.Marker({
                                        position: { lat: element['latitud'], lng: element['longitud'] },
                                        map,
                                        icon:"/mis_iconos/arma-01.png",
                                    });
                                    marker.addListener("click", () => {
                                        infowindow.open({
                                            anchor: marker,
                                            map,
                                            shouldFocus: false,
                                        });
                                    });
                                }else if($('input[name="tipo_map"]:checked').val()=="calor"){
                                    map.setOptions({ styles: stylesa.default });
                                    var data=new google.maps.LatLng(element['latitud'], element['longitud']);
                                    calor.push( data );
                                }
                            });
                            var heatmap = new google.maps.visualization.HeatmapLayer({
                                data: calor
                            });
                            heatmap.setMap(map);
                }
                initMap();
                console.log(decomisos);

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

$("input[name='tipo_decomiso']").change(function(){
    if($('input[name="tipo_decomiso"]:checked').val()=="Arma"){
        $('#nom_armas').parsley().reset();
        document.querySelector('#nom_armas').reset();
        $('#multiCollapseExample1').collapse('show');
    }else{
        //$("input[name='armas']").prop('checked',false);
        $('#multiCollapseExample1').collapse('hide');
    }
});


</script>
@endpush
