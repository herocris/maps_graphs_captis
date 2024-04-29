@extends('admin.layout')

@section('content')
{{-- @dump($decomisos) --}}
            <div id="contenedor_mapa" class="row" style="background-color: white; position: relative;width: 100%; height: 100%;z-index: 1;">
                <div class="col-auto" id="mapa" style="width: 100%; height: 75vh; z-index: 1;"></div>

                <div class="card" style="width: 18rem;font-size: 15px;color:#17202A;position: absolute; top: 380px; left: 10px; z-index: 99;background-color: white;">
                    <div id="contenido" class="card-body" style="text-align: start;">
                        <p id="fecha" class="card-text"><strong>Fecha:</strong></p>
                        <p id="observacion" class="card-text"><strong>Observación:</strong></p>
                        <p id="direccion" class="card-text"><strong>Dirección:</strong></p>
                        <p id="institución" class="card-text"><strong>Institución organizadora:</strong></p>
                    </div>
                </div>
            </div>
@stop
@push('scripts')
<script src="/HND_adm1.js"></script>
<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
<script src="https://maps.googleapis.com/maps/api/js?key={{env('KEY_GOOGLE_MAPS')}}&libraries=visualization"></script>
<script>

function c_font_map(vari){
    var contentString =
                '<div id="content" style="width:40px;">' +
                '<i style="color:#17202A" class="fas fa-pills"> '+vari+'</i>'+
                '</div>';
    return contentString;
}

function initMap() {
  map = new google.maps.Map(document.getElementById("mapa"), {
    center: { lat: 14.75887, lng:  -87.20262 },
    zoom: 7.7,
  });


    //var iw=new google.maps.InfoWindow();
    //map.addListener('click', function(mapsMouseEvent) {
    //    iw.close();
    //    iw = new google.maps.InfoWindow({position: mapsMouseEvent.latLng});
    //    iw.setContent(c_font_map(mapsMouseEvent.latLng.toString()));
    //    iw.open(map);
    //});

    map.data.addGeoJson(oiio);

    var featureStyle = {
        fillColor: "green",
        strokeColor: '#ffffff',
        strokeWeight: 1
    }

    map.data.setStyle(featureStyle);



    var decomisos=@json($decomisos);
    console.log(decomisos);

    @foreach ($decomisos as $decomiso)

            var marker = new google.maps.Marker({
                position: { lat: @json($decomiso->latitud), lng: @json($decomiso->longitud) },
                map,
                icon:"/mis_iconos/paquete.png",

            });



            marker.addListener("mouseover", () => {
                $( "#contenido" ).empty();
                $( "#contenido" ).append( '<p id="fecha" class="card-text"><strong>Fecha:</strong> '+@json($decomiso->fecha)+'</p>'+
                '<p id="observacion" class="card-text"><strong>Observación:</strong> <br>'+@json($decomiso->observacion)+'</p>'+
                '<p id="direccion" class="card-text"><strong>Dirección:</strong>: '+@json($decomiso->direccion)+'</p>'+

                '<p id="institución" class="card-text"><strong>Institución organizadora:</strong> '+@json($decomiso->institucion)+'</p>');
            });

            marker.addListener("mouseout", () => {
                $( "#contenido" ).empty();
                $( "#contenido" ).append( '<p id="fecha" class="card-text"><strong>Fecha:</strong></p>'+
                '<p id="observacion" class="card-text"><strong>Observación:</strong> <br></p>'+
                '<p id="direccion" class="card-text"><strong>Dirección:</strong>:</p>'+
                '<p id="institución" class="card-text"><strong>Institución organizadora:</strong></p>');
            });
    @endforeach




}
initMap();

</script>
@endpush

