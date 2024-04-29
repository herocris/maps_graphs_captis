@foreach($estr_noms as $estr_nom)
    <br>
        <input name="estructura" id="estructura_" required data-parsley-mincheck="1" type="checkbox" value="{{$estr_nom->descripcion}}">
        {{$estr_nom->descripcion}}
    
@endforeach