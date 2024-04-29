@foreach($prec_pres as $prec_pre)
        <br>
        <input name="pres_precursores" type="checkbox" required id="pre_pr_nom" value="{{$prec_pre->id}}">
        
        {{$prec_pre->descripcion}}
    
@endforeach