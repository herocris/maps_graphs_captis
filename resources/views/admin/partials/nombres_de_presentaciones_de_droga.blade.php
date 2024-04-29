{{--  @foreach($dro_pres as $dro_pre)
    <div class="checkbox">

        <input name="pres_drogas" type={{$tipo}} value="{{$dro_pre->id}}" required>
        {{$dro_pre->descripcion}}

        @if($loop->last)
         <div class="invalid-feedback">Debes seleccionar una opción.</div>
        @endif
    </div>
@endforeach  --}}

    @foreach($dro_pres as $dro_pre)
        <br>
        @if($loop->last)
        
            <input name="pres_drogas" type="checkbox" id="pres_drogas_nom" value="{{$dro_pre->id}}" required>
            {{$dro_pre->descripcion}}
        @else
        
            <input name="pres_drogas" type="checkbox" id="pres_drogas_nom" value="{{$dro_pre->id}}" required>
            {{$dro_pre->descripcion}}
        @endif
    @endforeach