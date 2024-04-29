{{-----  detenidos  ----------------------------------------------------------}}
<div class="container">
    <div class="collapse multi-collapse" id="multiCollapseExample5">
        <div class="form-group row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label>Género</label>
                    <div class="checkbox">
                        <input id="gener_" name="genero" type="checkbox" value="M" required data-parsley-mincheck="1">
                        Hombre
                        {{--  <br>  --}}
                        <input id="gener_" name="genero" type="checkbox" value="F" required data-parsley-mincheck="1">
                        Mujer
                    </div>
                </div>
            {{--  </div>

            <div class="col-sm-6">  --}}
                <div class="form-group">
                    <label>Estructura criminal</label>
                    {{--  @include('admin.partials.nombres_de_estructuras')  --}}
                    <div id="estructura_" required></div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-----  detenidos end -------------------------------------------------------}}
@push('scripts')
<script>

function iterar_arr(arr){
    var contenido="";
        arr.forEach(ele=>{
            console.log("ele");
            console.log(ele);

            contenido=contenido+'<div id="content" style="width:100px;">' +
            '<p style="color:#17202A"><strong>Nombre</strong>:'+ele['nombre']+'<br>'+
            '<strong>Edad</strong>: '+ele['edad']+'<br>'+
            '<strong>Estructura</strong>: '+ele['estructura']+'<br>'+
            '<strong>Identidad</strong>: '+ele['identidad']+'<br>'+
            '</p>'+
            '</div>';
        });
    return contenido;
}

var detenidos_select=[];
@json($estr_noms).forEach(element=>{
    detenidos_select.push({ label: element.descripcion, value: element.id, alias: 'custom label for search' });
});

@include('admin.mapa.virtual_select')
virtual_select_ini(detenidos_select, true, '#estructura_', 'Selecciona estructura');

var estruc=[];
$("input[name='estructura']").on('click', function(){
    estruc=[];
    $("input[name='estructura']:checked").each(function () {
        estruc.push($(this).val());
    });
    //alert("lle");
});
var gene=[];
$("input[name='genero']").on('click', function(){
    gene=[];
    $("input[name='genero']:checked").each(function () {
        gene.push($(this).val());
    });
    //alert("lle");
});

function validar_detenido(){
    var res=false;
    $('#fecha_ini').parsley().validate();
    $('#fecha_fin').parsley().validate();
    $('#gener_').parsley().validate();
    $('#estructura_').parsley().validate();

    if($('#fecha_ini').parsley().validate()==true && $('#fecha_fin').parsley().validate()==true && $('#gener_').parsley().validate()==true && $('#estructura_').parsley().validate()==true){
        res=true;
    }
    return res;
}

function decomisos_detenidos(){
    if (validar_detenido()) {
        $('#spinner-div').show();
        closeNav();
        console.log(gene);
        $.ajax({
            url:'/mapa_detenidos',
            data:{
                fecha_ini:$('#fecha_ini').val(),
                fecha_fin:$('#fecha_fin').val(),
                tipo_tiempo:$('#tipo_tiempo').val(),
                tipo_decomiso:$('#tipo_decomiso').val(),
                tipo_mapa:$('input[name="tipo_map"]:checked').val(),
                genero:gene,
                estructura:$('#estructura_').val(),
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
                        decomisos_por_departamento(map,decomisos,vector_deptos,vector_mun, ventana_info_detenidos,'/detalles_detenido_lugar','/detalles_detenido_lugar')
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

function ventana_info_detenidos(tipo,ruta,id, nombre, my_callback){
    //console.log($('#nom_armas').val());
    var arr_municiones=$('#nom_mun').val();

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
            estructura:$('#estructura_').val(),
            genero:gene,
            //pres_precursores:arr_pres_precu,
            tipo_mapa:$('input[name="tipo_map"]:checked').val(),

            //parametro:$("#parametro").is(':checked')==true?"cantidad":"volumen",
            parametro2:$("#cantpor").is(':checked')==true?"porcentaje":"cantidad",

            },
        type:'get',
        success: function (response) {
            console.log(response,"repuesta");

            contentString += info_ventana_content(response.detenidos_detalles, nombre)
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

    if($('input[name="tipo_decomiso"]:checked').val()=="Detenido" && validar_detenido()){
        $('#spinner-div').show();
        closeNav();
        titulo_de_grafica="Detenidos";

        $.ajax({
        url:'/mapa_detenidos',
        data:{
            fecha_ini:$('#fecha_ini').val(),
            fecha_fin:$('#fecha_fin').val(),
            tipo_tiempo:$('#tipo_tiempo').val(),
            tipo_decomiso:$('#tipo_decomiso').val(),
            tipo_mapa:$('input[name="tipo_map"]:checked').val(),
            genero:gene,
            estructura:$('#estructura_').val(),
            parametro2:$("#cantpor").is(':checked')==true?"porcentaje":"cantidad",

            },
        type:'get',
        success: function (response) {
            var decomisos=response.decomisos;
            console.log("arreglo");
            console.log(decomisos);
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
                                    //    '<p style="color:#17202A"><strong>'+element['cantidades']+'</strong>'+
                                    //    '</p>',
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
                                        icon:"/mis_iconos/ladron-01.png",
                                        label: {text: ''+element['cantidades']+''+textolabel, className: 'clase_label'},
                                    });
                                    //infowindow.open({
                                    //    map,
                                    //    anchor: marker,
                                    //    shouldFocus: false,
                                    //});
                                    for (var i in map.data.h.h) {//iteración de poligonos en la data del mapa para setear colores en base a cantidad de decomisos
                                        if((element['nombre']).normalize("NFD").replace(/[\u0300-\u036f]/g, "")==(map.data.h.h[i].j.NAME_1).normalize("NFD").replace(/[\u0300-\u036f]/g, "")){
                                            map.data.overrideStyle(map.data.h.h[i], {fillColor: element['color']});
                                        }
                                    }
                                }
                            }else if($('input[name="tipo_map"]:checked').val()=="ubicaciones"){
                                map.setOptions({ styles: stylesa.default });
                                if(element['detenidos'].length >0){
                                    var infowindow = new google.maps.InfoWindow({
                                    content:

                                        iterar_arr(element['detenidos']),

                                    });
                                    var marker = new google.maps.Marker({
                                        position: { lat: element['latitud'], lng: element['longitud'] },
                                        map,
                                        icon:"/mis_iconos/ladron-01.png",
                                    });
                                    marker.addListener("click", () => {
                                        infowindow.open({
                                        anchor: marker,
                                        map,
                                        shouldFocus: false,
                                        });
                                    });
                                }
                            }else if($('input[name="tipo_map"]:checked').val()=="calor"){
                                map.setOptions({ styles: stylesa.default });
                                if(element['detenidos'].length >0){
                                    var data2=new google.maps.LatLng(element['latitud'], element['longitud']);
                                    calor.push( data2 );
                                }
                            }
                        });
                        var heatmap = new google.maps.visualization.HeatmapLayer({
                            data: calor
                        });
                        heatmap.setMap(map);
                    }
                    initMap();
                    console.log(response.decomisos);
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
    if($('input[name="tipo_decomiso"]:checked').val()=="Detenido"){
        $('#gener_').parsley().reset();
        $('#estructura_').parsley().reset();
        document.querySelector('#estructura_').reset();
        $("#multiCollapseExample5").collapse('show');
    }else{
        $("#multiCollapseExample5").collapse('hide');
        //$("input[name='estructura']").prop('checked',false);
        $("input[name='genero']").prop('checked',false);
    }
});
</script>
@endpush
