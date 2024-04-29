@extends('admin.layout')

@section('header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Actividad</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('index')}}">Inicio</a></li>
                <li class="breadcrumb-item active">Actividad</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
@stop
@section('content')
<div class="card">
    <div class="card-header d-flex">
        <h3 class="card-title">Listado de actividades</h3>
        
    </div>
    <!-- /.card-header -->
    <div class="card-body">
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
        <table id="bitacora-table" class="table table-bordered table-striped table-hover" style="width:100%">
            <thead>
                <tr>
                    <th >Usuario</th>
                    <th >Institución</th>
                    <th>Descripción</th>
                    <th>Datos</th>
                    <th >Fecha</th>
                </tr>
                <tr>
                    <td >
                        <input type="text"  class="form-control filter-input" data-column="0" />
                    </td>
                    <td>
                        <input type="text"  class="form-control filter-input" data-column="1" />
                    </td>
                    <td>
                        <input type="text"  class="form-control filter-input" data-column="2" />
                    </td>
                    <td>
                        <input type="text"  class="form-control filter-input" data-column="3" />
                    </td>
                    <td>
                        <input type="text"  class="form-control filter-input" data-column="4" />
                    </td>

                </tr>
            </thead>
            <tbody>
                
            </tbody>
        </table>
    </div>
    <!-- /.card-body -->
</div>
@stop
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
          $(id).data("DateTimePicker").maxDate(new Date());
      }
      tipocalendario('#fec_ini','YYYY-MM-DD')
      tipocalendario('#fec_fin','YYYY-MM-DD')

    var imprimir=false;
    var imprimirPDF=false;
    var imprimirXLS=false;
        
    ////////////creación de tabla///////////////
    var table=$('#bitacora-table').DataTable({
        processing: true,
        serverSide: true,
        //autoWidth: true,
        //scrollCollapse: true,
        //autoWidth: true,
        scrollX: true,
        pageLength : 10,
        orderCellsTop: true,
        search: {
            return: true,
        },
        //bFilter: false,
        order:[[4, "desc"]],
        ajax:{
            "url": "{{ url('admin/bitacora')}}", ////////acción que llena la tabla/////////
            "data": function ( d ) {//////////variables extra enviadas a la acción que llena la tabla////////////
                d.fec_ini = $('#fec_ini').val();
                d.fec_fin = $('#fec_fin').val();
                //d.desabilitados= $("#desabilitados").is(":checked");
                d.imprimir = imprimir;
            },
            "dataFilter": function(response){/////////////respuesta del ajax que llena la tabla//////////////
                console.log(response);
                var obj = JSON.parse(response);//////////////conversión de a JSON//////////////////
                var actividades=obj.imprimir;

                if(imprimirPDF==true){
                    imprimirDOCPDF(actividades)
                }
                if(imprimirXLS==true){
                    imprimirDOCXLS(actividades)
                }
                //$('#bitacora-table').attr('width', 500);
                return response
            },
            "error": function(jqXHR, ajaxOptions, thrownError) {
                  alert(thrownError + "\r\n" + jqXHR.statusText + "\r\n" + jqXHR.responseText + "\r\n" + ajaxOptions.responseText);
                }  
        },
        columnDefs: [
            { "width": "20px", "targets": 3}
        ],
    
        columns:[
            {data:'log_name'},
            {data:'institucion'},
            {data:'description'},
            {data:'properties'},
            {data:'fecha'}
        ],
        language: {
            "decimal": "",
            "emptyTable": "No hay información",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
            "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
            "infoFiltered": "(Filtrado de _MAX_ total entradas)",
            "infoPostFix": "",
            "thousands": ",",
            "lengthMenu": "Mostrar _MENU_ Entradas",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "search": "Buscar:",
            "zeroRecords": "Sin resultados encontrados",
            "paginate": {
                "first": "Primero",
                "last": "Ultimo",
                "next": "Siguiente",
                "previous": "Anterior"
            }
        },
        dom: 'Bfrtip',
        buttons: [
            {
                //extend: 'excelHtml5',
                text: '<i class="fas fa-file-excel"></i>',
                action: function ( e, dt, node, config ) {
                    imprimir = true;
                    imprimirXLS = true;
                    table.draw()
                },
                titleAttr: 'Exportar a excel',
                title: "",
                className: 'btn btn-success',
            },
            
            {
                //extend: 'pdfHtml5',
                text: '<i class="fas fa-file-pdf"></i>',
                action: function ( e, dt, node, config ) {
                    imprimir = true;
                    imprimirPDF = true;
                    table.draw()
                },
                titleAttr: 'Exportar a PDF',
                title: "",
                className: 'btn btn-danger',
            },
        ] 
    });



    function imprimirDOCPDF(datos){/////////////////función para exportar a PDF/////////////////
        var registros=[];
        var encabe=[ 'Usuario', 'Institución', 'Descripción', 'Datos','Fecha' ];
        registros.push(encabe);

        datos.forEach(function(actividad) {
            var deco=[ actividad.log_name, actividad.institucion,actividad.description,actividad.properties, actividad.fecha ];
            registros.push(deco);
        });

        var docDefinition = {
            content: [
                {
                layout: 'lightHorizontalLines', // optional
                table: {
                        headerRows: 1,
                        widths: [ 'auto', 105, 105, 290, 'auto'],
                        body:registros
                    }
                }
            ],
            pageOrientation: 'landscape',
        };
        pdfMake.tableLayouts = {
            exampleLayout: {
                hLineWidth: function (i, node) {
                    if (i === 0 || i === node.table.body.length) {
                        return 0;
                    }
                    return (i === node.table.headerRows) ? 2 : 1;
                },
                vLineWidth: function (i) {
                    return 0;
                },
                hLineColor: function (i) {
                    return i === 1 ? 'black' : '#aaa';
                },
                paddingLeft: function (i) {
                    return i === 0 ? 0 : 8;
                },
                paddingRight: function (i, node) {
                    return (i === node.table.widths.length - 1) ? 0 : 8;
                }
            }
        };
        pdfMake.createPdf(docDefinition).download('Listado_de_actividades_Captis.pdf');
        imprimirPDF = false;
        imprimir = false;
    }

    function imprimirDOCXLS(datos){/////////////////////////función para exportar a Excell/////////////////////
        var wbout;
        var wb = XLSX.utils.book_new();
        wb.Props = {
            Title: "Captis",
            Subject: "Listado de actividades Captis",
            Author: "Captis",
            //CreatedDate: fecha
            };
        wb.SheetNames.push("Hoja1");
        var rows =[]    
        
        if(imprimir==true){
            console.log(datos);
            datos.forEach(function(actividad) {
                var deco={ Usuario: actividad.log_name, Institución: actividad.institucion, Descripción: actividad.description, Datos: actividad.properties, Fecha: actividad.fecha};
                rows.push(deco);
            });
        }
        var ws = XLSX.utils.json_to_sheet(rows);

        wb.Sheets["Hoja1"]= ws;
        wbout = XLSX.write(wb, {bookType:'xlsx',  type: 'binary'});
        saveAs(new Blob([s2ab(wbout)],{type:"application/octet-stream"}), 'Listado_de_actividades_Captis.xlsx');

        imprimir = false;
        imprimirXLS = false;
    }

    function s2ab(s) { /////////////////////función complementaria para la función para exportar excell//////////////
        var buf = new ArrayBuffer(s.length); //convert s to arrayBuffer
        var view = new Uint8Array(buf);  //create uint8array as viewer
        for (var i=0; i<s.length; i++) view[i] = s.charCodeAt(i) & 0xFF; //convert to octet
            return buf;    
    }
    
    $(document).on('click','.pagination a', function(e){/////////////////////función de paginación de la tabla///////////////////
        e.preventDefault();
        var page = $(this).attr('href').split('page=')[1];
        //alert("2332");
        //table.columns.adjust().draw();
    });

    $('.filter-input').keyup(function () {////////////función para filtrado de columnas/////////////////////
        table.columns($(this).data('column'))
            .search($(this).val())
            .draw();
            
    });



</script>
@endpush   