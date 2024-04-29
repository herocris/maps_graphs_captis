@extends('admin.layout')

@section('header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Drogas decomisadas</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('index')}}">Inicio</a></li>
                <li class="breadcrumb-item active">Drogas decomisadas</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
@stop
@section('content')
<div class="card">
    <div class="card-header d-flex">
        <h3 class="card-title">Listado de decomisos de drogas</h3>

    </div>
    <!-- <button class="btn btn-danger btn-block" onclick="pdf_s()">
        prueba pdf
    </button> -->
    <!-- /.card-header -->
    <div class="card-body">
        @include('admin.partials.fechas')
        <table id="droga-table" class="table table-bordered table-striped table-hover" style="width:100%">
            <thead>
                <tr>
                    <th>Descripción</th>
                    <th>Presentación</th>
                    <th>Cantidad</th>
                    <th>Peso</th>
                    <th>Departamento</th>
                    <th>Municipio</th>
                    <th>Fecha</th>
                    <th>Decomiso_id</th>

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
                    <td>
                        <input type="text"  class="form-control filter-input" data-column="5" />
                    </td>
                    <td>
                        <input type="text"  class="form-control filter-input" data-column="6" />
                    </td>
                    <td>
                        <input type="text"  class="form-control filter-input" data-column="6" />
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





    var imprimir=false;
    var imprimirPDF=false;
    var imprimirXLS=false;

    ////////////creación de tabla///////////////
    var table=$('#droga-table').DataTable({
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
            "url": "{{ url('decomisodroga')}}", ////////acción que llena la tabla/////////
            "data": function ( d ) {//////////variables extra enviadas a la acción que llena la tabla////////////
                d.fec_ini = $('#fec_ini').val();
                d.fec_fin = $('#fec_fin').val();
                //d.desabilitados= $("#desabilitados").is(":checked");
                d.imprimir = imprimir;
            },
            "dataFilter": function(response){/////////////respuesta del ajax que llena la tabla//////////////
                //console.log(response);
                var obj = JSON.parse(response);//////////////conversión de a JSON//////////////////
                var decomisosDrogas=obj.imprimir;

                if(imprimirPDF==true){
                    imprimirDOCPDF(decomisosDrogas)
                }
                if(imprimirXLS==true){
                    imprimirDOCXLS(decomisosDrogas)
                }
                //$('#bitacora-table').attr('width', 500);
                return response
            }
        },
        columnDefs: [
        { "width": "20%", "targets": 3 },
        {
            target: 7,
            visible: false,
            searchable: false
        }
    ],

        columns:[
            {data:'droga'},
            {data:'presentacion_droga'},
            {data:'cantidad'},
            {data:'peso'},
            {data:'departamento'},
            {data:'municipio'},
            {data:'fecha'},
            {data:'decomiso_id'},
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
        var encabe=[ 'Descripción', 'Presentación', 'Cantidad', 'Peso', 'Departamento', 'Municipio','Fecha' ];
        registros.push(encabe);

        datos.forEach(function(deco_droga) {
            var deco=[ deco_droga.droga, deco_droga.presentacion_droga, deco_droga.cantidad, deco_droga.peso, deco_droga.departamento, deco_droga.municipio, deco_droga.fecha ];
            registros.push(deco);
        });

        var docDefinition = {
            content: [
                {
                layout: 'lightHorizontalLines', // optional
                table: {
                        headerRows: 1,
                        widths: [ 'auto', 'auto', 'auto', 'auto', 'auto','auto','auto'],
                        body:registros
                    }
                }
            ],
            //pageOrientation: 'landscape',
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
        pdfMake.createPdf(docDefinition).download('Listado_de_decomisos_de_drogas_Captis.pdf');
        imprimirPDF = false;
        imprimir = false;
    }

    function imprimirDOCXLS(datos){/////////////////////////función para exportar a Excell/////////////////////
        var wbout;
        var wb = XLSX.utils.book_new();
        wb.Props = {
            Title: "Captis",
            Subject: "Listado de decomisos de drogas Captis",
            Author: "Captis",
            //CreatedDate: fecha
            };
        wb.SheetNames.push("Hoja1");
        var rows =[]

        if(imprimir==true){
            console.log(datos);
            datos.forEach(function(deco_droga) {
                var deco={ Descripción: deco_droga.droga, Presentación: deco_droga.presentacion_droga, Cantidad: deco_droga.cantidad, Peso: deco_droga.peso, Departamento: deco_droga.departamento, Municipio: deco_droga.municipio, Fecha: deco_droga.fecha};
                rows.push(deco);
            });
        }
        var ws = XLSX.utils.json_to_sheet(rows);

        wb.Sheets["Hoja1"]= ws;
        wbout = XLSX.write(wb, {bookType:'xlsx',  type: 'binary'});
        saveAs(new Blob([s2ab(wbout)],{type:"application/octet-stream"}), 'Listado_de_decomisos_de_droga_Captis.xlsx');

        imprimir = false;
        imprimirXLS = false;
    }

    function s2ab(s) { /////////////////////función complementaria para la función para exportar excell//////////////
        var buf = new ArrayBuffer(s.length); //convert s to arrayBuffer
        var view = new Uint8Array(buf);  //create uint8array as viewer
        for (var i=0; i<s.length; i++) view[i] = s.charCodeAt(i) & 0xFF; //convert to octet
            return buf;
    }



    $('.filter-input').keyup(function (e) {////////////función para filtrado de columnas/////////////////////
        if (e.keyCode == 13 || this.value == "") {
            table.columns($(this).data('column'))
                .search($(this).val())
                .draw();
        }
    });

    $(".dataTables_filter input")////////////////////substituyendo el evento keyup que viene por defecto y aplicando el presionado de Enter//////////////
    .unbind()
    .bind('keyup change', function (e) {
        if (e.keyCode == 13 || this.value == "") {
            table.search(this.value)
                .draw();
        }
    });

    table.on('click', 'td', function (e) {
        window.open("admin/decomiso/"+table.row( this ).data()['decomiso_id']+"/edit")
    });



</script>
@endpush
