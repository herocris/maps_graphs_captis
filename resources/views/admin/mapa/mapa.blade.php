@extends('admin.layout')
@section('header')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-6">
            <h1 class="m-0">Mapa</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('index')}}">Inicio</a></li>
                <li class="breadcrumb-item active">Mapa</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
@stop
@section('content')
@push('styles')
<style>
    #map { height: 180px; }
</style>
<!-- Modal -->

@include('admin.mapa.iconos')


    {{--  <div class="row">  --}}
        {{--  <div class="col-md-4" style="height:530px;overflow-y: scroll;">  --}}
            {{--  <form class="form-horizontal"  id="form_grafica">  --}}
                {{--  @include('admin.partials.barra_mapa')  --}}
            {{--  </form>      --}}
        {{--  </div>  --}}
        {{--  <div class="col-md-8">  --}}

            <div id="contenedor_mapa" class="row" style="background-color: white; position: relative;width: 100%; height: 100%;z-index: 1;">

                {{--  <span style="font-size:20px;cursor:pointer" onclick="openNav()" style="color:#17202A;position: absolute; z-index: 2;">&#9776; open</span>  --}}
                    <button type="button" class="btn btn-primary" id="op_opciones" onclick="openNav()" style="color:#17202A;position: absolute; top: 1%; left: 1%;color: white; z-index: 2;">Opciones >></button>
                    <div id="mySidenav" class="sidenav" style="display: 'none';color:#17202A;position: absolute; z-index: 2;">
                        @include('admin.partials.barra_mapa')
                    </div>
                    {{-- <div id="infoWindowContent" style="color:#17202A;"></div> --}}
                    <div class="col-auto" id="mapa" style="width: 100%; height: 90vh; z-index: 1;"></div>
                    <div id="info-box" style="display: 'none';width: 10%;font-size: 20px;color:#17202A;position: absolute; top: 50%; left: 90%; z-index: 1;background-color: white;"></div>
                    <div id="info-box2"  style="display: 'none';position: absolute; top: 170px; left: 830px; z-index: 1;background-color: white;color:#17202A;">
                        <div class="card-header" id="encabezado" style="display:none;">

                        </div>
                        <div class="container">
                            <div  class="container">
                                <div id="detalle" class="row">

                                </div>
                            </div>
                        </div>
                    </div>
                    {{--  <div class=col-auto style="width: 1000px; height: 410px; position: relative;" id="map"></div>  --}}
                    <button type="button" class="btn btn-primary" id="gen_img" onclick="generar_imagen_mapa2();" style="color:#17202A;position: absolute; top: 93%; left: 1%;color: white; z-index: 1;">Exportar a imagen</button>
                    <div id="spinner-div" class="pt-5" style="display: none; background-color: rgba(240 240 240 / 0.5);z-index: 2;position: absolute ;text-align: center;top: 0;left: 0;width: 100%;height: 100%">
                        <div class="spinner-border text-primary" role="status" style="vertical-align: -900%;">
                        </div>
                    </div>

            </div>
        {{--  </div>  --}}
    {{--  </div>  --}}

{{--  <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#hola22" aria-expanded="false" aria-controls="collapseExample">
    Button with data-target
</button>  --}}
<link rel="stylesheet" href="/image-picker-master/image-picker/image-picker.css">
<script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js" integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous"></script>

<style>
    .clase_label{
        position: relative;
        left: 0px;
        bottom: -20px;

        font-size: 16px;

        font-weight: bold;
        //background-color:white;
        opacity: .6;
        color:red;
    }

    #fecha_fin {
        z-index:3;
        position:relative;
    }
    #fecha_ini {
        z-index:3;
        position:relative;
    }
    //////////////////////////
    .container {
        font-family: "Lato", sans-serif;
    }
    .sidenav {
        height: 100%;
        width: 0;
        position: absolute;
        z-index: 1;
        top: 0;
        left: 100;
        //background-color: #111;
        overflow-x: hidden;
        transition: 0.5s;
        padding-top: 0px;
    }

    .sidenav .closebtn {
        position: absolute;
        top: 0;
        right: 25px;
        font-size: 36px;
        margin-left: 50px;
    }

    @media screen and (max-height: 450px) {
        .sidenav {padding-top: 15px;}
        .sidenav a {font-size: 18px;}
    }
    /////////////////////////
</style>

@stop

@push('scripts')
<!-- Popper.JS -->

<script src="/HND_adm1.js"></script>
<script src="/Honduras_dep.js"></script>
<script src="/Honduras_mun.js"></script>

<script src="/departamentos_ubicacion.js"></script>
<script src="/municipios_ubicación.js"></script>


<script type="text/javascript" src="/jsPDF-1.3.2/dist/jspdf.min.js"></script>
<script src="/html2canvas.js"></script>
<script src="/image-picker-master/image-picker/image-picker.min.js"></script>

<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
<script src="https://maps.googleapis.com/maps/api/js?key={{env('KEY_GOOGLE_MAPS')}}&libraries=visualization&callback=initMap&v=weekly"async></script>


{{--  <script type="text/javascript" src="https://maps.google.com/maps/api/js?sensor=true"></script>  --}}

{{--  <link rel="stylesheet" href="/resources/demos/style.css">  --}}
{{--  <script src="https://code.jquery.com/jquery-3.6.0.js"></script>  --}}

<script>
function openNav() {
  document.getElementById("mySidenav").style.width = "365px";
  $( "label" ).fadeTo( "fast" , 1);
  //document.getElementById("mySidenav").style.transition = "opacity 0.5s ease-in-out";
  //document.getElementById("grap_op").style.opacity = myopacity;
}

function closeNav() {
  document.getElementById("mySidenav").style.width = "0";
  $( "label" ).fadeTo( "fast" , 0);
  //document.getElementById("grap_op").style.opacity = 0.5;
}

$("#fecha_ini").on("dp.change", function (e) {
    $('#fecha_fin').data("DateTimePicker").minDate(e.date);
});
$("#fecha_fin").on("dp.change", function (e) {
    $('#fecha_ini').data("DateTimePicker").maxDate(e.date);

});

function tipocalendario(id,tipo){
    $(id).datetimepicker({
            format: tipo,
            locale: 'es',
            //viewMode:'months',
            showTodayButton: true,
            useCurrent: false,
            widgetPositioning:{
                horizontal: 'right',
                vertical: 'bottom'
            },
            icons: {
                time: "far fa-clock",
                date: "fa fa-calendar",
                up: "fas fa-arrow-up",
                down: "fas fa-arrow-down",
                previous: "fas fa-chevron-left",
                next: "fas fa-chevron-right",
                today: 'fas fa-calendar-day',
            },
            tooltips: {
                today: 'Ve al día de hoy',
                clear: 'Clear selection',
                close: 'Close the picker',
                selectMonth: 'Selecciona el mes',
                prevMonth: 'Mes anterior',
                nextMonth: 'Siguiente mes',
                selectYear: 'Selecciona el año',
                prevYear: 'Año anterior',
                nextYear: 'Siguiente año',
                selectDecade: 'Select Decade',
                prevDecade: 'Previous Decade',
                nextDecade: 'Next Decade',
                prevCentury: 'Previous Century',
                nextCentury: 'Next Century'
            }
        });
    $(id).data("DateTimePicker").maxDate(new Date());
}
tipocalendario('#fecha_ini','YYYY-MM-DD')
tipocalendario('#fecha_fin','YYYY-MM-DD')

function generar_imagen_mapa() {
    html2canvas(document.getElementById("contenedor_mapa")).then(canvas => {
        useCors: true;
      // Cuando se resuelva la promesa traerá el canvas
      // Crear un elemento <a>
      let enlace = document.createElement('a');
      enlace.download = "mapa_captis.png";
      // Convertir la imagen a Base64
      enlace.href = canvas.toDataURL();
      // Hacer click en él
      enlace.click();
    });
}

function quitarpropiedades() {
    var disableDefaultUI={
            zoomControl: false,
            mapTypeControl: false,
            scaleControl: false,
            streetViewControl: false,
            rotateControl: false,
            fullscreenControl: false
        };
    map.setOptions(disableDefaultUI);
}

function generar_imagen_mapa2() {
    var disableDefaultUI={
        zoomControl: false,
        mapTypeControl: false,
        scaleControl: false,
        //streetViewControl: false,
        rotateControl: false,
        fullscreenControl: false
    };
    $( "#op_opciones").hide();
    $( "#gen_img" ).hide();

    map.setOptions(disableDefaultUI);

    setTimeout(() => {
            html2canvas(document.getElementById("contenedor_mapa"), {
                useCORS: true,
                allowTaint: false,
                ignoreElements: (node) => {
                    return node.nodeName === 'IFRAME';
                }
            }).then(canvas => {
                let enlace = document.createElement('a');
                enlace.download = "mapa_captis.png";
                // Convertir la imagen a Base64
                enlace.href = canvas.toDataURL();
                // Hacer click en él
                enlace.click();
            });
        }, 1);

    setTimeout(() => {
        var disableDefaultUI2={
                zoomControl: true,
                mapTypeControl: false,
                scaleControl: true,
                //streetViewControl: true,
                rotateControl: true,
                fullscreenControl: true
            };
            $( "#op_opciones" ).show();
            $( "#gen_img" ).show();
        map.setOptions(disableDefaultUI2);
        }, 1);
}

$("#selepur").imagepicker();

function c_font_map(vari){
    var contentString =
                '<div id="content" style="width:40px;">' +
                '<i style="color:#17202A" class="fas fa-pills"> '+vari+'</i>'+
                '</div>';
    return contentString;
}

function logo_dec_map(texto,logo){
    var contentString =
                '<div id="content" style="width:40px;">' +
                '<i style="color:#17202A" class="fas fa-pills"> '+vari+'</i>'+
                '</div>';
    return contentString;
}
function limpiar_info_box2(){
    $("#info-box2").css("left", "830px");
    $("#detalle").empty();
    $("#encabezado").empty();
}

function controles_mapa(){
    const centerControlDiv = document.createElement("div");
    centerControlDiv.style.backgroundColor = "#3f6791";
    centerControlDiv.style.border = "2px solid #315377";
    centerControlDiv.style.borderRadius = "3px";
    centerControlDiv.style.boxShadow = "0 2px 6px rgba(0,0,0,.3)";
    centerControlDiv.style.cursor = "pointer";
    centerControlDiv.style.marginLeft = "8px";
    centerControlDiv.style.marginTop = "8px";
    centerControlDiv.style.marginBottom = "22px";
    centerControlDiv.style.textAlign = "center";
    centerControlDiv.title = "Click para ver opciones";

    centerControlDiv.style.color = "#fff";
    centerControlDiv.style.fontFamily = "Roboto,Arial,sans-serif";
    centerControlDiv.style.fontSize = "16px";
    centerControlDiv.style.lineHeight = "38px";
    centerControlDiv.style.paddingLeft = "5px";
    centerControlDiv.style.paddingRight = "5px";
    centerControlDiv.innerHTML = "Opciones >>";

    map.controls[google.maps.ControlPosition.TOP_LEFT].push(centerControlDiv);
    centerControlDiv.addEventListener("click", () => {
        openNav();
    });
}

function info_ventana_content(decomisos, nombre){
//console.log(decomisos,nombre);
//console.log(Object.keys(decomisos[0])[1],"llave");

    var cantidad_o_peso=$("#parametro").is(':checked');
    var porcentage_o_cantidad=$("#cantpor").is(':checked');

    var infoWindowText = '';

            var elementoHTML ='<div style="color:#17202A;">';
            elementoHTML +='<div class="container">';
            elementoHTML +='<div class="row">';
            elementoHTML += '<h3>'+nombre+'</h3>';
            elementoHTML +='</div>';
            elementoHTML +='<div class="row">';

                decomisos.forEach(function(element){
                elementoHTML += '<div class="col-md-auto">';
                var elementoHTML2 ='<div>';
                    console.log(Object.keys(decomisos[0])[1]);
                if (Object.keys(decomisos[0])[1]=='presentaciones') { //determinando la llave del objeto para saber si contiene presentaciones en el caso de que sea droga o precursor
                    element.presentaciones.forEach(function(element2){
                        if (!cantidad_o_peso) {
                            if (porcentage_o_cantidad) {
                                elementoHTML2+='<p>' + element2.nombre +' <b>'+ element2.peso.toLocaleString('en') + '</b>%</p>';
                            }else{
                                elementoHTML2+='<p>' + element2.nombre +' <b>'+ element2.peso.toLocaleString('en') + '</b> Kg</p>';
                            }

                        } else {
                            if (porcentage_o_cantidad) {
                                elementoHTML2+='<p>' + element2.nombre +' <b>'+ element2.cantidad.toLocaleString('en') + '</b>%</p>';
                            }else{
                                elementoHTML2+='<p>' + element2.nombre +' <b>'+ element2.cantidad.toLocaleString('en') + '</b></p>';
                            }
                        }
                    });
                } else if(Object.keys(decomisos[0])[1]=='cantidades_detenidos'){
                    element.cantidades_detenidos.forEach(function(element2){
                        if (porcentage_o_cantidad) {
                            elementoHTML2+='<p>' + (element2.genero=='M'?'Hombres':'Mujeres') +' <b>'+ element2.cantidad.toLocaleString('en') + '</b>%</p>';
                        }else{
                            elementoHTML2+='<p>' + (element2.genero=='M'?'Hombres':'Mujeres') +' <b>'+ element2.cantidad.toLocaleString('en') + '</b></p>';
                        }
                    });
                } else if(Object.keys(decomisos[0])[1]=='cantidades_transportes'){
                    if (porcentage_o_cantidad) {
                        elementoHTML2+='<p> <b>'+ element.cantidades_transportes.toLocaleString('en') + '</b>%</p>';
                    }else{
                        elementoHTML2+='<p> <b>'+ element.cantidades_transportes.toLocaleString('en') + '</b></p>';
                    }
                }

                elementoHTML2 += '</div>';
                switch (element.nombre) {
                    case 'Marihuana':
                        elementoHTML += '<img alt="Descripción de la imagen" src="/mis_iconos/marihuana-01-01.png" >';
                        break;
                    case 'Cocaína':
                        elementoHTML += '<img alt="Descripción de la imagen" src="/mis_iconos/cocaina1.png" >';
                        break;
                    case 'Crack':
                        elementoHTML += '<img alt="Descripción de la imagen" src="/mis_iconos/pastilla-01.png" >';
                        break;
                    case 'Metanfetamina':
                        elementoHTML += '<img alt="Descripción de la imagen" src="/mis_iconos/meta.png" >';
                        break;
                    case 'Heroína':
                        elementoHTML += '<img alt="Descripción de la imagen" src="/mis_iconos/heroina.png" >';
                        break;
                    default:
                        break;
                }
                elementoHTML += '<h6>' + element.nombre + '</h6>';
                if (Object.keys(decomisos[0])[1]=='cantidad') {
                    //elementoHTML += element.cantidad;
                    if (porcentage_o_cantidad) {
                        elementoHTML2+='<p> <b>'+ element.cantidad.toLocaleString('en') + '</b>%</p>';
                    }else{
                        elementoHTML2+='<p> <b>'+ element.cantidad.toLocaleString('en') + '</b></p>';
                    }
                }
                elementoHTML += '<p>' + elementoHTML2 + '</p>';
                elementoHTML += '</div>';
            });

            elementoHTML += '</div>';
            elementoHTML += '</div>';
            elementoHTML += '</div>';
            infoWindowText += elementoHTML;



    return infoWindowText
}
function borrar_marcadores(){
    //console.log("borrando marcadores con tamaño: "+markers.length)
    for (let i = 0; i < markers.length; i++) {
        markers[i].setMap(null);
    }
    markers = [];
}

function borrar_vectores(){
    //console.log("borrando vector con tamaño: "+vector_map.length)
    vector_map.forEach(function (eler){
        for (var i = 0; i < eler.length; i++){
            map.data.remove(eler[i]);
        }
    });
    vector_map = [];
}
function limpiar_eventos(){
    google.maps.event.clearListeners(map.data, 'mouseover');
    google.maps.event.clearListeners(map.data, 'mouseout');
    google.maps.event.clearListeners(map.data, 'click');/////
    google.maps.event.clearListeners(map, 'idle');
}
function agregar_eventos_genericos(map){
    map.data.addListener('mouseover', function(event) {
        if (event.feature.getProperty('NAME_2')!=undefined) {
            document.getElementById('info-box').textContent =
            event.feature.getProperty('NAME_2');
        }else{
            document.getElementById('info-box').textContent =
            event.feature.getProperty('NAME_1');
        }
        document.getElementById("info-box").style.display = "block";
        map.data.overrideStyle(event.feature, ($('input[name="tipo_map"]:checked').val()=="departamentos")?{strokeWeight: 5}:{strokeWeight: 5,fillOpacity: 0.3, fillColor:"#56c78b"});
    });

    map.data.addListener('mouseout', function(event) {
        map.data.overrideStyle(event.feature, ($('input[name="tipo_map"]:checked').val()=="departamentos")?{strokeWeight: 1}:{strokeWeight: 1,fillOpacity: 0});
        document.getElementById("info-box").style.display = "none";
    });

}

function limpiar_mapa(){
    borrar_marcadores()
    borrar_vectores()
}

function label_marcador_mapa(){
    var tipo=$('input[name="tipo_decomiso"]:checked').val()
    var label=""
    if($("#cantpor").is(':checked')==true){
        label="%";
    }else if(tipo == 'Droga' || tipo == 'Precursor'){
        label=($("#parametro").is(':checked')==true?"cantidad":"peso")=="peso"?" Kg":"";
    }
    return label
}

function limpiar_calor(){
    //heatmap.setMap(null);
    heatmap = new google.maps.visualization.HeatmapLayer();
    for (var k = 0; k < capas_calor.length; k++) {
        capas_calor[k].setMap(null);
    }
    capas_calor=[];
    //heatmap=[];
    calor=[];
}

function decomisos_por_departamento(map,decomisos,vector_deptos,vector_mun, ventana_info, ruta1, ruta2){
    //map.setOptions({ styles: stylesa.vacio });
    //var featureStyle = {
    //    fillColor: "#A6A6A6",
    //    fillOpacity: 1,
    //    strokeColor: '#ffffff',
    //    strokeWeight: 1,
    //}
    /////estableciendo color gris
    //map.data.setStyle(featureStyle);
    if (nivel==1) {
        map.setZoom(8);
        map.setCenter({ lat: 14.52887, lng:  -86.25262 });
        limpiar_mapa()
        limpiar_eventos()
        limpiar_calor()
        /////////////////modificación 18082023 para ejecutar todo en un solo query

        //////////////////////*******************
        decomisos.forEach(element=>{
            var coordenadas = paintMapVectors(0,vector_deptos,element,map)

            if (element['cantidades']!=0) {
                var cantidad=cantidades_dep(element['cantidades']);

                var marker = new google.maps.Marker({
                    position: { lat: coordenadas.latitud, lng: coordenadas.longitud },
                    map,
                    icon:"/mis_iconos/paquete.png",
                    customInfo: [element['dep_id'],element['nombre']],///para poder guardar información en un marcador
                    label: {text: ''+cantidad+''+label_marcador_mapa(), className: 'clase_label'},
                    //labelOrigin: new google.maps.Point(9, 9)
                });

                marker.addListener("click", function(event) {
                    //alert("kjklj")
                    /////////////devolviendo respuesta de una funcion ajax (primera opcion que muestra la información en una infowindow)
                    ventana_info('departamento',ruta1,element['dep_id'],element['nombre'], function(resp){
                        var infowindow = new google.maps.InfoWindow({
                            content:resp,
                            zIndex:3,
                        });
                        infowindow.open({
                            anchor: marker,
                            map,
                            shouldFocus: false,
                        });
                    });
                });
                markers.push(marker);
            }
        });
        ////////////agregando eventos al mapa en caso de seleccionar departamentos

        agregar_eventos_genericos(map)

        map.data.addListener('click', function(event) {
            decomisos_por_municipios(map,event,vector_mun,vector_deptos,decomisos,ventana_info,ruta1,ruta2)
            evento_data=event;
        });
        map.data.setStyle(element22=>{
            return {
                fillColor: element22.getProperty('COLOR'),
                fillOpacity: 1,
                strokeColor: '#ffffff',
                strokeWeight: 1,
            }
        });

    } else if(nivel=2){
        decomisos_por_municipios(map,evento_data,vector_mun,vector_deptos,decomisos,ventana_info,ruta1,ruta2)
    }
}

function decomisos_por_municipios(map,event,vector_mun,vector_deptos,decomisos,ventana_info,ruta1,ruta2){
    //console.log(ventana_info)
    nivel = 2;
    map.setZoom(9);
    map.setCenter({ lat: parseFloat(event.feature.getProperty('CENTRO')[0]), lng:  parseFloat(event.feature.getProperty('CENTRO')[1]) });

    limpiar_mapa()
    limpiar_eventos()

    var vector_mun2=vector_mun['features'].filter((muni) => muni.properties.ID_1==event.feature.getProperty('ID_1'));
    var vector_depto2=decomisos.filter((depto) => depto.dep_id==event.feature.getProperty('ID_1'));

    vector_depto2[0].municipios.forEach(function (elemVector1){
        var coordenadas = paintMapVectors(event.feature.getProperty('ID_1'),vector_mun2,elemVector1,map)
    //alert(elemVector1,'buscado');
        if (elemVector1['cantidades']!=0) {
            var cantidad=cantidades_dep(elemVector1['cantidades']);

            var marker = new google.maps.Marker({
                position: { lat: parseFloat(coordenadas.latitud) , lng: parseFloat(coordenadas.longitud)  },
                map,
                icon:"/mis_iconos/paquete.png",
                customInfo: [elemVector1['mun_id'],elemVector1['nombre']],///para poder guardar información en un marcador
                label: {text: ''+cantidad+''+label_marcador_mapa(), className: 'clase_label'},
                //labelOrigin: new google.maps.Point(9, 9)
            });
            marker.addListener("click", function(event) {
                /////////////devolviendo respuesta de una funcion ajax (primera opcion que muestra la información en una infowindow)
                ventana_info('municipio',ruta2, elemVector1['mun_id'],elemVector1['nombre'], function(resp){
                //ventana_info_drogas('municipio','/detalles_droga_lugar',elemVector1['mun_id'],elemVector1['nombre'], function(resp){
                    var infowindow = new google.maps.InfoWindow({
                        content:resp,
                        zIndex:3,
                    });
                    infowindow.open({
                        anchor: marker,
                        map,
                        shouldFocus: false,
                    });
                });
                ///////////////segunda opcion que muestra la información en una ventana dentro del mapa
                //info_depto_op2(this.customInfo[0],this.customInfo[1],map,vector_map, vector_deptos,this,markers)
            });
            markers.push(marker);
        }
    });

    agregar_eventos_genericos(map)

    map.addListener('idle', function(event) {
        console.log("cambia zoom")
        console.log(map.getZoom())
        if (map.getZoom()<9 && nivel==2) {
            nivel=1
            decomisos_por_departamento(map,decomisos,vector_deptos,vector_mun,ventana_info,ruta1,ruta2)
        }
    });

    map.data.setStyle(element22=>{
        return {
            fillColor: element22.getProperty('COLOR'),
            fillOpacity: 1,
            strokeColor: '#ffffff',
            strokeWeight: 1,
        }
    });
    /////////////
    //decomisos_mun(map, event.feature.getProperty('ID_1'), event.feature.getProperty('NAME_1'),event.feature.getProperty('CENTRO')[0],event.feature.getProperty('CENTRO')[1], vector_mun)
}

function decomisos_por_ubicacion(decomisos,map,vector){
    limpiar_mapa()
    limpiar_eventos()
    limpiar_calor()
console.log('valores',decomisos);
    paintMapVectors(0,vector,[],map)
    map.data.setStyle(element22=>{
        return {
            fillColor: element22.getProperty('COLOR'),
            fillOpacity: 0,
            strokeColor: '#ffffff',
            strokeWeight: 1,
            stroke: "#555555",
        }
    });

    agregar_eventos_genericos(map)

    map.addListener('idle', function(event) {
        if (map.getZoom()<9 && nivel==2) {
            nivel = 1;
            for (let i = 0; i < markers.length; i++) {
                markers[i].setMap(map);
            }
        }
    });
    map.data.addListener('click', function(event) {
    //console.log(event);
        nivel = 2;
        evento_data=event;

        for (let i = 0; i < markers.length; i++) {
            markers[i].setMap(map);
        }
        for (let i = 0; i < markers.length; i++) {
            //console.log(markers[i].customInfo[0]);
            if (event.feature.getProperty('ID_1')!=markers[i].customInfo[0]) {
                markers[i].setMap(null);
            }
        }
        map.setZoom(9);
        map.setCenter({ lat: parseFloat(event.feature.getProperty('CENTRO')[0]), lng:  parseFloat(event.feature.getProperty('CENTRO')[1]) });
    });

    if (nivel==1) {
        //alert("nivel 1")
        map.setZoom(8);
        map.setCenter({ lat: 14.52887, lng:  -86.25262 });
        decomisos.forEach(element=>{
            map.setOptions({ styles: stylesa.default });
            var infowindow = new google.maps.InfoWindow({
            content:

                infoWindowContent(element[Object.keys(element)[0]],Object.keys(element)[0],element['id']),
            });
            var marker = new google.maps.Marker({
                position: { lat: element['latitud'], lng: element['longitud'] },
                map,
                icon:"/mis_iconos/paquete.png",
                customInfo: [element['departamento_id']],
            });
            marker.addListener("click", () => {
                infowindow.open({
                anchor: marker,
                map,
                shouldFocus: false,
                });
            });
            markers.push(marker);
        });
    }else if(nivel==2){
        //alert(Object.keys(decomisos))

        map.setZoom(9);
        map.setCenter({ lat: parseFloat(evento_data.feature.getProperty('CENTRO')[0]), lng:  parseFloat(evento_data.feature.getProperty('CENTRO')[1]) });
        decomisos.forEach(element=>{
            //alert('sdakjñdfkl');
            //console.log(Object.keys(element)[0],'algo');
            map.setOptions({ styles: stylesa.default });
            var infowindow = new google.maps.InfoWindow({
            content:


                infoWindowContent(element[Object.keys(element)[0]],Object.keys(element)[0],element['id']),
            });
            var marker = new google.maps.Marker({
                position: { lat: element['latitud'], lng: element['longitud'] },
                map,
                icon:"/mis_iconos/paquete.png",
                customInfo: [element['departamento_id']],
            });
            marker.addListener("click", () => {
                infowindow.open({
                anchor: marker,
                map,
                shouldFocus: false,
                });
            });
            markers.push(marker);
        });

        for (let i = 0; i < markers.length; i++) {
            markers[i].setMap(map);
        }
        for (let i = 0; i < markers.length; i++) {
            if (evento_data.feature.getProperty('ID_1')!=markers[i].customInfo[0]) {
                markers[i].setMap(null);
            }
        }
    }
}

function decomisos_por_calor(decomisos,map,calor,heatmap,vector){
    limpiar_mapa()
    limpiar_eventos()
    limpiar_calor()
    //if(element['drogas'].length >0){
    paintMapVectors(0,vector,[],map)
    map.data.setStyle(element22=>{
        return {
            fillColor: element22.getProperty('COLOR'),
            fillOpacity: 0,
            strokeColor: '#ffffff',
            strokeWeight: 1,
            stroke: "#555555",
        }
    });

    agregar_eventos_genericos(map)

    map.addListener('idle', function(event) {
        if (map.getZoom()<9 && nivel==2) {
            nivel = 1;
            var calor2=[];
            heatmap.setMap(null);
            heatmap=new google.maps.visualization.HeatmapLayer({
                data:calor.map(function(element){return element.coordenadas}),
                opacity:1,
                radius:15
            });
            heatmap.setMap(map);
        }
    });

    map.data.addListener('click', function(event) {
        nivel = 2;
        evento_data=event;

        limpiar_calor()

        var calor2=calor.filter(function(element){
            return event.feature.getProperty('ID_1')==element.dep_id
        }).map(function(element2){return element2.coordenadas})

        heatmap.setMap(null);
        heatmap=new google.maps.visualization.HeatmapLayer({
            data:calor2,
            opacity:1,
            radius:15
        });
        heatmap.setMap(map);

        map.setZoom(9);
        map.setCenter({ lat: parseFloat(event.feature.getProperty('CENTRO')[0]), lng:  parseFloat(event.feature.getProperty('CENTRO')[1]) });

    });
    if (nivel==1) {
        map.setZoom(8);
        map.setCenter({ lat: 14.52887, lng:  -86.25262 });
        decomisos.forEach(element=>{
            map.setOptions({ styles: stylesa.default });
            var data={
                'coordenadas': new google.maps.LatLng(element['latitud'], element['longitud']),
                'dep_id':element.departamento_id
            };
            calor.push( data );
        });

        heatmap.setMap(null);
        heatmap=new google.maps.visualization.HeatmapLayer({
            data:calor.map(function(element){return element.coordenadas}),
            opacity:1,
            radius:15

        });

        heatmap.setMap(map);
    } else if(nivel==2){
        map.setZoom(9);
        map.setCenter({ lat: parseFloat(evento_data.feature.getProperty('CENTRO')[0]), lng:  parseFloat(evento_data.feature.getProperty('CENTRO')[1]) });

        decomisos.forEach(element=>{
            map.setOptions({ styles: stylesa.default });
            var data={
                'coordenadas': new google.maps.LatLng(element['latitud'], element['longitud']),
                'dep_id':element.departamento_id
            };
            calor.push( data );
        });

        var calor2=calor.filter(function(element){
            return evento_data.feature.getProperty('ID_1')==element.dep_id
        }).map(function(element2){return element2.coordenadas})

        heatmap.setMap(null);
        heatmap=new google.maps.visualization.HeatmapLayer({
            data:calor2,
            opacity:1,
            radius:15
        });
        heatmap.setMap(map);

    }

}

function cantidades_dep(cantidad){
    if ($("#cantpor").is(':checked')==true) {
        cantidad=cantidad.toFixed(2).toLocaleString('en')
    } else {
        cantidad=cantidad.toLocaleString('en')
    }
    return cantidad;
}

function minText(texto){
    return texto.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
}

var heatmap;
var calor;
var capas_calor=[];
var stylesa={
        default: [],
        vacio: [{
            elementType: 'all',
            stylers: [{visibility: 'off'}]
        }],
    };


var markers = [];
var vector_map = [];

//////////variables temporales para decomisos por municipios//////////////
var id_departa=0;
var nom_departa="";
var lat_departa=0.0;
var lng_departa=0.0;
var vector_municip = [];

var evento_data=[];

var nivel = 1;

function initMap() {
  map = new google.maps.Map(document.getElementById("mapa"), {
    center: { lat: 14.52887, lng:  -86.25262 },
    zoom: 7.3,
    mapTypeControl: false,
    streetViewControl: false,
  });
  //map.data.loadGeoJson("decomisos_drogasdepto.json");
}
$('#guardado').click(function(e){
    console.log($('input[name="tipo_decomiso"]:checked').val());
    switch ($('input[name="tipo_decomiso"]:checked').val()) {
        case "Droga":
            decomisos_drogas()
            break;
        case "Precursor":
            decomisos_precursores()
            break;
        case "Arma":
            decomisos_armas()
            break;
        case "Municion":
            decomisos_municiones()
            break;
        case "Transporte":
            decomisos_transportes();
            break;
        case "Detenido":
            decomisos_detenidos()
            break;
        default:
            break;
    }

});



function paintMapVectors(id_dep,vector,element,map){
    //////////////////////SETEANDO COLOR A CADA DEPARATAMENTO
    ////seteando nueva propiedad color arreglos con datos de vectores de departamentos y seteando latitud y longitud
    //console.log("ubiii")
    //console.log("pinta vector")

    //alert("ñdlfkh")
    var latitud=0.0;
    var longitud=0.0;

    if (id_dep==0 && $('input[name="tipo_map"]:checked').val()=="departamentos") {
        vector['features'].forEach(function (elemVector){
            if (minText(elemVector.properties.NAME_1) == minText(element['nombre']) ) {
                //console.log(minText(named)+" "+minText(element['nombre']));
                vector_map.push(map.data.addGeoJson(elemVector));

                if (element['cantidades']!=0) {
                    elemVector.properties['COLOR']=element['color']
                    latitud = elemVector.properties['CENTRO'][0]
                    longitud = elemVector.properties['CENTRO'][1]
                }else{
                    elemVector.properties['COLOR']="#A6A6A6"
                    latitud = elemVector.properties['CENTRO'][0]
                    longitud = elemVector.properties['CENTRO'][1]
                }
            }
        });
    } else if(id_dep!=0 && $('input[name="tipo_map"]:checked').val()=="departamentos"){

        vector.forEach(function (elemVector){

            if (minText(elemVector.properties.NAME_2) == minText(element['nombre'])) {

                vector_map.push(map.data.addGeoJson(elemVector));

                if (element['cantidades']!=0) {
                    elemVector.properties['COLOR']=element['color']
                    latitud = elemVector.properties['CENTRO'][0]
                    longitud = elemVector.properties['CENTRO'][1]
                }else{
                    elemVector.properties['COLOR']="#A6A6A6"
                    latitud = elemVector.properties['CENTRO'][0]
                    longitud = elemVector.properties['CENTRO'][1]
                }
            }
            //console.log(elemVector)
        });

    }
    if ($('input[name="tipo_map"]:checked').val()=="ubicaciones" || $('input[name="tipo_map"]:checked').val()=="calor") {
        vector['features'].forEach(function (elemVector){
            vector_map.push(map.data.addGeoJson(elemVector));
            //elemVector.properties['COLOR']="#56c78b"
        });
        //console.log("ubiii")
        //console.log(vector)
    }
    return {latitud, longitud}
}

$.ajaxSetup({
     headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
     }
 });

function errores_general(){
     $("#fecha_ini").val()==""?$("#fec_valid").show():$("#fec_valid").hide();
     $("#fecha_fin").val()==""?$("#fec2_valid").show():$("#fec2_valid").hide();

     $("#tipo_decomiso").val()==null?$("#deco_valid").show():$("#deco_valid").hide();

}

function infoWindowContent(arr,tipo,id_deco){
    var contenido="";
    var url_="decomiso/"+id_deco+"/edit"
    var url_="{{route('decomiso.edit','Id_dec')}}",
        url_=url_.replace('Id_dec', id_deco);
    var hyper="<a href="+url_+" target='_blank'>Ir a decomiso</a>"
    //alert(tipo);
    if (tipo=='drogas') {
        //alert(tipo);
        arr.forEach(ele=>{
            contenido=contenido+'<div id="content" class="container">' +
            '<p style="color:#17202A"><strong>Droga</strong>:'+ele['descripcion']+'<br>'+
            '<strong>Cantidad</strong>: '+ele['cantidad']+'<br>'+
            '<strong>Peso</strong>: '+ele['peso']+'<br>'+
            '<strong>Presentación</strong>: '+ele['presentacion']+'<br>'
            '<strong>Fecha</strong>: '+ele['fecha']+'<br>'+
            '</p>'+
            '</div>';
        });
    } else if (tipo=='precursores') {
        arr.forEach(ele=>{
            contenido=contenido+'<div id="content" class="container">' +
            '<p style="color:#17202A"><strong>Precursor</strong>:'+ele['descripcion']+'<br>'+
            '<strong>Cantidad</strong>: '+ele['cantidad']+'<br>'+
            '<strong>Volumen</strong>: '+ele['volumen']+'<br>'+
            '<strong>Presentación</strong>: '+ele['presentacion']+'<br>'
            '<strong>Fecha</strong>: '+ele['fecha']+'<br>'+
            '</p>'+
            '</div>';
        });
    } else if (tipo=='armas') {

        arr.forEach(ele=>{
            contenido=contenido+'<div id="content" class="container">' +
            '<p style="color:#17202A"><strong>Arma</strong>:'+ele['descripcion']+'<br>'+
            '<strong>Cantidad</strong>: '+ele['cantidad']+'<br>'+
            '<strong>Fecha</strong>: '+ele['fecha']+'<br>'+
            '</p>'+
            '</div>';
        });
    } else if (tipo=='municions') {
        arr.forEach(ele=>{
            contenido=contenido+'<div id="content" class="container">' +
            '<p style="color:#17202A"><strong>Munición</strong>:'+ele['descripcion']+'<br>'+
            '<strong>Cantidad</strong>: '+ele['cantidad']+'<br>'+
            '<strong>Fecha</strong>: '+ele['fecha']+'<br>'+
            '</p>'+
            '</div>';
        });
    }else if (tipo=='detenidos') {
        arr.forEach(ele=>{
            contenido=contenido+'<div id="content" class="container">' +
            '<p style="color:#17202A"><strong>Nombre</strong>:'+ele['nombre']+'<br>'+
            '<strong>Edad</strong>: '+ele['edad']+'<br>'+
            '<strong>Estructura</strong>: '+ele['estructura']+'<br>'+
            '<strong>Identidad</strong>: '+ele['identidad']+'<br>'+
            '</p>'+
            '</div>';
        });
    }else if (tipo=='transportes') {
        arr.forEach(ele=>{
            contenido=contenido+'<div id="content" class="container">' +
            '<p style="color:#17202A"><strong>Placa</strong>:'+ele['placa']+'<br>'+
            '<strong>Marca</strong>: '+ele['marca']+'<br>'+
            '<strong>Modelo</strong>: '+ele['modelo']+'<br>'+
            '<strong>Color</strong>: '+ele['color']+'<br>'+
            '</p>'+
            '</div>';
        });
    };
    return contenido+hyper;
}
var titulo_de_grafica="";
var unidades_de_grafica="";
</script>
@endpush
