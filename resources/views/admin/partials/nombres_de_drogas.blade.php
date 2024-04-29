
    @foreach($dro_noms as $dro_nom)
        <br>
        @if($loop->last)
            <input name="drogas" type="radio" id="drogas_nom" value="{{$dro_nom->descripcion}}" required>
            {{$dro_nom->descripcion}}
        @else
            <input name="drogas" type="radio" id="drogas_nom" value="{{$dro_nom->descripcion}}" required>
            {{$dro_nom->descripcion}}
        @endif
    @endforeach



