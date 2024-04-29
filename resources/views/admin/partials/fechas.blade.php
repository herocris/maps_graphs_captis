<div class="form-group row">
    <label for="fec_ini" class="col-sm-1 col-form-label">Fecha inicio</label>
    <div class="col-sm-2">
        <input type="text" required  autocomplete="off" name="fec_ini" class="form-control" id="fec_ini"
         data-parsley-trigger="keyup" placeholder="aaaa-mm-dd" data-parsley-pattern-message="El formato de la fecha es incorrecto" data-parsley-pattern="^(\d{4})(\/|-)(0[1-9]|1[0-2])(\/|-)([0-2][0-9]|3[0-1])$">
    </div>
</div>
<div class="form-group row">
    <label for="fec_fin" class="col-sm-1 col-form-label">Fecha final</label>
    <div class="col-sm-2">
        <input type="text" required  autocomplete="off" name="fec_fin" class="form-control" id="fec_fin"
            value="" data-parsley-trigger="keyup" placeholder="aaaa-mm-dd" data-parsley-pattern-message="El formato de la fecha es incorrecto" data-parsley-pattern="^(\d{4})(\/|-)(0[1-9]|1[0-2])(\/|-)([0-2][0-9]|3[0-1])$">
    </div>
</div>

@push('scripts')
<script>
    $("#fec_ini").on("dp.change", function (e) {
        $('#fec_fin').data("DateTimePicker").minDate(e.date);
        if($("#fec_ini").val()!='' && $("#fec_fin").val()!=''){
            table.draw();
        }else if($("#fec_ini").val()=='' && $("#fec_fin").val()==''){
            table.draw();
        }


    });
    $("#fec_fin").on("dp.change", function (e) {
        $('#fec_ini').data("DateTimePicker").maxDate(e.date);
        if($("#fec_ini").val()!='' && $("#fec_fin").val()!=''){
            table.draw();
        }else if($("#fec_ini").val()=='' && $("#fec_fin").val()==''){
            table.draw();
        }

    });
    function tipocalendario(id,tipo){
        $(id).datetimepicker({
            format: tipo,
            locale: 'es',
            //viewMode:'months',
            showTodayButton: true,
            useCurrent: false,
            widgetPositioning:{
                horizontal: 'left',
                vertical: 'bottom'
            },
            icons: {
                time: "far fa-clock",
                date: "fa fa-calendar",
                up: "fas fa-arrow-up",
                down: "fas fa-arrow-down",
                previous: "fas fa-chevron-left",
                next: "fas fa-chevron-right",
                today: 'fas fa-calendar-day',
            },
            tooltips: {
                today: 'Ve al día de hoy',
                clear: 'Clear selection',
                close: 'Close the picker',
                selectMonth: 'Selecciona el mes',
                prevMonth: 'Mes anterior',
                nextMonth: 'Siguiente mes',
                selectYear: 'Selecciona el año',
                prevYear: 'Año anterior',
                nextYear: 'Siguiente año',
                selectDecade: 'Select Decade',
                prevDecade: 'Previous Decade',
                nextDecade: 'Next Decade',
                prevCentury: 'Previous Century',
                nextCentury: 'Next Century'
            }
          });

          //console.log(moment(moment().endOf('day')).format('YYYY-MM-DD'));
          $(id).data("DateTimePicker").maxDate(new Date());
      }
      tipocalendario('#fec_ini','YYYY-MM-DD')
      tipocalendario('#fec_fin','YYYY-MM-DD')
</script>
@endpush
