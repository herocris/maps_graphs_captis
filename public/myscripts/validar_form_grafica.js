$('#form_decomiso').validate({
    rules:{
        fecha_ini:"required",
        fecha_fin:"required",
    },
    messages:{
        fecha_ini:"La fecha de inicio es requerida",
        fecha_fin:"La fecha final es requerida",
    }
})