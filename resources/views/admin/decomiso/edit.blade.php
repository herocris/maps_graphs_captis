@extends('admin.layout')
@section('header')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-6">
            <h1 class="m-0">Editar decomiso</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('decomiso.index')}}">Decomisos</a></li>
                <li class="breadcrumb-item active">Editar decomiso</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
@stop
@section('content')
<!-- Modal -->

{{-- @include('admin.decomiso.form_create', ['nombre_accion' => 'municipio']) --}}
@can('crear decomisos de droga')
@include('admin.decomiso.droga.create_droga')
@endcan

@include('admin.decomiso.droga.edit_droga')

@can('crear decomisos de precursores')
@include('admin.decomiso.precursor.create_precursor')
@endcan

@include('admin.decomiso.precursor.edit_precursor')

@can('crear decomisos de armas')
@include('admin.decomiso.arma.create_arma')
@endcan

@include('admin.decomiso.arma.edit_arma')

@can('crear decomisos de municiones')
@include('admin.decomiso.municion.create_municion')
@endcan

@include('admin.decomiso.municion.edit_municion')

@can('crear detenidos en decomisos')
@include('admin.decomiso.detenido.create_detenido')
@endcan

@include('admin.decomiso.detenido.edit_detenido')

@can('crear decomisos de transportes')
@include('admin.decomiso.transporte.create_transporte')
@endcan

@include('admin.decomiso.transporte.edit_transporte')

<div class="container" id="contenedor_principal">
    <div class="row">
        <div class="col-md-4">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Nuevo decomiso</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form class="form-horizontal" data-parsley-validate action="{{route('decomiso.update',$decomiso)}}" method="POST"
                    id="form_edit_decomiso">
                    {{csrf_field()}}

                    <input name="_method" value="PUT" type="hidden">
                    <input name="municipio_id_s" id="municipio_id_s" value={{$decomiso->municipio_id}} type="hidden">
                    <div class="card-body">
                        <div class="form-group row">
                            <label for="inputFecha_" class="col-sm-2 col-form-label">Fecha</label>
                            <div class="col-sm-10">
                                <input required data-parsley-trigger="keyup" type="text" name="fecha_" class="form-control" id="inputFecha_"
                                    value="{{old('fecha_',$decomiso->fecha)}}" autocomplete="off" placeholder="Fecha" data-parsley-pattern-message="El formato de la fecha es incorrecto" data-parsley-pattern="^(\d{4})(\/|-)(0[1-9]|1[0-2])(\/|-)([0-2][0-9]|3[0-1])$">
                                @include('admin.partials.mensages_error', ['nombre' => 'fecha_'])
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputObservacion_" class="col-sm-4 col-form-label">Observación</label>
                            <div class="col-sm-8">
                                <input type="text" required name="observacion_" class="form-control" id="inputObservacion_"
                                    value="{{old('observacion_',$decomiso->observacion)}}" placeholder="Observación" autocomplete="off">
                                @include('admin.partials.mensages_error', ['nombre' => 'observacion_'])
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputDirección_" class="col-sm-3 col-form-label">Dirección</label>
                            <div class="col-sm-9">
                                <input type="text" required name="direccion_" class="form-control" id="inputDirección_"
                                    value="{{old('direccion_',$decomiso->direccion)}}" placeholder="Dirección" autocomplete="off">
                                @include('admin.partials.mensages_error', ['nombre' => 'direccion_'])
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="inputDep_" class="col-sm-5 col-form-label">Departamento</label>
                            <div class="col-sm-7">
                                <select class="form-select select2" name="inputDep_" id="inputDep_" value="">
                                    @foreach($departamentos as $departamento)
                                    {{--  <option value="" selected disabled hidden>Selecciona departamento</option>  --}}
                                    <option value={{$departamento->id}}>{{$departamento->nombre}}</option>
                                    @endforeach
                                </select>

                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputMun_" class="col-sm-5 col-form-label">Municipio</label>
                            <div class="col-sm-7">
                                <select class="form-select" data-placeholder="Selecciona municipio" name="municipio_id_" id="inputMun_"
                                    value="{{old('municipio_id_')}}" required>
                                    <option value="" selected disabled hidden>Selecciona el municipio</option>
                                </select>
                                @include('admin.partials.mensages_error', ['nombre' => 'municipio_id_'])
                            </div>
                        </div>
                        {{--  <div class="form-group row">
                            <label for="inputDep" class="col-sm-5 col-form-label">Ubicación</label>
                            <div class="col-sm-7">

                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#MapaModal"><i class="fas fa-map-marked"></i></button>
                                    <input id="latitud" name="latitud" type="hidden" value={{$decomiso->latitud}}>
                                    <input id="longitud" name="longitud" type="hidden" value={{$decomiso->longitud}}>
                            </div>
                        </div>  --}}


                        <div class="form-group row">
                            <label for="latitud" class="col-sm-4 col-form-label">Latitud</label>
                            <div class="col-sm-6">
                                <input type="text" name="latitud" class="form-control form-control-sm" id="latitud"
                                    value="{{old('latitud', $decomiso->latitud)}}" placeholder="Latitud"  autocomplete="off"
                                    required data-parsley-maxlength="21" min="12" max="16.6" step="0.01"
                                    data-parsley-validation-threshold="1" data-parsley-trigger="keyup"
                                    data-parsley-type="number" >
                                @include('admin.partials.mensages_error', ['nombre' => 'latitud'])
                            </div>
                            <div class="col-sm-2">
                                <button type="button" class="btn btn-primary" id="generar" data-placement="bottom" title="Comprobar ubicación" data-toggle="modal" data-target="#MapaModal"><i class="fas fa-map-marked"></i></button>
                            </div>
                            <label for="longitud" class="col-sm-4 col-form-label">Longitud</label>
                            <div class="col-sm-6">
                                <input type="text" name="longitud" class="form-control form-control-sm" id="longitud"
                                    value="{{old('longitud',$decomiso->longitud)}}" placeholder="Longitud" autocomplete="off"
                                    required data-parsley-maxlength="21" min="-89.4" max="-82" step="0.01"
                                    data-parsley-validation-threshold="1" data-parsley-trigger="keyup"
                                    data-parsley-type="number" >
                                @include('admin.partials.mensages_error', ['nombre' => 'longitud'])
                            </div>
                        </div>
                        {{--  <div class="form-group row">
                            <label for="inputDep_" class="col-sm-5 col-form-label">Departamento</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" id="inputDep_"
                                    value="" placeholder="Departamento" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputMun_" class="col-sm-3 col-form-label">Municipio</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="inputMun_"
                                    value="" placeholder="Municipio" readonly>
                            </div>
                        </div>  --}}

                        <div class="form-group row">
                            <label for="inputInsti_" class="col-sm-4 col-form-label">Institución</label>
                            {{--  <div class="col-sm-5">  --}}
                                <select class="form-select col-sm-5" style="width:170%" name="institucion_id_" id="inputInsti_"
                                    value="{{old('institucion_id_')}}">
                                    @foreach($instituciones as $institucion)
                                    {{--  <option value="" selected disabled hidden>Selecciona institucion</option>  --}}
                                    <option value={{$institucion->id}}>{{$institucion->nombre}}</option>
                                    @endforeach
                                    @include('admin.partials.mensages_error', ['nombre' => 'institucion_id'])
                                </select>
                            {{--  </div>  --}}
                        </div>
                        <div class="form-group row">

                            {{--  <div class="col-sm-7">  --}}
                                <label for="inputInstiones" class="col-form-label">Colaboradores</label>
                                <select class="form-select form-control-sm" multiple="multiple" data-placeholder="Selecciona colaboradores" style="width: 165%" name="instituciones_id[]" id="inputInstiones" value="{{old('instituciones_id')}}">

                                    @foreach($instituciones as $institucion)
                                    {{--  <option value="" selected disabled hidden>Selecciona colaboradores</option>  --}}
                                    {{--  <option value={{in_array($institucion->id, ($decomiso->instituciones->pluck('id')->toArray()))?$institucion->id. 'selected':$institucion->id}}>{{$institucion->nombre}}</option>  --}}
                                    <option {{ collect(old('permisos', $decomiso->instituciones->pluck('id')))->contains($institucion->id)? 'selected':''}} value="{{$institucion->id}}">{{$institucion->nombre}}</option>
                                    @endforeach
                                </select>
                                @include('admin.partials.mensages_error', ['nombre' => 'instituciones_id'])
                            {{--  </div>  --}}
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                    @can('crear decomiso')
                        <button type="submit" id="guardar_deco" class="btn btn-primary">Guardar</button>
                    @endcan
                    </div>
                    <!-- /.card-footer -->
                </form>

            </div>
        </div>

        @include('admin.decomiso.mapa', ['depto' => 'inputDep_', 'munio' => 'inputMun_', 'latitud' => 'latitud', 'longitud' => 'longitud'])

        <div class="col-md-8">
            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                @can('ver decomisos de droga')
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="pills-drogas-tab" data-toggle="pill" href="#pills-drogas" role="tab"
                        aria-controls="pills-drogas" aria-selected="true">Drogas</a>
                </li>
                @endcan
                @can('ver decomisos de precursores')
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="pills-precursores-tab" data-toggle="pill" href="#pills-precursores"
                        role="tab" aria-controls="pills-precursores" aria-selected="false">Precursores</a>
                </li>
                @endcan
                @can('ver decomisos de armas')
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="pills-armas-tab" data-toggle="pill" href="#pills-armas" role="tab"
                        aria-controls="pills-armas" aria-selected="false">Armas</a>
                </li>
                @endcan
                @can('ver decomisos de municiones')
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="pills-municiones-tab" data-toggle="pill" href="#pills-municiones" role="tab"
                        aria-controls="pills-municiones" aria-selected="false">Municiones</a>
                </li>
                @endcan
                @can('ver detenidos en decomisos')
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="pills-detenidos-tab" data-toggle="pill" href="#pills-detenidos" role="tab"
                        aria-controls="pills-detenidos" aria-selected="false">Detenidos</a>
                </li>
                @endcan
                @can('ver decomisos de transportes')
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="pills-transportes-tab" data-toggle="pill" href="#pills-transportes"
                        role="tab" aria-controls="pills-transportes" aria-selected="false">Transportes</a>
                </li>
                @endcan
            </ul>
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-drogas" role="tabpanel"
                    aria-labelledby="pills-drogas-tab">
                    @can('ver decomisos de droga')
                    @include('admin.decomiso.droga.index_droga')
                    @endcan
                </div>
                <div class="tab-pane fade" id="pills-precursores" role="tabpanel"
                    aria-labelledby="pills-precursores-tab">
                    @can('ver decomisos de precursores')
                    @include('admin.decomiso.precursor.index_precursor')
                    @endcan
                </div>
                <div class="tab-pane fade" id="pills-armas" role="tabpanel" aria-labelledby="pills-armas-tab">
                    @can('ver decomisos de armas')
                    @include('admin.decomiso.arma.index_arma')
                    @endcan
                </div>
                <div class="tab-pane fade" id="pills-municiones" role="tabpanel" aria-labelledby="pills-municiones-tab">
                    @can('ver decomisos de municiones')
                    @include('admin.decomiso.municion.index_municion')
                    @endcan
                </div>
                <div class="tab-pane fade" id="pills-detenidos" role="tabpanel" aria-labelledby="pills-detenidos-tab">
                    @can('ver detenidos en decomisos')
                    @include('admin.decomiso.detenido.index_detenido')
                    @endcan
                </div>
                <div class="tab-pane fade" id="pills-transportes" role="tabpanel"
                    aria-labelledby="pills-transportes-tab">
                    @can('ver decomisos de transportes')
                    {{--  <table id="transporte-table" style="width:100%" class="table table-bordered table-striped table-sm table-hover" style="width:100%">  --}}
                    @include('admin.decomiso.transporte.index_transporte')
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@push('scripts')


<script>
//initMap();

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
  tipocalendario('#inputFecha_','YYYY-MM-DD')
  $('#inputFecha_').data("DateTimePicker").maxDate(new Date());

    //alert(@json(basename($_SERVER['REQUEST_URI'])));
    //@json(session()->get('status'))


    //var url_ =@json(basename($_SERVER['REQUEST_URI']));
    //alert(url_);
    //url_ = url_.substr(12, 17);
    //$("#pills-tab a[href='#" + url_ + "']").tab('show');
    //alert(@json(session() -> get('modelo')));
    //$("#pills-tab a[href='#pills-"+@json($permiso_tab)+"']").tab('show'); //#pills-municiones


    //////////////////////////
    $("#inputDep_").on('change', function () {
        llenarMunicipios__($("#inputDep_").val());
    });

    function llenarMunicipios__(id) {
        //alert(id);
        $("#inputMun_").empty();
        @json($municipios).forEach(function (item) {
            if (item.departamento_id == id) {
                $("#inputMun_").append('<option value="' + item.id + '">' + item.nombre + '</option>' +
                    '<option value="" selected disabled hidden>Selecciona el municipio</option>');
            }
        });
    }


    $("#inputDep_").val(@json($decomiso -> municipio -> departamento_id));
    llenarMunicipiosEdit(@json($decomiso -> municipio -> departamento_id));

    function llenarMunicipiosEdit(id) {
        //alert(@json($decomiso -> municipio -> departamento_id));
        $("#inputMun_").empty();
        @json($municipios).forEach(function (item) {
            if (item.departamento_id == id) {
                $("#inputMun_").append('<option value="' + item.id + '">' + item.nombre + '</option>' +
                    '<option value="" selected disabled hidden>Selecciona el municipio</option>');
            }
        });
    }

    {{--  $("#inputDep_").val(@json($decomiso -> municipio -> id));  --}}
    $("#inputMun_").val(@json($decomiso -> municipio -> id));
    $("#inputInsti_").val(@json($decomiso -> institucion_id));


    if (@json(session() -> get('modelo')) != null) {

        console.log(@json(session()-> get('modal')));
        $("#pills-tab a[href='" +@json(session() -> get('modelo')) +"']").tab('show');
        $("" +@json(session() -> get('modal')) +"").modal('toggle');
        //alert("jjkjdkjfkdjkdfjfk");
        var table = $('#droga-table').DataTable();
        $("" +@json(session() -> get('form1')) +"").attr('action',@json(session() -> get('formRoute')) +@json(session() -> get('formID')));
        $("" +@json(session() -> get('form2')) +"").attr('action', @json(session() -> get('formRoute')) +@json(session() -> get('formID')));
        $(@json(session() -> get('oculto'))).val(@json(session() -> get('formID')));

        {{ session() -> forget('modelo')}}
        {{ session() -> forget('modal')}}
        {{ session() -> forget('form1')}}
        {{ session() -> forget('form2')}}
        {{ session() -> forget('formID')}}
        {{ session() -> forget('formRoute')}}
        {{ session() -> forget('oculto')}}
    }else{
       $("#pills-tab a[href='#pills-"+@json($permiso_tab)+"']").tab('show');
    }
    {{--  alert(@json(htmlspecialchars($_GET["algo"])));  --}}
    {{--  @if (Session:: has('errors'))

            $('#muis').text("asdlkfjñ");
            $('#muis').text("asdlkfjñ");
            alert(@json(session() -> get('status')));
            alert(@json($errors -> all()));
            alert(@json(Session:: forget('errors')));

            @foreach($errors -> all() as $error)
            $('#muis').text("asdlkfjñ");
            @endforeach

            @endif--}}




            /////////////////////////////////llenar tablas///////////////////////////////////


////////////////////////

$('#inputDep_, #inputMun_, #inputInsti_, #inputInstiones').select2({
    theme: 'bootstrap4',
});
</script>

@endpush
