@foreach($mun_noms as $mun_nom)
    <br>
        <input name="municiones" id="nom_mun" required data-parsley-mincheck="1" type="checkbox" value="{{$mun_nom->descripcion}}">
        {{$mun_nom->descripcion}}
    
@endforeach