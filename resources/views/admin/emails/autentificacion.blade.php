
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>Autenticación de dos pasos</h1>
    {{--  <p>Nombre de usuario: {{$contancto['name']}}</p>
    <p>Correo de usuario: {{$contancto['email']}}</p>  --}}

    <strong>Ahora tienes habilitado 2FA, escanea el siguiente codigo QR en tu aplicación de tu
        telefono.</strong>
    <div id="svg-container">
        <br>
        {!! $contancto->twoFactorQrCodeSvg() !!}

        <br>

    </div>
    {{--  <img src="{{$message->embed(public_path(). '/logocaptis.png')}}">  --}}
    {{--  <img src="{{ $message->embedData($contancto->twoFactorQrCodeSvg(), 'example-image.png') }}">  --}}
    {{--  <canvas id="myCanvas"></canvas>  --}}
    
    
    <strong>Por favor copia estos codigos en un lugar seguro:</strong>
    <br>
    @foreach(json_decode(decrypt($contancto->two_factor_recovery_codes, true)) as $code)
    {{ trim($code) }}<br>
    @endforeach
    
    
</body>

</html>