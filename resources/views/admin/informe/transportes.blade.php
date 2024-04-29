@extends('admin.layout')

@section('header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Transportes</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('index')}}">Inicio</a></li>
                <li class="breadcrumb-item active">Transportes</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
@stop
@section('content')
<div class="card">
    <div class="card-header d-flex">
        <h3 class="card-title">Listado de transportes decomisados</h3>
        
    </div>
    <!-- /.card-header -->
    <div class="card-body" >
        @include('admin.partials.fechas') 
        <table id="transporte-table" style="width:150%" class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>Placa</th>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Color</th>
                    <th>Tipo</th>
                    <th>País procedencia</th>
                    <th>Departamento procedencia</th>
                    <th>Municipio procedencia</th>
                    <th>País destino</th>
                    <th>Departamento destino</th>
                    <th>Municipio destino</th>
                    <th>Fecha</th>

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
                    <td >
                        <input type="text"  class="form-control filter-input" data-column="6" />
                    </td>
                    <td>
                        <input type="text"  class="form-control filter-input" data-column="7" />
                    </td>
                    <td>
                        <input type="text"  class="form-control filter-input" data-column="8" />
                    </td>
                    <td>
                        <input type="text"  class="form-control filter-input" data-column="9" />
                    </td>
                    <td>
                        <input type="text"  class="form-control filter-input" data-column="10" />
                    </td>
                    <td>
                        <input type="text"  class="form-control filter-input" data-column="11" />
                    </td>

                </tr>
            </thead>
            {{--  <tbody>
                @foreach($decomisos_transportes as $transporte)
                <tr>
                    <td>{{$transporte->placa}}</td>
                    <td>{{$transporte->marca}}</td>
                    <td>{{$transporte->modelo}}</td>
                    <td>{{$transporte->color}}</td>
                    <td>{{$transporte->tipo}}</td>
                    <td>{{$transporte->paiss($transporte->pais_pro_id)}}</td>
                    <td>{{$transporte->depto($transporte->dep_pro_id)}}</td>
                    <td>{{$transporte->muni($transporte->mun_pro_id)}}</td>
                    <td>{{$transporte->paiss($transporte->pais_des_id)}}</td>
                    <td>{{$transporte->depto($transporte->dep_des_id)}}</td>
                    <td>{{$transporte->muni($transporte->mun_des_id)}}</td>
                    <td>{{$transporte->fecha}}</td>
                    
                </tr>
                @endforeach
            </tbody>  --}}
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
    var table=$('#transporte-table').DataTable({
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
            "url": "{{ url('decomisotransporte')}}", ////////acción que llena la tabla/////////
            "data": function ( d ) {//////////variables extra enviadas a la acción que llena la tabla////////////
                d.fec_ini = $('#fec_ini').val();
                d.fec_fin = $('#fec_fin').val();
                //d.desabilitados= $("#desabilitados").is(":checked");
                d.imprimir = imprimir;
            },
            "dataFilter": function(response){/////////////respuesta del ajax que llena la tabla//////////////
                var obj = JSON.parse(response);//////////////conversión de a JSON//////////////////
                var actividades=obj.imprimir;
//console.log(obj);
                if(imprimirPDF==true){
                    imprimirDOCPDF(actividades)
                }
                if(imprimirXLS==true){
                    imprimirDOCXLS(actividades)
                }
                //$('#bitacora-table').attr('width', 500);
                return response
            }  
        },
        columnDefs: [
        { "width": "20%", "targets": 3 }
    ],
    
        columns:[
            {data:'placa'},
            {data:'marca'},
            {data:'modelo'},
            {data:'color'},
            {data:'tipo'},
            {data:'pais_pr'},
            {data:'pais_de'},
            {data:'dep_pr'},
            {data:'dep_de'},
            {data:'mun_pr'},
            {data:'mun_de'},
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
        var encabe=[ 'Placa', 'Marca', 'Modelo', 'Color','Tipo','País procedencia','Departamento procedencia','Municipio procedencia','País destino','Departamento destino','Municipio destino','Fecha' ];
        registros.push(encabe);

        datos.forEach(function(transportes) {
            var deco=[ transportes.placa, transportes.marca, transportes.modelo, transportes.color, transportes.tipo, transportes.pais_pr, transportes.pais_de, transportes.dep_pr, transportes.dep_de, transportes.mun_pr, transportes.mun_de, transportes.fecha ];
            registros.push(deco);
        });

        var docDefinition = {
            content: [
                {
                layout: 'lightHorizontalLines', // optional
                table: {
                        headerRows: 1,
                        widths: [ 'auto', 'auto', 'auto', 'auto', 'auto', 'auto', 'auto', 'auto', 'auto', 'auto', 'auto', 'auto'],
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
        pdfMake.createPdf(docDefinition).download('Listado_de_decomisos_de_transportes_Captis.pdf');
        imprimirPDF = false;
        imprimir = false;
    }

    function imprimirDOCXLS(datos){/////////////////////////función para exportar a Excell/////////////////////
        var wbout;
        var wb = XLSX.utils.book_new();
        wb.Props = {
            Title: "Captis",
            Subject: "Listado de decomisos de transportes Captis",
            Author: "Captis",
            //CreatedDate: fecha
            };
        wb.SheetNames.push("Hoja1");
        var rows =[]    
        
        if(imprimir==true){
            console.log(datos);
            datos.forEach(function(transporte) {
                var deco={ Placa: transporte.placa, Marca: transporte.marca, Modelo: transporte.modelo, Color: transporte.color, Tipo: transporte.tipo, País_procedencia: transporte.pais_pr, Departamento_procedencia: transporte.dep_pr, Municipio_procedencia: transporte.mun_pr, País_destino: transporte.pais_de, Departamento_destino: transporte.dep_de, Municipio_destino: transporte.mun_de};
                rows.push(deco);
            });
        }
        var ws = XLSX.utils.json_to_sheet(rows);

        wb.Sheets["Hoja1"]= ws;
        wbout = XLSX.write(wb, {bookType:'xlsx',  type: 'binary'});
        saveAs(new Blob([s2ab(wbout)],{type:"application/octet-stream"}), 'Listado_de_transportes_decomisados_Captis.xlsx');

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



</script>
@endpush