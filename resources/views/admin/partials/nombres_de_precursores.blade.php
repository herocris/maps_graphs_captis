@foreach($prec_noms as $prec_nom)
    
    <br>
        <input name="precursores" type="radio" required id="pr_nom" value="{{$prec_nom->descripcion}}">
        {{$prec_nom->descripcion}}
    
@endforeach