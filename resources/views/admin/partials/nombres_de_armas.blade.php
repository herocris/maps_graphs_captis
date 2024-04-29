@foreach($arm_noms as $arm_nom)
        <br>
        <input name="armas" id="nom_armas" type="checkbox" required data-parsley-mincheck="1" value="{{$arm_nom->descripcion}}">
        {{$arm_nom->descripcion}}
    
@endforeach