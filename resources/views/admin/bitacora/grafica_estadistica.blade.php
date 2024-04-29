@extends('admin.layout')

@section('header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h3 class="m-0">Gráficas estadisticas</h3>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('index')}}">Inicio</a></li>
                <li class="breadcrumb-item active">Gráficas estadisticas</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
@stop
@section('content')
<div class="card">
    <!-- /.card-header -->
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group row">
                    <label for="fec_ini" class="col-sm-4 col-form-label">Fecha inicio</label>
                    <div class="col-sm-5">
                        <input type="text" required  autocomplete="off" name="fec_ini" class="form-control" id="fec_ini"
                        data-parsley-trigger="keyup" placeholder="aaaa-mm-dd" data-parsley-pattern-message="El formato de la fecha es incorrecto" data-parsley-pattern="^(\d{4})(\/|-)(0[1-9]|1[0-2])(\/|-)([0-2][0-9]|3[0-1])$">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="fec_fin" class="col-sm-4 col-form-label">Fecha final</label>
                    <div class="col-sm-5">
                        <input type="text" required  autocomplete="off" name="fec_fin" class="form-control" id="fec_fin"
                            value="" data-parsley-trigger="keyup" placeholder="aaaa-mm-dd" data-parsley-pattern-message="El formato de la fecha es incorrecto" data-parsley-pattern="^(\d{4})(\/|-)(0[1-9]|1[0-2])(\/|-)([0-2][0-9]|3[0-1])$">
                    </div>
                </div>  
                <div class="form-group row">
                    <label for="parametro" class="col-sm-5 col-form-label">Parametro</label>
                    <input type="checkbox" id="parametro" unchecked data-toggle="toggle" data-size="sm"
                        data-on='<i class="fas fa-users" data-toggle="tooltip" data-placement="right" title="Por usuarios"></i>'
                        data-off='<i class="fas fa-building" data-toggle="tooltip" data-placement="right" title="Por instituciones"></i>'
                        data-onstyle="primary" data-offstyle="info">
                </div> 
                <div class="form-group">
                    <label class="col-form-label">Instituciones</label>
                    <div id="intituciones" required></div>
                </div>
                <div class="form-group">
                    <label class="col-form-label" id="label_usuario">Usuarios</label>
                    <div id="usuarios" required></div>
                </div>
                <div class="form-group">
                    <label class="col-form-label">Acciones</label>
                    <div id="acciones" required></div>
                </div>
            
                <div class="form-group">
                    <button type="button" class="btn btn-primary" id="gene_grafica">Generar</button>
                </div>
            </div>
            
            <div class="col-md-9" id="esp_gra">
                <div id="contenedor_grafica" style="background-color: white;text-align: center;">
                
                    <canvas id="myChart" width="600" height="320"></canvas>
                    <div id="spinner-div" class="pt-5" style="display: none; background-color: rgba(240 240 240 / 0.5);z-index: 2;position: absolute ;text-align: center;top: 0;left: 0;width: 100%;height: 100%">
                        <div class="spinner-border text-primary" role="status" style="vertical-align: -900%;">
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-primary" id="guardado" onclick="generar_imagen_grafica();">Exportar a imagen</button>
            </div>
        </div>
        
    </div>
    <!-- /.card-body -->
</div>
@stop
@push('scripts')
<script src="/html2canvas.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.6.0/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
<script>
    //////////////////////////////////scrips de grafica//////////////////////////////////////
    var myChart;
    var config;
    var ctx = document.getElementById('myChart');

    config={
        type: 'bar',
        data: {
            labels:[],
            datasets: []
        },
        plugins: [ChartDataLabels],
        options: {
            plugins: {
                datalabels: {
                    backgroundColor: function(context) {
                        return context.dataset.backgroundColor;
                    },
                    borderColor: 'white',
                    borderRadius: 25,
                    borderWidth: 2,
                    color: 'white',
                    display: function(context) {
                        return context.dataset.data[context.dataIndex] !== 0;
                    },
                    font: {
                        weight: 'bold'
                    },
                    padding: 6,
                    formatter: function (value, ctx) {
                        
                        return value % 1 == 0?value:value.toFixed(2);
                    },
                },
            },
        }   
    };
    myChart = new Chart(ctx,config );
    myChart.options.scales = {
        y: {
            title: {
                display: true,
                text: "Interacciones",
                font: {
                    size: 20,
                    weight: "bold"
                }
            }
        }
    };
    myChart.update()

    function reset_graph(){       
        myChart['options']['plugins']['title']['display']= true;
        myChart['options']['plugins']['title']['font']= {size: 25};

        if(myChart){
            myChart.data.labels=[];
            myChart.data.datasets=[];
        }        
    }

    $('#gene_grafica').click(function(e){
        if(validar_parametros()){   
            $('#spinner-div').show(); 
            $.ajax({
                url:'/graficassbitacora', ////////se cambio el nombre de la ruta para poder mostrar graficas de bitacora a no administradores 03/07/2023
                data:{
                    fecha_ini:$('#fec_ini').val(),
                    fecha_fin:$('#fec_fin').val(),
                    acciones:$('#acciones').val(),
                    instituciones:$('#intituciones').val(),
                    usuarios:$('#usuarios')[0].multiple?$('#usuarios').val():[],
                    parametro:$("#parametro").is(':checked')==true?"usuarios":"instituciones",
                },
                type:'get',
                success: function (response) {
                    reset_graph();
                    myChart['options']['plugins']['title']['text']= $("#parametro").is(':checked')==true?"Usuarios":"Instituciones";

                    myChart.data=response.datos;
                    myChart.update();
                },
                statusCode: {
                    500: function() {
                        alert('Error en la solicitud al servidor');
                    }
                },
                complete: function () {
                    $('#spinner-div').hide();
                }
            });
        }    
    });
    ////////////////////////scripts de fecha////////////////////////////////
    $("#fec_ini").on("dp.change", function (e) {
        $('#fec_fin').data("DateTimePicker").minDate(e.date);
    });
    $("#fec_fin").on("dp.change", function (e) {
        $('#fec_ini').data("DateTimePicker").maxDate(e.date);
    });

    function tipocalendario(id,tipo){
        $(id).datetimepicker({
            format: tipo,
            locale: 'es',
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
          $(id).data("DateTimePicker").maxDate(new Date());
      }
      tipocalendario('#fec_ini','YYYY-MM-DD')
      tipocalendario('#fec_fin','YYYY-MM-DD')

    ///////////////////////////////scrips para imagen de grafica//////////////////////////
    function generar_imagen_grafica() {
        html2canvas(document.getElementById("contenedor_grafica")).then(canvas => {
            let enlace = document.createElement('a');
            enlace.download = "Grafica_captis.png";
            enlace.href = canvas.toDataURL();
            enlace.click();
        });
    } 
    ///////////////////////////////////scrips para virtual select////////////////////////////
    function virtual_select_ini(lista, multiple, id, criterio){
        VirtualSelect.init({ 
            ele: id,
            search: true, 
            options: lista,
            placeholder: criterio,
            noSearchResultsText: 'Sin resultados',
            noOptionsText: 'Sin resultados',
            searchPlaceholderText: 'Buscar', 
            optionsCount: 4,
            multiple: multiple,
            allOptionsSelectedText: 'Todos',
            optionsSelectedText: 'opciones',
            disableValidation: true,
            showDropboxAsPopup:false,
            popupDropboxBreakpoint: '3000px',
            zIndex:3,
        });
    }

    var instituciones_select=[];
    var usuarios_select=[];
    var acciones_select=[
        { label: 'crear', value: 'created' },
        { label: 'editar', value: 'updated' },
        { label: 'borrar', value: 'deleted' },
        { label: 'ingresar', value: 'Login' },
        { label: 'salir', value: 'Logout' },
    ];
    @json($instituciones).forEach(element=>{
        instituciones_select.push({ label: element.nombre, value: element.id, alias: 'custom label for search' });
    });

    $('#intituciones').change(function(e) {
        usuarios_select=[];
        @json($usuarios).forEach(element=>{
            if (element.institucion_id==e.target.value) {
                usuarios_select.push({ label: element.name, value: element.id, alias: 'custom label for search' });
            }
        });

        if (!($("#intituciones")[0].multiple)) {
            document.querySelector('#usuarios').setOptions(usuarios_select);
        }
    });

    virtual_select_ini(instituciones_select, true, '#intituciones', 'Selecciona instituciones');
    virtual_select_ini(acciones_select, true, '#acciones', 'Selecciona acciones');

    $('#parametro').change(function(e) {
        if ($("#parametro").is(':checked')) {
            virtual_select_ini(instituciones_select, false, '#intituciones', 'Selecciona instituciones');
            virtual_select_ini(usuarios_select, true, '#usuarios', 'Selecciona usuarios');
            $("#label_usuario").show();
        } else {
            virtual_select_ini(instituciones_select, true, '#intituciones', 'Selecciona instituciones');
            document.querySelector('#usuarios').destroy();
            $("#label_usuario").hide();
        }
    });

    $("#label_usuario").hide();

    ////////////////////////////////script para validar campos de formulario/////////////////////////////
    function validar_parametros(){
        var res=false;
        $('#fec_ini').parsley().validate();
        $('#fec_fin').parsley().validate();
        $('#acciones').parsley().validate();
        $('#intituciones').parsley().validate();
        if ($("#parametro").is(':checked')==true) {
            $('#usuarios').parsley().validate();
            if($('#usuarios').parsley().validate()==true && $('#fec_ini').parsley().validate()==true && $('#fec_fin').parsley().validate()==true && $('#acciones').parsley().validate()==true && $('#intituciones').parsley().validate()==true){
                res=true;
            }
        }else{
            if($('#fec_ini').parsley().validate()==true && $('#fec_fin').parsley().validate()==true && $('#acciones').parsley().validate()==true && $('#intituciones').parsley().validate()==true){
                res=true;
            }
        }
        return res;
    }
</script>
@endpush   