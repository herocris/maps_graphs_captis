<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/adminlte/plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/adminlte/css/adminlte.min.css">
</head>

<body class="hold-transition login-page" style="background-color: #454d55;">
    <div class="login-box" style="width: 25rem;">
        <div class="login-logo">
            <a><b style="color: white;">Cargar base de datos</b></a>
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <form class="form-horizontal" action="{{route('respaldo.cargarRespaldo')}}" method="POST" id="form_arma"
                    enctype="multipart/form-data">
                    {{csrf_field()}}
                    <div class="card-body">
                        <div class="custom-file">
                            <input type="file" accept=".sql" class="custom-file-input" id="inputGroupFile04" name="base">
                            <label class="custom-file-label" for="inputGroupFile04">Selecciona base de datos</label>
                            
                            @include('admin.partials.mensages_error', ['nombre' => 'base'])
                        </div>
                    </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Generar</button>
            </div>
            </form>
        </div>
        <!-- /.login-card-body -->
    </div>
    </div>
    <!-- /.login-box -->

    <!-- jQuery -->
    <script src="/adminlte/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="/adminlte/js/adminlte.min.js"></script>
    <script>
        history.forward();
    </script>
</body>



</html>