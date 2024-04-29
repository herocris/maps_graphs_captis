function virtual_select_ini(lista, multiple, id, criterio){
    VirtualSelect.init({ 
        ele: id,
        search: true, 
        options: lista,
        placeholder: criterio,
        noSearchResultsTex: 'Sin resultados',
        noOptionsText: 'Sin resultados',
        searchPlaceholderText: 'Buscar', 
        optionsCount: 4,
        multiple: multiple,
        allOptionsSelectedText: 'Todos',
        optionsSelectedText: 'opciones',
        disableValidation: true,
        showDropboxAsPopup:true,
        popupDropboxBreakpoint: '3000px',
        zIndex:3,
    });
    document.querySelector(id).reset();
}