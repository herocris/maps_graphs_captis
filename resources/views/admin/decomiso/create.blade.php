@extends('admin.layout')
@section('header')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-6">
            <h1 class="m-0">Crear decomiso</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('decomiso.index')}}">Decomisos</a></li>
                <li class="breadcrumb-item active">Crear decomiso</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
@stop
@section('content')
<!-- Modal -->


<style>
    .muis{
        color:red;
    }

    .pac-container { z-index: 100000 !important; }

</style>



<div class="container" id="contenedor_principal">
    <div class="row">
        <div class="col-md-4">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Nuevo decomiso</h3>
                </div>

                <!-- /.card-header -->
                <!-- form start -->
                <form class="form-horizontal" data-parsley-validate action="{{route('decomiso.store')}}" method="POST" id="form_decomiso">
                    {{csrf_field()}}
                    <input name="municipio_id_s_" id="municipio_id_s_" value="" type="hidden">
                    <div class="card-body">
                        <div class="form-group row">
                            <label for="inputFecha" class="col-sm-2 col-form-label">Fecha</label>
                            <div class="col-sm-10">
                                <input type="text" name="fecha" required class="form-control form-control-sm" id="inputFecha"
                                    value="{{old('fecha')}}" data-parsley-trigger="keyup" placeholder="aaaa-mm-dd" autocomplete="off" data-parsley-pattern-message="El formato de la fecha es incorrecto" data-parsley-pattern="^(\d{4})(\/|-)(0[1-9]|1[0-2])(\/|-)([0-2][0-9]|3[0-1])$">
                                @include('admin.partials.mensages_error', ['nombre' => 'fecha'])
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputObservacion" class="col-sm-4 col-form-label">Observación</label>
                            <div class="col-sm-8">
                                <input required type="text" name="observacion" required class="form-control form-control-sm" id="inputObservacion"
                                    value="{{old('observacion')}}" placeholder="Observación" autocomplete="off">
                                @include('admin.partials.mensages_error', ['nombre' => 'observacion'])
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputDirección" class="col-sm-3 col-form-label">Dirección</label>
                            <div class="col-sm-9">
                                <input required type="text" name="direccion" required class="form-control form-control-sm" id="inputDirección"
                                    placeholder="Dirección" autocomplete="off">
                                @include('admin.partials.mensages_error', ['nombre' => 'direccion'])
                            </div>
                        </div>

                        {{--  <div class="form-group row">
                            <label for="inputDep" class="col-sm-5 col-form-label">Departamento</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" id="inputDep"
                                    value="" placeholder="Departamento" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputMun" class="col-sm-3 col-form-label">Municipio</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="inputMun"
                                    value="" placeholder="Municipio" readonly>
                            </div>
                        </div>  --}}
                        <div class="form-group row">
                            <label for="inputDep" class="col-sm-5 col-form-label">Departamento</label>
                            <div class="col-sm-7">
                                <select required class="form-select form-control-sm" style="width: 100%" data-placeholder="Selecciona departamento" name="inputDep" id="inputDep" >
                                    <option  value="" {{ old('inputDep') == null ? 'selected' : '' }} disabled hidden>Selecciona departamento</option>
                                    @foreach($departamentos as $departamento)
                                    {{--  <option value="" selected disabled hidden>Selecciona departamento</option>
                                    <option value={{$departamento->id}}>{{$departamento->nombre}}</option>  --}}


                                    <option value={{$departamento->id}} {{ old('inputDep') == $departamento->id ? 'selected' : '' }}>{{$departamento->nombre}}</option>
                                    @endforeach
                                </select>
                                @include('admin.partials.mensages_error', ['nombre' => 'inputDep'])

                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="inputMun" class="col-sm-5 col-form-label">Municipio</label>
                            <div class="col-sm-7">
                                <select required class="form-select form-control-sm" data-placeholder="Selecciona municipio" name="municipio_id" id="inputMun" value="{{old('municipio_id')}}">
                                    <option value="" selected disabled hidden>Selecciona el municipio</option>
                                </select>
                                @include('admin.partials.mensages_error', ['nombre' => 'municipio_id'])
                            </div>
                        </div>
                        {{--  <div class="form-group row">
                            <label for="inputDep" class="col-sm-4 col-form-label">Ubicación</label>
                            <div class="col-sm-8">
                                <div class="row">

                                    <label id="latitud" name="prueba1" class="col-form-label-sm"></label>
                                    <label id="longitud" name="prueba2" class="col-form-label-sm"></label>
                                    <input type="text" class="col-sm-4 form-control form-control-sm" required id="latitud_">
                                    <input type="text" class="col-sm-4 form-control form-control-sm" required id="longitud_">
                                    <button type="button" class="col-sm-2 btn btn-primary" data-toggle="modal" data-target="#MapaModal"><i class="fas fa-map-marked"></i></button>
                                </div>


                            </div>
                        </div>  --}}
                        <div class="form-group row">
                            <label for="latitud" class="col-sm-4 col-form-label-sm">Latitud</label>
                            <div class="col-sm-6">
                                <input type="text" name="latitud" class="form-control form-control-sm" id="latitud"
                                    value="{{old('latitud')}}" placeholder="Latitud"  autocomplete="off"
                                    required data-parsley-maxlength="21" min="12" max="16.6" step="0.01"
                                    data-parsley-validation-threshold="1" data-parsley-trigger="keyup"
                                    data-parsley-type="number" >
                                @include('admin.partials.mensages_error', ['nombre' => 'latitud'])
                            </div>
                            <div class="col-sm-2">
                                <button type="button" class="btn btn-primary" id="generar"  data-placement="bottom" title="Comprobar ubicación" data-toggle="modal" data-target="#MapaModal"><i class="fas fa-map-marked"></i></button>
                            </div>
                            <label for="longitud" class="col-sm-4 col-form-label-sm">Longitud</label>
                            <div class="col-sm-6">
                                <input type="text" name="longitud" class="form-control form-control-sm" id="longitud"
                                    value="{{old('longitud')}}" placeholder="Longitud" autocomplete="off"
                                    required data-parsley-maxlength="21" min="-89.4" max="-82" step="0.01"
                                    data-parsley-validation-threshold="1" data-parsley-trigger="keyup"
                                    data-parsley-type="number" >
                                @include('admin.partials.mensages_error', ['nombre' => 'longitud'])
                            </div>
                        </div>



                        <div class="form-group row">
                            <label for="inputInsti" class="col-sm-4 col-form-label">Institución</label>
                            {{--  <div class="col-sm-5">  --}}
                                <select required class="form-select form-control-sm col-sm-5" data-placeholder="Selecciona institución" style="width: 165%" name="institucion_id" id="inputInsti" value="{{old('institucion_id')}}">
                                    @foreach($instituciones as $institucion)
                                    <option value="" selected disabled hidden>Selecciona institución</option>
                                    <option value={{$institucion->id}}>{{$institucion->nombre}}</option>
                                    @endforeach
                                </select>
                                @include('admin.partials.mensages_error', ['nombre' => 'institucion_id'])
                            {{--  </div>  --}}
                        </div>

                        <div class="form-group row">
                            {{--  <div class="col-sm-7">  --}}
                                <label for="inputInstiones" class="col-form-label">Colaboradores</label>
                                <select required class="form-select form-control-sm" multiple="multiple" data-placeholder="Selecciona colaboradores" style="width: 165%" name="instituciones_id[]" id="inputInstiones" value="{{old('instituciones_id')}}">
                                    @foreach($instituciones as $institucion)
                                    {{--  <option value="" selected disabled hidden>Selecciona colaboradores</option>  --}}
                                    <option value={{$institucion->id}}>{{$institucion->nombre}}</option>
                                    @endforeach
                                </select>
                                @include('admin.partials.mensages_error', ['nombre' => 'instituciones_id'])
                            {{--  </div>  --}}
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <button type="submit" id="guardar" class="btn btn-primary">Guardar</button>
                    </div>
                    <!-- /.card-footer -->
                </form>

            </div>
        </div>
        <div class="col-md-8">

        </div>
    </div>

</div>
@include('admin.decomiso.mapa', ['depto' => 'inputDep', 'munio' => 'inputMun', 'latitud' => 'latitud', 'longitud' => 'longitud'])
@stop

@push('scripts')



<script>


//alert($('#latitud').parsley().validate());
//if($('#latitud').parsley().validate()==true){
//    alert("alksdfj");
//}
//$('#guardar').click(function(e){
  //  $('#longitud_').parsley().validate();
  //  if($('#longitud_').val()=="" && $('#latitud_').val()=="" && $('#inputFecha').parsley().validate()==true && $('#inputObservacion').parsley().validate()==true && $('#inputDirección').parsley().validate()==true && $('#inputDep').parsley().validate()==true && $('#inputMun').parsley().validate()==true && $('#inputInsti').parsley().validate()==true){
  //      e.preventDefault();
  //  }

    //$('#latitud').parsley().validate();
//});
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
}
tipocalendario('#inputFecha','YYYY-MM-DD')

$('#inputFecha').data("DateTimePicker").maxDate(new Date());




function c_font_map(vari){
    var contentString =
                '<div id="content" style="width:40px;color:#17202A">' +
                ''+vari+''+
                '</div>';
    return contentString;
}




function llenarDeptos(id) {
    $("#inputMun").empty();
    @json($municipios).forEach(function (item) {
        if (item.departamento_id == id) {
            $("#inputMun").append('<option value="' + item.id + '">' + item.nombre + '</option>' +
                '<option value="" selected disabled hidden>Selecciona el municipio</option>');
        }
    });
}

$("#inputDep").on('change', function () {
    llenarDeptos($("#inputDep").val());
});
//alert($("#inputDep").val());
if($("#inputDep").val()!=null && $("#inputMun").val()==null){
    //alert("nada");
    llenarDeptos($("#inputDep").val());
}

$('#inputDep, #inputMun, #inputInsti').select2({
    theme: 'bootstrap4',
});
$('#inputInstiones').select2({
    theme: 'bootstrap4',
});
</script>
@endpush
