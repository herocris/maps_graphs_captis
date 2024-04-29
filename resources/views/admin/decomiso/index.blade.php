@extends('admin.layout')


@section('header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Decomisos</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('index')}}">Inicio</a></li>
                <li class="breadcrumb-item active">Decomisos</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
@stop
@section('content')
    <div class="card">
        <div class="card-header d-flex">
            <h3 class="card-title">Listado de decomisos</h3>
            @can('ver decomisos deshabilitados')
            <div class="nav-item ml-auto">
            {{--  @if($dehabilitado)
                <form method="GET" action="{{route('decomiso.index')}}">  --}}
                Ver registros:
                    <input type="checkbox" id="desabilitados" checked data-toggle="toggle" data-on="Habilitados" data-off="Deshabilitados" data-onstyle="success" data-offstyle="danger">
                {{--  </form>
            @else
                <form method="GET" action="{{route('decomiso.show', 1)}}">
                Ver registros:
                    <input type="checkbox" onChange="this.form.submit()" checked data-toggle="toggle" data-on="Habilitados" data-off="Deshabilitados" data-onstyle="success" data-offstyle="danger">
                </form>
            @endif  --}}
            </div>
            @endcan
            @can('crear decomiso')
            <a href="{{route('decomiso.create')}}" class="btn btn-success ml-auto" data-toggle="tooltip" data-placement="bottom" title="Crear decomiso"><i class="fas fa-plus-circle"></i></a>
            @endcan
        </div>
        <div id="paginacion_deco">
            @include('admin.decomiso.paginacion')
        </div>
    </div>
@stop
@push('scripts')
<script>



// $('#crud-table thead tr').clone(true).appendTo('#crud-table thead');

// $('#crud-table thead tr:eq(1) th').each(function (i) {
//     var title = $(this).text();

//     $(this).html('<input type="text" style="width:100%" />');




// });

//crear_tabla();
// $('#crud-table thead th').each(function() {
//     var title = $(this).text();
//     $(this).html('<input type="text" placeholder="Search ' + title + '" />');
//   });
  //function crear_tabla (){


var imprimir=false;
var imprimirPDF=false;
var imprimirXLS=false;

////////////creación de tabla///////////////
var table=$('#crud-table').DataTable({
    processing: true,
    serverSide: true,
    pageLength : 10,
    scrollX: true,
    orderCellsTop: true,
    search: {
        return: true,
    },
    //bFilter: false,
    order:[[0, "desc"]],
    ajax:{
        "url": "{{ url('decomiso')}}", ////////acción que llena la tabla/////////
        "data": function ( d ) {//////////variables extra enviadas a la acción que llena la tabla////////////
            d.fec_ini = $('#fec_ini').val();
            d.fec_fin = $('#fec_fin').val();
            @if(auth()->user()->can('ver decomisos deshabilitados'))
            d.desabilitados= $("#desabilitados").is(":checked");
            @else
            d.desabilitados= true;
            @endif
            //

            d.imprimir = imprimir;
        },
        "dataFilter": function(response){/////////////respuesta del ajax que llena la tabla//////////////
            var obj = JSON.parse(response);//////////////conversión de a JSON//////////////////
            var decomisos=obj.imprimir;

            if(imprimirPDF==true){
                imprimirDOCPDF(decomisos)
            }
            if(imprimirXLS==true){
                imprimirDOCXLS(decomisos)
            }

            return response
        }
    },
    columns:[
        {data:'fecha'},
        {data:'observacion'},
        {data:'direccion'},
        {data:'departamento'},
        {data:'municipio'},
        {data:'creador'},
        {data:'actualizador'},
        {data:'institucion'},
        {data:'botones'},
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
  //}

  //alert("llega");

function imprimirDOCPDF(datos){/////////////////función para exportar a PDF/////////////////
    var registros=[];
    var encabe=[ 'Fecha', 'Observacion', 'Direccion', 'Departamento','Municipio','Institucion' ];
    registros.push(encabe);

    datos.forEach(function(decomiso) {
        var deco=[ decomiso.fecha , decomiso.observacion , decomiso.direccion , decomiso.departamento, decomiso.municipio, decomiso.institucion ];
        registros.push(deco);
    });

    var docDefinition = {
        content: [
            {
            layout: 'lightHorizontalLines', // optional
            table: {
                    headerRows: 1,
                    widths: [ 65, 'auto', 100, 77, 75, '*' ],
                    body:registros
                }
            }
        ]
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
    pdfMake.createPdf(docDefinition).download('Listado_de_decomisos_Captis.pdf');
    imprimirPDF = false;
    imprimir = false;
}

function imprimirDOCXLS(datos){/////////////////////////función para exportar a Excell/////////////////////
    var wbout;
    var wb = XLSX.utils.book_new();
    wb.Props = {
        Title: "Captis",
        Subject: "Listado de decomisos Captis",
        Author: "Captis",
        //CreatedDate: fecha
        };
    wb.SheetNames.push("Hoja1");
    var rows =[]

    if(imprimir==true){
        datos.forEach(function(decomiso) {
            var deco={ fecha: decomiso.fecha, observacion: decomiso.observacion, direccion: decomiso.direccion, departamento: decomiso.departamento, municipio: decomiso.municipio, institucion: decomiso.institucion };
            rows.push(deco);
        });
    }
    var ws = XLSX.utils.json_to_sheet(rows);

    wb.Sheets["Hoja1"]= ws;
    wbout = XLSX.write(wb, {bookType:'xlsx',  type: 'binary'});
    saveAs(new Blob([s2ab(wbout)],{type:"application/octet-stream"}), 'Listado_de_decomisos_Captis.xlsx');

    imprimir = false;
    imprimirXLS = false;
}

function s2ab(s) { /////////////////////función complementaria para la función para exportar excell//////////////
    var buf = new ArrayBuffer(s.length); //convert s to arrayBuffer
    var view = new Uint8Array(buf);  //create uint8array as viewer
    for (var i=0; i<s.length; i++) view[i] = s.charCodeAt(i) & 0xFF; //convert to octet
        return buf;
}



    //$('#crud-table thead tr:eq(1) th').each( function (i) {
    //    if(i!=($('#crud-table thead tr:eq(1) th').length)-1){
    //        var title = $(this).text();
    //        $(this).html( '<input type="text" style="width:100%"  />' );

    //        $( 'input', this ).on( 'keyup change', function () {
    //            if ( table.column(i).search() !== this.value ) {
    //                table
    //                   .column(i)
    //                    .search( this.value )
    //                    .draw();
    //            }

                //buscar_col(1, this.value)
    //        });
    //    }else{
    //        $(this).html( '<label></label>' );
    //    }
    //});



    //console.log(table.context[0].oLanguage.sEmptyTable);
    //table.context[0].oLanguage.sEmptyTable="algo";
    //console.log(table.context[0].oLanguage.emptyTable);



$(document).on('click','.pagination a', function(e){/////////////////////función de paginación de la tabla///////////////////
    e.preventDefault();
    var page = $(this).attr('href').split('page=')[1];
});

$('.filter-input').keyup(function (e) {////////////función para filtrado de columnas/////////////////////
    if (e.keyCode == 13 || this.value == "") {
        table.columns($(this).data('column'))
            .search($(this).val())
            .draw();
    }
});


$("#desabilitados").change(function(){////////////////////////función para mostrar decomisos deshabilitados//////////////////
    table.draw();
});

$(".dataTables_filter input")////////////////////substituyendo el evento keyup que viene por defecto y aplicando el presionado de Enter//////////////
    .unbind()
    .bind('keyup change', function (e) {
    if (e.keyCode == 13 || this.value == "") {
        table.search(this.value)
            .draw();
    }
});


    // $(document).on('click','.pagination li', function(e){
    //     $('.pagination li').removeClass( "active" );
    //     $(this).addClass( "active" );
    //     //$(this).text("zdgf")
    //     //alert($( '.page-item:contains("1")' ).text());
    //     //$('.page-link:contains("1")').css('background-color', 'red');
    //     //console.log($( '.page-item:has(span)' ));
    //     //if($(this).text()!="1"){
    //         //$( '.page-item:contains("1")' ).append( "<a class='page-link' href='http://captis.test/decomiso?page=1'>1</a>" );
    //        // $( '.page-link:contains("1")' ).remove('span');
    //     //}

    //     //$('.page-item:has(span)').append( "<a class='page-link' href='http://captis.test/decomiso?page=1'>1</a>" );

    // });









</script>

@endpush
