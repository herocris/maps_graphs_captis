{{--  @foreach($permisos as $id => $name)
        <div class="checkbox">
                
                <input name="permisos[]" type="checkbox" value="{{$id}}" 
                {{collect(old('permisos'))->contains($name)?'checked':''}}
                {{$model->permissions->contains($id)?'checked':''}}>
                
                {{$name}}
        </div>
    @endforeach  --}}

    
        

<div id="permisos" name="permisos"></div>
    @include('admin.partials.mensages_error', ['nombre' => 'permisos'])


@push('scripts')
<script>

    var permis=[];
    @json($permisos).forEach(element=>{
        permis.push({ label: element.name, value: element.id});
    });

    VirtualSelect.init({ 
        ele: '#permisos',
        search: true, 
        options: permis,
        placeholder: "Selecciona permisos",
        noSearchResultsTex: 'Sin resultados',
        noOptionsText: 'Sin resultados',
        searchPlaceholderText: 'Buscar', 
        optionsCount: 4,
        multiple: true,
        allOptionsSelectedText: 'Todos',
        optionsSelectedText: 'opciones',
        disableValidation: true,
        showDropboxAsPopup:true,
        popupDropboxBreakpoint: '3000px',
        zIndex:3,
    });

    @if (count($model->permissions->toArray())!=0)
        document.querySelector('#permisos').setValue(@json($model->permissions->pluck('id')->toArray()));
    @else
        document.querySelector('#permisos').toggleSelectAll(false);
    @endif

</script>
@endpush