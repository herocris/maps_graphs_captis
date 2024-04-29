<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ config('app.name') }}</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="/adminlte/plugins/fontawesome-free/css/all.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="/adminlte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- para estilo de datetime jquery -->

  <!-- Estilo de para validaciones de formularios -->
  <link href="/parsley.css" rel="stylesheet">
  <!-- Theme style -->
  <link rel="stylesheet" href="/adminlte/css/adminlte.min.css">
  <!-- boton para activación de dos factores -->
  <link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">
  <!-- apariencia de select2 -->
  <link href="/adminlte/plugins/select2/css/select2.css" rel="stylesheet">
  <link href="/adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.css" rel="stylesheet">
  <!-- Estilo de para Jquery Multi select -->.
  <link href="/jQuery-Multiple-Select-Plugin-For-Bootstrap-Bootstrap-Multiselect/dist/css/bootstrap-multiselect.min.css" rel="stylesheet">
  <!-- apariencia de virtual select -->
  <link href="/virtual-select-master/dist/virtual-select.min.css" rel="stylesheet">

  <link href="/adminlte/plugins/datatables/jquery.dataTables.css" rel="stylesheet">
  <link href="/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet">
  <link href="/adminlte/plugins/datatables-fixedcolumns/css/fixedColumns.dataTables.min.css" rel="stylesheet">
  <link href="/adminlte/plugins/datatables-fixedheader/css/fixedHeader.dataTables.min.css" rel="stylesheet">
  <link href="/adminlte/plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.min.css" rel="stylesheet">
  <link href="/adminlte/plugins/datatables-fixedheader/css/fixedHeader.bootstrap4.min.css" rel="stylesheet">
  <!-- apariencia de datetimepicker -->
  <link rel="stylesheet" href="/datetimepicker/bootstrap-datetimepicker.min.css">


    <style>

      .dataTables_wrapper .dataTables_processing {
        position: absolute;
        top: 50% !important;
          background: #FFFFCC;
          border: 1px solid black;
          border-radius: 3px;
          font-weight: bold;
          z-index: 2;
        }

    .dark-mode .select2-selection--single .select2-selection__rendered {
      color: white;
    }

    .dark-mode .select2-container--bootstrap4 .select2-selection--single .select2-selection__arrow b {
        border-color: white transparent transparent transparent;
    }

    .dark-mode .select2-container--bootstrap4 .select2-selection {
        background-color: #343a40;
        border: 1px solid #6c757d;
    }

    .dark-mode .select2-container--bootstrap4 .select2-dropdown .select2-results__option[aria-selected="true"] {
        color: white;
        background-color: #f2f2f2;
    }



    .dark-mode .vscomp-toggle-button {
      background-color: #343a40;
    }

    .dark-mode .vscomp-wrapper {
      color: #b4b4b4;
    }

    .dark-mode .vscomp-arrow::after {
      border-right-color: white;
      border-bottom-color: white;
    }

    .dark-mode .vscomp-options-container  {
      background-color: #343a40;
      color: #ffffff;
    }


    .dark-mode .vscomp-search-container {
      background-color: #343a40;
      color: #ffffff;
    }

    .dark-mode .vscomp-option.selected {
      background-color: #8c8c8c;
    }

    @if (Auth::user()->oscuro ==1)
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_processing,
        .dataTables_wrapper .dataTables_paginate {
            color: #fff;
        }
        table.dataTable thead th, table.dataTable thead td {
            padding: 10px;
            border-bottom: 1px solid rgb(107 116 124);
        }

        table.dataTable.cell-border tbody tr th:first-child, table.dataTable.cell-border tbody tr td:first-child {
            border-left: 1px solid rgb(107 116 124);
        }

        table.dataTable.cell-border tbody th, table.dataTable.cell-border tbody td {
            border-top: 1px solid rgb(107 116 124);
            border-right: 1px solid rgb(107 116 124);
        }

        table.dataTable.hover > tbody > tr:hover > *, table.dataTable.display > tbody > tr:hover > * {
            box-shadow: inset 0 0 0 9999px rgb(59, 100, 131);
        }


    @endif

    {{--  #user-table tbody tr {
        cursor: pointer;
    }  --}}





  </style>

  @stack('styles')
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
  <div class="wrapper">

    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center">
      <img class="animation__wobble" src="/logocaptis.png" alt="AdminLTELogo" height="65" width="65">
    </div>


    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand  navbar-light">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        {{--  <li class="nav-item d-none d-sm-inline-block">
          <a href="index3.html" class="nav-link">Home</a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
          <a href="#" class="nav-link">Contact</a>
        </li>  --}}
      </ul>

      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">

        {{--  <li class="nav-item">
          Modo oscuro
          <input type="checkbox" id="dark_modes" onchange= modo_oscuro() name="my-checkbox" checked data-bootstrap-switch data-off-color="light" data-on-color="secondary">
        </li>  --}}

        @if (Auth::user()->oscuro ==1)
          <form method="POST" action="{{route('oscuro.oscuro', Auth::user())}}">
              {{csrf_field()}}
              {{method_field('PUT')}}
              <input name="oscuro" type="hidden" value=0>
                  Modo oscuro
                  <input type="checkbox" id="dark_mode" onchange= "this.form.submit()" name="my-checkbox"  checked data-bootstrap-switch data-off-color="light" data-on-color="secondary">
          </form>
        @else
          <form method="POST" action="{{route('oscuro.oscuro',Auth::user())}}">
                {{csrf_field()}}
                {{method_field('PUT')}}
                <input name="oscuro" type="hidden" value=1>
                    Modo oscuro
                    <input type="checkbox" id="dark_mode" onchange= "this.form.submit()" name="my-checkbox" data-bootstrap-switch data-off-color="light" data-on-color="secondary">
          </form>
        @endif

        <li class="nav-item">
          <a class="nav-link" data-widget="fullscreen" href="#" role="button">
            <i class="fas fa-expand-arrows-alt"></i>
          </a>
        </li>

        <li>
          <a href="{{route('logout')}}" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fas fa-power-off"></i> Salir
          </a>
          <form id="logout-form" action="{{route('logout')}}" method="POST" style="display: none">
            @csrf
          </form>
        </li>

      </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <a href="{{route('index')}}" class="brand-link" style="text-align: center;">
        <img src="/logocaptis.png" height="55px" width="65px" alt="AdminLTE Logo"
          style="opacity: .8">
        <!-- <span class="brand-text font-weight-light">Captis</span> -->
      </a>
      {{--  <br>  --}}
      <!-- Sidebar -->
      <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-5 pb-1 mb-1 d-flex">
          <div class="image">
            <img src="/adminlte/img/avatar_user.png" class="img-circle elevation-3" alt="User Image">
          </div>
          <div class="info">
            <p  class="d-block dark-mode">{{ Auth::user()->name }}</p>
          </div>
        </div>

        <!-- SidebarSearch Form -->
        <!-- <div class="form-inline">
          <div class="input-group" data-widget="sidebar-search">
            <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
              <button class="btn btn-sidebar">
                <i class="fas fa-search fa-fw"></i>
              </button>
            </div>
          </div>
        </div> -->

        <!-- Sidebar Menu -->
        @include('admin.partials.nav')
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      @yield('header')
      <!-- /.content-header -->

      <!-- Main content -->
      @if (session()->has('flash'))
        <div class="alert alert-success" onload="alerta_op()">{{ session('flash')}}</div>
      @endif
      @yield('content')
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->

    <!-- Main Footer -->
    <footer class="main-footer">
      <strong>Copyright &copy; 2022 Captis. Departamento de Desarrollo de Software</strong>
      Todos los derechos reservados.
      <div class="float-right d-none d-sm-inline-block">
        <b>Version</b> 2.0.0
      </div>
    </footer>
  </div>
  <!-- ./wrapper -->

  <!-- REQUIRED SCRIPTS -->
  <!-- jQuery -->
  <script src="/adminlte/plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap -->
  <script src="/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- overlayScrollbars -->
  <script src="/adminlte/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
  <!-- DataTables  & Plugins -->
  <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
  <!-- <script src="/adminlte/plugins/datatables/jquery.dataTables.min.js"></script> -->
  <script src="/adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
  <script src="/adminlte/plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.min.js"></script>
  <script src="/adminlte/plugins/datatables-fixedheader/js/dataTables.fixedHeader.min.js"></script>
  <script src="/adminlte/plugins/datatables-fixedcolumns/js/fixedColumns.bootstrap4.min.js"></script>
  <script src="/adminlte/plugins/datatables-fixedheader/js/fixedHeader.bootstrap4.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.1.0/js/dataTables.buttons.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.1.0/js/buttons.html5.min.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.1.0/js/buttons.print.min.js"></script>

  <!-- Para plugin de calendario datetimepicker -->
  <script src="/datetimepicker/moment.min.js"></script>
  <script src="/datetimepicker/es-mx.js"></script>
  <script src="/datetimepicker/bootstrap-datetimepicker.min.js"></script>

  <!-- Para validación de formularios -->
  <script src="/parsley.min.js"></script>
  <script src="/es.js"></script>
  <!-- Select2 plugin -->
  <script src="/adminlte/plugins/select2/js/select2.full.js"></script>
  <!-- Multi select plugin -->
  <script src="/jQuery-Multiple-Select-Plugin-For-Bootstrap-Bootstrap-Multiselect/dist/js/bootstrap-multiselect.min.js"></script>
  <!-- Virtual select plugin -->
  <script src="/virtual-select-master/dist/virtual-select.min.js"></script>

  <!-- SheetJS plugin -->
  <script src="/xlsx.full.min.js"></script>

  <!-- pdfmake plugin -->
  <script src='/pdfmake.min.js'></script>
  <script src='/vfs_fonts.js'></script>



  {{--  <script type="text/javascript" src="https://unpkg.com/default-passive-events"></script>  --}}
  @stack('scripts')

  <!-- AdminLTE App -->
  <script src="/adminlte/js/adminlte.js"></script>

  <!-- PAGE PLUGINS -->
  <!-- jQuery Mapael -->
  <script src="/adminlte/plugins/jquery-mousewheel/jquery.mousewheel.js"></script>
  <script src="/adminlte/plugins/raphael/raphael.min.js"></script>
  <script src="/adminlte/plugins/jquery-mapael/jquery.mapael.min.js"></script>
  <script src="/adminlte/plugins/jquery-mapael/maps/usa_states.min.js"></script>

  <!-- ChartJS -->
  <script src="/adminlte/plugins/chart.js/Chart.min.js"></script>
  <!-- boton para activación de dos factores -->
  <script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>

  <!-- Bootstrap Switch -->
  <script src="/adminlte/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>

  <!-- AdminLTE for demo purposes -->
  {{--  <script src="/adminlte/js/demo.js"></script>  --}}
  <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
  <!-- <script src="/adminlte/js/pages/dashboard2.js"></script> -->

  <script>
        $(function () {
            var table=$('#user-table').DataTable({
                order: [[0, 'desc']],
                "columnDefs": [
                    {
                        "targets": [0],
                        "visible": false,
                        "searchable": false
                    },
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
                    extend: 'excelHtml5',
                    text: '<i class="fas fa-file-excel"></i>',
                    titleAttr: 'Exportar a excel',
                    title: "",
                    className: 'btn btn-success',
                },
                ,
                {
                    extend: 'pdfHtml5',
                    text: '<i class="fas fa-file-pdf"></i>',
                    titleAttr: 'Exportar a PDF',
                    title: "",
                    className: 'btn btn-danger',

                },
            ],

            });

            // $('#user-table thead tr').clone(true).appendTo( '#user-table thead' );
            // $('#user-table thead tr:eq(1) th').each( function (i) {
            //   //alert($('#user-table thead tr:eq(1) th').length);
            //   if(i!=($('#user-table thead tr:eq(1) th').length)-1){
            //     var title = $(this).text();
            //     $(this).html( '<input type="text" style="width:100%"  />' );

            //     $( 'input', this ).on( 'keyup change', function () {
            //         if ( table.column(i).search() !== this.value ) {
            //             table
            //                 .column(i)
            //                 .search( this.value )
            //                 .draw();
            //         }
            //     });
            //   }else{
            //     $(this).html( '<label></label>' );
            //   }

            // });




            //$('.form-select').select2({
              //theme: 'bootstrap4',
            //});
        });




        $("#dark_mode").bootstrapSwitch();

        modo_oscuro(@json(Auth::user()->oscuro));

        function modo_oscuro(usuario){
          if(usuario==1){
            $("body").toggleClass("dark-mode");
          $("nav").toggleClass("navbar-dark");
          }
        }
       // alert(@json(Session::get('response'));


        function alerta_op(){
          alert("hizo algo");
        }
        //history.forward(); ///comando para impedir que vuelva a la pagina atras cuando se haya cerrado sesión
    </script>

</body>

</html>
