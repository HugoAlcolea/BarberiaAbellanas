<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket de Cita</title>
    <link rel="stylesheet" href="{{ asset('css/ticket.css') }}">
</head>

<body>
    <div class="ticket">
        <h1>¡Gracias por reservar en Barbería Abellanas!</h1>
        <p><strong>{{ $usuario->name }} {{ $usuario->surname }}</strong>, has reservado cita el día {{ $cita->fecha }} a
            las {{ $cita->hora }},</p>
        <p>Tu código de reserva es:</p>
        <h1 class="codigo" style="color: red">{{ $cita->codigo }}</h1>
    </div>
</body>

</html>