<style>
    .pac-container { z-index: 100000 !important; }
</style>
<div class="modal fade" id="MapaModal" tabindex="-1" aria-labelledby="MapaModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agregar ubicación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                    {{--  <input id="pac-input" class="controls" type="text" placeholder="Search Box" />  --}}


                <div class="row">
                    <label for="inputLugar" class="col-sm-1 col-form-label-sm">Lugar:</label>
                    <div class="col-sm-6">

                        {{--  <div class="pac-card" id="pac-card">
                            <div>
                                <div id="title">Countries</div>
                                <div id="country-selector" class="pac-controls">
                                    <input type="radio" name="type" id="changecountry-usa"/>
                                    <label for="changecountry-usa">USA</label>

                                    <input type="radio" name="type" id="changecountry-usa-and-uot" checked="checked"/>
                                    <label for="changecountry-usa-and-uot">USA and unincorporated organized territories</label>
                                </div>
                            </div>
                            <div id="pac-container">
                                <input id="pac-input" type="text" placeholder="Enter a location" />
                            </div>
                        </div>  --}}

                        {{--  <input type="text" name="direccion" class="form-control form-control-sm" id="inputLugar"
                            placeholder="Lugar" autocomplete="off" style="display: none;">  --}}
                        <input type="text" name="direccion" class="form-control form-control-sm" id="pac-input"
                            placeholder="Lugar" autocomplete="off" style="display: block;">
                    </div>
                    {{--  <div class="col-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="direccion" name="op_bus" id="direccion" checked>
                            <label class="form-check-label" for="direccion">
                                Dirección
                            </label>
                          </div>
                          <div class="form-check">
                            <input class="form-check-input" type="radio" value="coordenadas" name="op_bus" id="coodenada">
                            <label class="form-check-label" for="coodenada">
                                Coordenadas
                            </label>
                          </div>
                    </div>  --}}
                    <div class="col-sm-1">
                        <button type="button" class="btn btn-primary btn-sm" id="buscar_lugar">Buscar</button>
                    </div>
                </div>

                <div class=col-auto id="mapa" style="width: 1000px; height: 410px;"></div>
            </div>
            <div class="modal-footer">

                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
@push('scripts')

<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
<!-- Linea de codigo que habilita la funcion del mapa con la nueva cuenta de google cloud -->
<script src="https://maps.googleapis.com/maps/api/js?key={{env('KEY_GOOGLE_MAPS')}}&libraries=places"></script>

<!-- Linea de codigo que inhabilita la funcion del mapa -->
<!--script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDjs-RxeUUIAv_a4Isc4iDGj4m_cur9dGU&libraries=places"></script -->

<script>

var departamento="#"+@json($depto);
var municipio="#"+@json($munio);
//alert(departamento);


//alert($('#latitud').parsley().validate());
//if($('#latitud').parsley().validate()==true){
//    alert("alksdfj");
//}
//$('#guardar').click(function(e){
  //  $('#longitud_').parsley().validate();
  //  if($('#longitud_').val()=="" && $('#latitud_').val()=="" && $('#inputFecha').parsley().validate()==true && $('#inputObservacion').parsley().validate()==true && $('#inputDirección').parsley().validate()==true && $('#inputDep').parsley().validate()==true && $('#inputMun').parsley().validate()==true && $('#inputInsti').parsley().validate()==true){
  //      e.preventDefault();
  //  }

    //$('#latitud').parsley().validate();
//});


  //$('#inputFecha').data("DateTimePicker").maxDate(new Date());




function c_font_map(vari){
    var contentString =
                '<div id="content" style="width:40px;color:#17202A">' +
                ''+vari+''+
                '</div>';
    return contentString;
}
function initMap() {
    //////////////////////variables para maps
    const options = {
        //bounds: defaultBounds,
        componentRestrictions: { country: "hn" },
        fields: ["address_components", "geometry", "icon", "name"],
        strictBounds: false,
        types: ["establishment"],
    };
    const geocoder = new google.maps.Geocoder();
    const infowindow = new google.maps.InfoWindow();
    var mi_ubicacion=new google.maps.Marker();
    const input = document.getElementById("pac-input");
    const autocomplete = new google.maps.places.Autocomplete(input, options);

    map = new google.maps.Map(document.getElementById("mapa"), {
        center: { lat: 14.52887, lng:  -86.66262 },
        zoom: 7.5,
    });
    //////////////////






    //$('#inputLugar').keypress(function (e) {
    //    if($( "#inputLugar" ).val()!=''){
    //        if (e.which == '13') {
    //            geocodificar_lugar({ address: $( "#inputLugar" ).val()+", Honduras" }, mi_ubicacion );
    //        }
    //    }
    //});

    document.getElementById("generar").addEventListener("click", () => {///////////////setear marcador a momento de precionar el boton para mostrar mapa
        var ubicacion= { lat: parseFloat($("#latitud").val()), lng: parseFloat($("#longitud").val()) };
        mi_ubicacion.setMap(null);
        mi_ubicacion.setPosition(ubicacion);
        mi_ubicacion.setMap(map);

    });

    set_location_on_move(geocoder, map, mi_ubicacion)/////seteando ubicación en el mapa cuando la longitud y latitud esten llenas y haya un movimiento del mouse

    btn_buscar_lugar(geocoder, map, mi_ubicacion)/////////al momento de hacer click en boton de buscar


///////////////////////////////////////////////////////////
    //const card = document.getElementById("pac-card");

    //map.controls[google.maps.ControlPosition.TOP_RIGHT].push(card);

    //const center = { lat: 50.064192, lng: -130.605469 };
    // Create a bounding box with sides ~10km away from the center point
    //const defaultBounds = {
    //    north: center.lat + 0.1,
    //    south: center.lat - 0.1,
    //    east: center.lng + 0.1,
    //    west: center.lng - 0.1,
    //};


    // Set initial restriction to the greater list of countries.
    //autocomplete.setComponentRestrictions({
    //    country: ["us", "pr", "vi", "gu", "mp"],
    //});

    //const southwest = { lat: 5.6108, lng: 136.589326 };
    //const northeast = { lat: 61.179287, lng: 2.64325 };
    //const newBounds = new google.maps.LatLngBounds(southwest, northeast);

    //autocomplete.setBounds(newBounds);

    //const infowindow = new google.maps.InfoWindow();
    //const infowindowContent = document.getElementById("infowindow-content");

    //infowindow.setContent(infowindowContent);

    //const marker = new google.maps.Marker({
    //    map,
    //    anchorPoint: new google.maps.Point(0, -29),
    //});

    click_mapa(geocoder, mi_ubicacion, map)////////////al momento de hacer click en el mapa

    // Sets a listener on a given radio button. The radio buttons specify
    // the countries used to restrict the autocomplete search.
    lugares(autocomplete, geocoder, map, mi_ubicacion)////////////al escribir en la caja de texto buscara lugares
    //////////////////////////

}

initMap()/////////inicializa todo el mapa

function set_location_on_move(geocoder, map, mi_ubicacion){
    var cambio=false;
    $("#latitud, #longitud").keyup(function(){
        cambio=true;
    });

    $("#contenedor_principal").mousemove(function(){
        if(cambio){
            //console.log({ lat: $("#latitud").val(), lng:  $("#longitud").val()});
            if ($("#longitud").val()!="" && $("#latitud").val()!="") {
                map.setCenter({ lat: Number($("#latitud").val()), lng:  Number($("#longitud").val())});
                map.setZoom(10);
                var latlng = {
                    lat: parseFloat($("#latitud").val()),
                    lng: parseFloat($("#longitud").val()),
                  };
                geocodeLatLng(geocoder, map, latlng, mi_ubicacion);
                cambio=false;
            }
        }
    });
}

function btn_buscar_lugar(geocoder, map, mi_ubicacion){
    $( "#buscar_lugar" ).click(function() {

        var coordenadas = $("#pac-input").val().split(',');
            var latlng = {
                lat: parseFloat(coordenadas[0]),
                lng: parseFloat(coordenadas[1]),
            };
            if(!isNaN(latlng['lng']) && !isNaN(latlng['lat'])){
                //
                geocodeLatLng(geocoder, map, latlng, mi_ubicacion);
            }else{
                alert("No se encontro la ubicación");
                $(departamento).val('').trigger('change');
                $(municipio).val('').trigger('change');
                $("#latitud").val('').trigger('change');
                $("#longitud").val('').trigger('change');
                $("#pac-input").val('');
            }
    });
}
//window.initMap = initMap;
///////funcion para hacer cambio en radio butons entre dirección y coordenadas
//document.querySelectorAll('input[type=radio][name="op_bus"]')
//    .forEach(radio => radio
//    .addEventListener('change', function(e){
//        if(radio.value=="direccion"){
            //alert(radio.value)
//            var direccion = document.getElementById("pac-input");
//            direccion.style.display = 'block';
//            direccion.value="";
//            var coordenada = document.getElementById("inputLugar");
//            coordenada.style.display = 'none';
//        }else{
//            var direccion = document.getElementById("pac-input");
//            direccion.style.display = 'none';
//            var coordenada = document.getElementById("inputLugar");
//            coordenada.style.display = 'block';
//            coordenada.value="";
//        }
//   }
//));
function click_mapa(geocoder, mi_ubicacion, map){
    map.addListener('click', function(mapsMouseEvent) {

        mi_ubicacion.setMap(null);
        mi_ubicacion.setPosition(mapsMouseEvent.latLng);
        mi_ubicacion.setMap(map);

        //mi_ubicacion = new google.maps.Marker({
        //    position: mapsMouseEvent.latLng, //PosiciÃ³n de la marca
        //    map: map, //Mapa donde estarÃ¡ la marca
        //    title: '' //TÃ­tulo all hacer un mouseover
        //});

        $("#longitud").val(mapsMouseEvent.latLng.lng().toString());
        $("#latitud").val(mapsMouseEvent.latLng.lat().toString());
        $('#latitud').parsley().reset();

        var latlng = {
         lat: parseFloat(mapsMouseEvent.latLng.lat().toString()),
         lng: parseFloat(mapsMouseEvent.latLng.lng().toString()),
       };
       //console.log(mapsMouseEvent);
        geocodeLatLng(geocoder, map, latlng, mi_ubicacion);
     });
}

function lugares(autocomplete, geocoder, map, mi_ubicacion){
    autocomplete.addListener("place_changed", function() {

        //infowindow.close();
        //console.log(e);


        //console.log(mi_ubicacion);
        const place = autocomplete.getPlace();
        console.log(place);
        if (!place.geometry || !place.geometry.location) {
            console.log("no es direccion, posiblemente es coordenada");

            // User entered the name of a Place that was not suggested and
            // pressed the Enter key, or the Place Details request failed.
            //window.alert("No se encontro ubicación para la entrada: '" + place.name + "'");
            //return;

            //$("#longitud").val(place.geometry.location.lng().toString());
            //$("#latitud").val(place.geometry.location.lat().toString());
            var coordenadas = $("#pac-input").val().split(',');
            var latlng = {
                lat: parseFloat(coordenadas[0]),
                lng: parseFloat(coordenadas[1]),
            };
            console.log("longitud y latitud");
            console.log(latlng);
            if(!isNaN(latlng['lng']) && !isNaN(latlng['lat'])){
                geocodeLatLng(geocoder, map, latlng, mi_ubicacion);
            }else{

                alert("No se encontro la ubicación para: '" + place.name + "'");
                $(departamento).val('').trigger('change');
                $(municipio).val('').trigger('change');
                $("#latitud").val('').trigger('change');
                $("#longitud").val('').trigger('change');
                $("#pac-input").val('');
            }

            return;
        }

        var nombres_r=nombre_dep_mun(place);

        set_controls(nombres_r[0], nombres_r[1], place.geometry.location);
        // If the place has a geometry, then present it on a map.
        if (place.geometry.viewport) {
            map.fitBounds(place.geometry.viewport);
        } else {
            map.setCenter(place.geometry.location);
            map.setZoom(10); // Why 17? Because it looks good.
        }

        //mi_ubicacion.setVisible(false);
        mi_ubicacion.setMap(null);
        mi_ubicacion.setPosition(place.geometry.location);
        mi_ubicacion.setMap(map);
        //mi_ubicacion.setVisible(true);



        //infowindowContent.children["place-icon"].src = place.icon;
        //infowindowContent.children["place-name"].textContent = place.name;
        //infowindowContent.children["place-address"].textContent = address;
        //infowindow.open(map, mi_ubicacion);
    });
}


function geocodeLatLng(geocoder, map, latlng, marker) {///////funcion para buscar lugar por coordenadas
  console.log("geocodificación reversa");

  geocoder
    .geocode({ location: latlng })
    .then((response) => {
        console.log(response);
      if (response.results[0]) {
        if(buscar_pais(response)=="honduras"){//////comprobando si la ubicacion pertenece a Honduras

            var nombres_r=nombre_dep_mun(response);////obteniendo nombre de departamento y municipio
            set_controls(nombres_r[0], nombres_r[1], latlng);//// seteando datos de departamento, municipio y coordenadas en el formulario

            $("#pac-input").val(response.results[0].formatted_address);////seteando nombre de lugar en caja de texto de mapa
            map.setCenter(latlng);

            marker.setMap(map);
            marker.setPosition(latlng);
            marker.setVisible(true);
            map.setZoom(17);
        }else{/////////seteando los controles vacios en caso de que la ubicación no corresponda a Honduras
            alert("La ubicación no pertenece a territorio hondureño");
            $(departamento).val('').trigger('change');
            $(municipio).val('').trigger('change');
            $("#latitud").val('').trigger('change');
            $("#longitud").val('').trigger('change');
            $("#inputLugar").val('');
        }
        //infowindow.setContent(response.results[0].formatted_address);
      } else {
        window.alert("No results found");
      }
    })
    .catch(function(e) {////en caso de error
        alert("No se pudieron encontrar datos sobre este departamento y municipio"+ e); // "oh, no!"
        $(departamento).val('').trigger('change');
        $(municipio).val('').trigger('change');
        $("#latitud").val('').trigger('change');
        $("#longitud").val('').trigger('change');
        $("#inputLugar").val('');
    });
}

function set_controls(dep, mun, latlng){/////funcion para setear nombres departamento municipio y coordenadas en controles de formulario
    if(dep.length!=0 && mun.length!=0 && dep.latlng!=0){
        $(departamento).val(dep[0].id).trigger('change');
        $(municipio).val(mun[0].id).trigger('change');
        $("#longitud").val(latlng['lng']);
        $("#latitud").val(latlng['lat']);
    }
}

function nombre_dep_mun(response){////funcion para buscar nombres de departamento y muncipio
    console.log(response);
    //////busqueda de departamento que devuelve el request (place) en la lista de departamentos de la base de datos
    var depa=@json($departamentos).filter(function(dep) {
        return mayminac(dep.nombre) == buscar_depa(response);
    });
    //////busqueda de departamento en lista de ecepciones en caso de no estar en lista de departamentos de la base de datos
    if(depa.length==0){
        depa=exepciones_dep2.filter(function(depar) {
            return mayminac(depar.nombre) == mayminac(buscar_depa(response));
        });
    }
    //////busqueda de municipio que devuelve el request (place) en la lista de municipios de la base de datos
    var muni=@json($municipios).filter(function(mun) {
        return mayminac(mun.nombre)== buscar_muni(response) && (mun.departamento_id == depa[0].id);
    });
    //////busqueda de municipio en lista de ecepciones en caso de no estar en lista de municipios de la base de datos
    if(muni.length==0){
        muni=exepciones_dep.filter(function(munic) {
            return munic.nombre == buscar_muni(response) && (munic.departamento_id == depa[0].id);
        });
    }

    return [depa,muni];
}



function buscar_muni(response){
    var municipio="";
    if(Object.keys(response).length!=1){////////////si es response de lugar (place autocomplete)
        response.address_components.forEach(elementt => {
            if (elementt.types[0] == "administrative_area_level_2") {
                municipio=elementt.long_name;
            }
        });
        return mayminac(municipio);
    }else{////////////si es response de coordenada (geocodificación reversa)
        response.results.forEach(element => {
            element.address_components.forEach(elementt => {
                if (elementt.types[0] == "administrative_area_level_2") {
                    municipio=elementt.long_name;
                }
            });
        });
        return mayminac(municipio);
    }
}

function buscar_depa(response){
    ////////////pendiente de agregar algo que maneje el hecho del cambio de idioma en el navegador chrome ya que cuando se pone en ingles la geocodifiación no funciona
    var departamento="";
    //console.log(Object.keys(response).length);
    if(Object.keys(response).length!=1){////////////si es response de lugar (place autocomplete)
        response.address_components.forEach(elementt => {
            if (elementt.types[0] == "administrative_area_level_1") {
                departamento=elementt.long_name;
            }
        });
        return mayminac(!departamento.includes('Departamento de ')?departamento:(departamento.split("Departamento de "))[1]);
    }else{////////////si es response de coordenada (geocodificación reversa)
        response.results.forEach(element => {
            element.address_components.forEach(elementt => {
                if (elementt.types[0] == "administrative_area_level_1") {
                    departamento=elementt.long_name;
                }
            });
        });
        return mayminac(!departamento.includes('Departamento de ')?departamento:(departamento.split("Departamento de "))[1]);
    }
}


function buscar_pais(response){
    var pais="";
    if(response.results){
        //console.log("primera option");
        response.results.forEach(element => {
            element.address_components.forEach(elementt => {
                //alert(elementt.formatted_address);
                if (elementt.types[0] == "country") {
                    pais=elementt.long_name;
                }
            });
        });
        return mayminac(pais);
    }else{
        //console.log("segunda option");
        //console.log(response[0]);
        response.forEach(element => {
            //console.log(elementt);
            element.address_components.forEach(elementt => {
                //alert(elementt.long_name);
                if (elementt.types[0] == "country" && elementt.long_name == "Honduras") {
                    pais=elementt.long_name;
                }
            });
        });
        return mayminac(pais);
    }

}

function mayminac(cadena){
    return cadena.normalize('NFD').replace(/[\u0300-\u036f]/g,"").toLowerCase();
}

var exepciones_dep=[
  {"id":41, "nombre":"corquiin", "departamento_id":4},
  {"id":40, "nombre":"copn ruinas", "departamento_id":4},
  {"id":56, "nombre":"san pedro", "departamento_id":4},
  {"id":290, "nombre":"moraaan", "departamento_id":18},
];

var exepciones_dep2=[
  {"id":11, "nombre":"Bay Islands Department"},
  {"id":8, "nombre":"Francisco Morazán Department"}
];




</script>
@endpush
