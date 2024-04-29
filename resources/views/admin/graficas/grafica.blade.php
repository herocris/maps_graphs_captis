@extends('admin.layout')
@section('header')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-6">
            <h1 class="m-0">Gráfica</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('index')}}">Inicio</a></li>
                <li class="breadcrumb-item active">Gráfica</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
@stop
@section('content')
<!-- Modal -->

<div class="container">
    <div class="row">
        <div class="col-md-4"  style="height:530px;overflow-y: scroll;">
            <form  id="form_grafica" data-parsley-validate="">
                <div class="card card-primary" id="op_gra">
                    <div class="card-header">
                        <h2 class="card-title">Nueva gráfica</h2>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->

                        {{csrf_field()}}
                        <div class="card-body">
                            <div class="form-group row">
                                <label for="periodos" class="col-sm-5 col-form-label">Tipo de período</label>
                                <div id="periodos" class="col-sm-7"></div>
                            </div>

                            <div class="form-group row">
                                <label for="fecha_ini" class="col-sm-4 col-form-label">Fecha inicio</label>
                                <div class="col-sm-8">
                                    <input type="text" data-parsley-required="true"   name="fecha_ini" class="form-control" id="fecha_ini" autocomplete="off" data-parsley-trigger="keyup" placeholder="aaaa-mm-dd" data-parsley-pattern-message="El formato de la fecha es incorrecto" >
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="fecha_fin" class="col-sm-4 col-form-label">Fecha final</label>
                                <div class="col-sm-8">
                                    <input type="text" required  name="fecha_fin" class="form-control" id="fecha_fin" value="" data-parsley-trigger="keyup" placeholder="aaaa-mm-dd" data-parsley-pattern-message="El formato de la fecha es incorrecto"  autocomplete="off">

                                </div>
                            </div>
                            <div class="form-group row">
                                {{--  <label for="institucion" class="col-sm-4 col-form-label">Institución</label>  --}}
                                <div id="institucion" class="col-sm-12" data-parsley-required="true"></div>
                            </div>
                            @include('admin.partials.opciones_parametro2')

                            <div class="card">
                                <div class="card-body">
                                @include('admin.graficas.deco_droga')
                                @include('admin.graficas.deco_precursor')
                                @include('admin.graficas.deco_arma')
                                @include('admin.graficas.deco_municion')
                                @include('admin.graficas.deco_detenido')
                                @include('admin.graficas.deco_transporte')
                                {{--  @include('admin.partials.opciones_de_grafica2')  --}}
                                </div>
                            </div>


                            {{--  <div class="form-group row" id="parametro_precursor" style="display:none;">
                                <label for="parametro_prec" class="col-sm-4 col-form-label">Magnitud</label>
                                <div class="col-sm-8">
                                    <select class="form-select" name="parametro_prec" id="parametro_prec">
                                        <option value="" {{ old('parametro_prec')==null ? ' selected' : '' }}disabled hidden>Selecciona el parametro</option>
                                        <option value="volumen">volumen</option>
                                        <option value="cantidad">cantidad</option>
                                    </select>
                                    <span style="color:red">
                                        <strong id="mag_pre_valid" style="display:none;">"Selecciona un criterio"</strong>
                                    </span>
                                </div>
                            </div>  --}}



                            {{--  <div class="form-group row">
                                <label for="tipo_grafica" class="col-sm-3 col-form-label">Tipo de grafica</label>
                                <div class="col-sm-9">
                                    <select class="form-select" name="tipo_grafica" id="tipo_grafica">
                                        <option value="" {{ old('tipo_grafica')==null ? ' selected' : '' }} disabled hidden>Selecciona el tipo de grafica</option>
                                        <option value="bar">Barras</option>
                                        <option value="pie">Pastel</option>
                                        <option value="line">Lineas</option>
                                        <option value="doughnut">Dona</option>
                                    </select>
                                    <span style="color:red">
                                        <strong id="gra_tip_valid" style="display:none;">"Selecciona un tipo"</strong>
                                    </span>
                                </div>
                            </div>  --}}
                            <br>
                            @include('admin.partials.opciones_de_tipo_de_grafica')
                            <div>
                                <label for="parametro" class="col-sm-5 col-form-label">Parametro</label>
                                <input type="checkbox" id="cantpor" unchecked data-toggle="toggle" data-size="sm"
                                    data-on='<i class="fas fa-percentage" data-toggle="tooltip" data-placement="right" title="Porcentaje"></i>'
                                    data-off='<i class="fas fa-sort-amount-up-alt" data-toggle="tooltip" data-placement="right" title="Cantidad"></i>'
                                    data-onstyle="primary" data-offstyle="info">
                            </div>
                        </div>

                        <!-- /.card-body -->
                        <div class="card-footer d-flex">
                            <button type="button" class="btn btn-primary ml-auto"  id="guardado">Generar</button>

                        </div>
                        <!-- /.card-footer -->

                </div>
            </form>
        </div>
        <div class="col-md-8" id="esp_gra">

            <div id="contenedor_grafica" style="background-color: white;text-align: center;">

                <canvas id="myChart" width="400" height="290"></canvas>
                <div id="spinner-div" class="pt-5" style="display: none; background-color: rgba(240 240 240 / 0.5);z-index: 2;position: absolute ;text-align: center;top: 0;left: 0;width: 100%;height: 100%">
                    <div class="spinner-border text-primary" role="status" style="vertical-align: -900%;">
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-primary" id="guardado" onclick="generar_imagen_grafica();">Exportar a imagen</button>
        </div>
    </div>

</div>
<link href="/parsley.css" rel="stylesheet">
<style>
#fecha_fin {
  z-index:3;
  position:relative;
}
#fecha_ini {
  z-index:3;
  position:relative;
}

#op_gra {

        transition: 0.5s;

    }
</style>
@stop

@push('scripts')

<script type="text/javascript" src="/jsPDF-1.3.2/dist/jspdf.min.js"></script>
<script src="/html2canvas.js"></script>
//////////para TWS Toggle Buttons////////////
<script src="/jquery.twbs-toggle-buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.6.0/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
<script src="/parsley.min.js"></script>
<script src="/es.js"></script>


<link rel="stylesheet" href="/datetimepicker/bootstrap-datetimepicker.min.css">

<script src="/datetimepicker/moment.min.js"></script>
<script src="/datetimepicker/es-mx.js"></script>
<script src="/datetimepicker/bootstrap-datetimepicker.min.js"></script>

<style>
    .main{
        font-size: 120%;
        color: red;
    }



</style>

<script>

//$("#param_dro").twbsToggleButtons();
//$("#param_prec").twbsToggleButtons();


/////////////////////////////fechas///////////////////////////////////
$("#fecha_ini").on("dp.change", function (e) {
    $('#fecha_fin').data("DateTimePicker").minDate(e.date);
});
$("#fecha_fin").on("dp.change", function (e) {
    $('#fecha_ini').data("DateTimePicker").maxDate(e.date);
});

function tipocalendario(id,tipo){
$(id).datetimepicker({
        format: tipo,
        locale: 'es',
        //viewMode:'months',
        showTodayButton: true,
        useCurrent: false,
        widgetPositioning:{
            horizontal: 'right',
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
tipocalendario('#fecha_ini','YYYY-MM-DD')
tipocalendario('#fecha_fin','YYYY-MM-DD')


$('#periodos').on('beforeClose',function() {
    if(this.value=="Diario"){
        $('#fecha_ini').data("DateTimePicker").destroy();
        tipocalendario('#fecha_ini','YYYY-MM-DD');
        $('#fecha_fin').data("DateTimePicker").destroy();
        tipocalendario('#fecha_fin','YYYY-MM-DD');
    }else if(this.value=="Mensual" || this.value=="Trimestral" || this.value=="Semestral"){
        $('#fecha_ini').data("DateTimePicker").destroy();
        tipocalendario('#fecha_ini','YYYY-MM');
        $('#fecha_fin').data("DateTimePicker").destroy();
        tipocalendario('#fecha_fin','YYYY-MM');
    }else{
        $('#fecha_ini').data("DateTimePicker").destroy();
        tipocalendario('#fecha_ini','YYYY');
        $('#fecha_fin').data("DateTimePicker").destroy();
        tipocalendario('#fecha_fin','YYYY');
    }
});

////////////////////////////////////////////////////////////////

///////////////////////////////funcion para generar imagen de grafica//////////////////////////
function generar_imagen_grafica() {
    html2canvas(document.getElementById("contenedor_grafica")).then(canvas => {
      // Cuando se resuelva la promesa traerá el canvas
      // Crear un elemento <a>
      let enlace = document.createElement('a');
      enlace.download = "Grafica_captis.png";
      // Convertir la imagen a Base64
      enlace.href = canvas.toDataURL();
      // Hacer click en él
      enlace.click();

    });
}
/////////////////////////////////////////////////////////////////////////////////

///////////////////////////////variables de grafica///////////////////////////////////
var myChart;
var config;
var ctx = document.getElementById('myChart');

 config={
            type: 'bar',
            data: {
                labels:[],
                datasets: []
            },
            options : {
                plugins:{
                    title: {
                        display: true,
                        text: 'Custom Chart Title'
                    }
                },
                    scales: {
                        y: {
                            title: {
                                display: true,
                                text: 'Unidad',
                                    font: {
                                        size: 25,
                                        weight: "bold"
                                    }
                            }
                        }
                    }
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
                                    text: unidades_de_grafica,
                                    font: {
                                        size: 20,
                                        weight: "bold"
                                    }
                                }
                            }
                        };
myChart.update()

function reset_graph(gra){
    config['type'] = gra;
    myChart['options']['plugins']['title']['text']= titulo_de_grafica;
    myChart['options']['plugins']['title']['display']= true;
    myChart['options']['plugins']['title']['font']= {size: 25};

    if(myChart){
        myChart.data.labels=[];
        myChart.data.datasets=[];
    }
}

var titulo_de_grafica="";
var unidades_de_grafica="Kilogramos";
//////////////////////////////////////////////////////////////////

///////////////////////////////encabezado utilizado en solicitudes ajax///////////////////////////////////
$.ajaxSetup({
     headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
     }
 });
 //////////////////////////////////////////////////////////////////////////////////////////////


///////////////////////////////onclick funcion parsley para validaciones///////////////////////////////////
$('#guardado').click(function(e){
    $(function () {
        $('#form_grafica').parsley().on('field:validated', function() {
            var ok = $('.parsley-error').length === 0;
            $('.bs-callout-info').toggleClass('hidden', !ok);
            $('.bs-callout-warning').toggleClass('hidden', ok);
        })
        .on('form:submit', function() {
            return false; // Don't submit form for this demo
        });
    });
});
//////////////////////////////////////////////////////////////////////////////////////////////

///////////////////////////////funcion para inicializar los distintos combobox en los diferentes decomisos////////////
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
        showDropboxAsPopup:true,
        popupDropboxBreakpoint: '3000px',
        zIndex:3,
    });
    //document.querySelector(id).reset();
}
//////////////////////////////////////////////////////////////////////////////////////////////

///////////////////////////////combobox de instituciones///////////////////////////////////
var instituciones_select=[];
@json($instituciones).forEach(element=>{
    instituciones_select.push({ label: element.nombre, value: element.id, alias: 'custom label for search' });
});

function select_institucion(){
    VirtualSelect.init({
        ele: '#institucion',
        selectedValue: 'Diario',
        hideClearButton: true,
        options: instituciones_select,
        allOptionsSelectedText: 'Todas las instituciones',
        searchPlaceholderText: 'Buscar',
        placeholder: 'Selecciona institución',
        zIndex:4,
        selectedValue: [1,3,5,6,7],
        multiple: true,
    });
}
select_institucion();
//////////////////////////////////////////////////////////////////////////////////////////////

///////////////////////////////combobox de periodos///////////////////////////////////
function select_periodo(){
    VirtualSelect.init({
        ele: '#periodos',
        selectedValue: 'Diario',
        hideClearButton: true,
        options: [
            { label: 'Diario', value: 'Diario' },
            { label: 'Mensual', value: 'Mensual' },
            { label: 'Trimestral', value: 'Trimestral' },
            { label: 'Semestral', value: 'Semestral' },
            { label: 'Anual', value: 'Anual' },
          ],
        zIndex:4,
    });
}
select_periodo();
//////////////////////////////////////////////////////////////////////////////////////////////
</script>
@endpush
