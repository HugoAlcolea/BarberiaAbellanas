<!-- resources/views/login.blade.php -->

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/ticket.css') }}">

</head>

<body>
    <div class="formulario-container">
        <div class="contenedor">
            <div class="pestaña">
                <span class="circulo rojo"></span>
                <span class="circulo amarillo"></span>
                <span class="circulo verde"></span>
            </div>
        </div>
        <br>
        <div class="registro-container">
            <div class="ticket text-center">
                <h1>¡Gracias por Reservar!</h1>
                <p><strong>{{ $cita->usuario->name }} {{ $cita->usuario->surname }}</strong>, has reservado cita
                    el día {{ $cita->fecha }} a
                    las {{ $cita->hora }},</p>
                <p>Tu código de reserva es:</p>
                <h1 class="codigo" style="color: red">{{ $cita->codigo }}</h1>
                <a href="{{ route('view') }}" class="btn btn-primary mt-3 boton" style="background-color: white; color:black; border: 2px solid black">Volver al principio</a>
            </div>
        </div>
    </div>
</body>

</html>