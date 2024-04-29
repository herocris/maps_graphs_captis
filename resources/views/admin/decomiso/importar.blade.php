@extends('admin.layout')

@section('header')

    <div  id="pps" style="display:none;">df</div>


<div class="container-fluid">
    <div class="row">
        <div class="col-sm-6">
            <h1 class="m-0">Importar decomisos</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('decomiso.index')}}">Decomisos</a></li>
                <li class="breadcrumb-item active">Importar decomisos</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
@stop
@section('content')
<div class="container" style="width: 60%;">

    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Importar</h3>
        </div>
        <div class="card-body">
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <button class="btn btn-primary" id="carga" type="button">Cargar</button>
                </div>
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="inputGroupFile03" accept=".xlsx, .xls, .csv">
                    <label class="custom-file-label" id="label_inputf" for="inputGroupFile03"></label>
                </div>
            </div>
            <div class="progress">
                <div class="progress-bar" role="progressbar" id="progreso" style="width: 0%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">25%</div>
            </div>
        </div>
        {{--  <form data-parsley-validate class="form-horizontal" action="{{route('droga.store')}}" method="POST" id="form_droga">
            @include('admin.droga.form')
        </form>  --}}
    </div>

</div>

@stop
@push('scripts')
<script src="/xlsx.full.min.js"></script>
<script>

    var excelJsonObj=[];
    var decomisos=[];
    var subdecomisos=[];
    $("#carga").prop('disabled', true);
    $("#inputGroupFile03").click(function(e){
        $('#inputGroupFile03').val('');
    });
    $("#inputGroupFile03").change(function(e){

        var myfile=document.getElementById('inputGroupFile03');
        var input=myfile;
        var reader = new FileReader();
        var respuesta="";
        reader.onload = function(){
            
            var fileData = reader.result;
            var workbook = XLSX.read(fileData, {type: 'binary'});
            workbook.SheetNames.forEach(function(sheetName){
                
                var rowObject = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[sheetName]);
                //console.log(rowObject);
                if (Object.keys(rowObject[0])[0]=="Numero" && Object.keys(rowObject[0])[1]=="Fecha" && Object.keys(rowObject[0])[2]=="Observacion" && Object.keys(rowObject[0])[3]=="Direccion") {
                    rowObject.forEach(function(elem, index){
                        // $("#progreso").css("width", (100/rowObject.length).toFixed(2)*index+'%');
                        // $("#progreso").empty();
                        // $("#progreso").append(Math.round((100/rowObject.length).toFixed(2)*index)+'%');
                        //alert((100/rowObject.length).toFixed(2));
                        
                    });
                    excelJsonObj = rowObject;
                    $("#progreso").css("width", '0%');
                    $("#progreso").empty();
                    $("#label_inputf").empty();
                    $("#label_inputf").append(input.files[0]['name']);
                    $("#carga").prop('disabled', false);
                    $( "#pps" ).slideUp();
                } else {
                    $("#progreso").css("width", '0%');
                    $("#progreso").empty();
                    respuesta="El formato del documento no coinside con el requerido";
                }
            });

            if (respuesta!="") {
                $("#label_inputf").empty();
                $('#inputGroupFile03').val('');
                excelJsonObj=[];
                //alert(respuesta);
                animar_alerta(respuesta);
            }
            
            console.log(excelJsonObj);
            // for(var i=0; i<excelJsonObj.length; i++){
            //     var itemsubdecomiso = {"numero":excelJsonObj[i]['Numero'],"cantidad":excelJsonObj[i]['Cantidad'], "peso":excelJsonObj[i]['Peso'], "droga_id":excelJsonObj[i]['Id_Droga'], "presentacion_droga_id":excelJsonObj[i]['Id_Presentacion']};
            //     var itemdecomiso = {"fecha":excelJsonObj[i]['Fecha'], "observacion":excelJsonObj[i]['Observacion'], "direccion":excelJsonObj[i]['Direccion'], "municipio_id":excelJsonObj[i]['Mun_id'], "latitud":excelJsonObj[i]['Latitud'], "longitud":excelJsonObj[i]['Longitud'], "institucion_id":excelJsonObj[i]['Int_id'], "subdecomiso":[]};
                
            //     if (i==0) {
            //         itemdecomiso.subdecomiso.push(itemsubdecomiso);
            //         decomisos.push(itemdecomiso);
            //     } else if(excelJsonObj[i]['Direccion']==decomisos[decomisos.length-1]['direccion']) {
            //         decomisos[decomisos.length-1].subdecomiso.push(itemsubdecomiso);
            //     }else{
            //         itemdecomiso.subdecomiso.push(itemsubdecomiso);
            //         decomisos.push(itemdecomiso);
            //     }
            // }
            //console.log(decomisos);
            
            
            
        };
        //console.log(input.files.length);
        if (input.files.length>0) {
            reader.readAsBinaryString(input.files[0]);
        }
        
        //////    

    });

    $("#carga").click(function(){
        // var algo=[
        //     decomisos[0],
        //     decomisos[1],
        //     // decomisos[2],
        //     // decomisos[3],
        //     // decomisos[4],
        // ];

        if(excelJsonObj.length>0){
            $.ajax({
                type:'POST',
                url:"{{ route('decomiso.decomiso_importar') }}",
                dataType: 'json',


                data:{
                    parametro:JSON.stringify(excelJsonObj), //para post demaciado grandes hay que serializar la data para enviarla al controlador como texto para posteriormente serializarla
                    _token: "{{ csrf_token() }}",
                    },
                
                success: function (response) {
                    var respuesta="";
                    console.log(response.porcentage);

                    

                    if (response.respuesta!="La exportacion se realizo correctamente") {
                        response.respuesta.forEach(element => {
                            respuesta=respuesta+"<p>"+element+"</p>";
                        }); 
                        //$("#label_inputf").empty();
                        //$('#inputGroupFile03').val('');
                        //excelJsonObj=[];
                        $("#carga").prop('disabled', true);
                        //$( ".container-fluid" ).append( "<div class='alert alert-danger' onload='alerta_op()'>"+respuesta+"</div>" );
                        //alert(respuesta);
                        animar_alerta(respuesta);
                        $("#progreso").css("width", response.porcentage+'%');
                        $("#progreso").empty();
                        $("#progreso").append((response.porcentage).toFixed(2)+'%');
                    }else{
                        animar_alerta(response.respuesta)
                        //alert(response.respuesta);
                        $("#progreso").css("width", response.porcentage+'%');
                        $("#progreso").empty();
                        $("#progreso").append(Math.round(response.porcentage)+'%');
                        $("#carga").prop('disabled', true);
                    }
                    
                    
                },
            }); 
        }else{
            //alert("debe cargar un archivo");
            animar_alerta("debe cargar un archivo");
        }
        
    });

    function animar_alerta(mensage){
        //$( ".container-fluid" ).append( "<div class='alert alert-danger'>prueba</div>" );
        if (mensage!="La exportacion se realizo correctamente") {
            //alert(mensage);
            $( "#pps" ).slideUp();
            $( "#pps" ).removeClass($("#pps").attr("class"));
            $( "#pps" ).addClass("alert alert-danger");
            $( "#pps" ).slideDown();
            $( "#pps" ).empty();
            $( "#pps" ).append(mensage);
        } else {
            $( "#pps" ).slideUp();

            $( "#pps" ).removeClass($("#pps").attr("class"));
            $( "#pps" ).addClass("alert alert-success");
            $( "#pps" ).slideDown();
            $( "#pps" ).empty();
            $( "#pps" ).append(mensage);   
        }
        
        //alert("ji");
        // $("#ppps").show().animate({
        //     height: '100px',
        //     //height: "easeOutBounce"
        //     //display:'none',
        //     opacity:'1'
        //   },2000);

        // $("#ppps").animate({
        //     height: '100px',
        //     //height: "easeOutBounce"
        //     display:'none',
        //   },1000);
    }
</script>
@endpush