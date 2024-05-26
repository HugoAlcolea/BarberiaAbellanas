<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Solicitar cita</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>

    <link rel="stylesheet" href="{{ asset('css/formulario_cita.css') }}">
</head>

<body>
    <div class="center-box">
        <div class="box">
            <span></span>
            <a href="{{ route('index') }}" class="back-button">
                <img src="{{ asset('img/arrow-icon.png') }}" alt="Volver al inicio">
            </a>
            <div class="container" id="main-container">
                <h1>Solicitar cita</h1>

                <form id="cita-form" action="{{ route('guardar.cita') }}" method="POST">
                    @csrf
                    <input type="hidden" id="google-calendar-access-token" value="{{ $accessToken }}">
                    <input type="hidden" id="nombreUsuario" name="nombreUsuario" value="{{ Auth::user()->name }}">
                    <input type="hidden" id="apellidoUsuario" name="apellidoUsuario"
                        value="{{ Auth::user()->surname }}">
                    <div class="input-group">
                        <label for="fecha">Fecha:</label>
                        <input type="date" id="fecha" name="fecha" min="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <div class="input-group">
                        <label for="hora">Hora:</label>
                        <select class="form-select selectMax" id="hora" name="hora" required>
                            <option value="" selected disabled>-- Selecciona una hora --</option>
                            <option value="09:00">09:00</option>
                            <option value="09:30">09:30</option>
                            <option value="10:00">10:00</option>
                            <option value="10:30">10:30</option>
                            <option value="11:00">11:00</option>
                            <option value="11:30">11:30</option>
                            <option value="12:00">12:00</option>
                            <option value="12:30">12:30</option>
                            <option value="13:00">13:00</option>
                            <option value="" disabled>-- Horas de la Tarde --</option>
                            <option value="16:00">16:00</option>
                            <option value="16:30">16:30</option>
                            <option value="17:00">17:00</option>
                            <option value="17:30">17:30</option>
                            <option value="18:00">18:00</option>
                            <option value="18:30">18:30</option>
                            <option value="19:00">19:00</option>
                            <option value="19:30">19:30</option>
                            <option value="20:00">20:00</option>
                        </select>
                    </div>
                    <div class="input-group">
                        <label for="barbero">Barbero:</label>
                        <select class="form-select selectMax" id="barbero" name="barbero" required>
                            <option value="" selected disabled>-- selecciona un barbero --</option>
                            @foreach($barberos as $barbero)
                            <option value="{{ $barbero->nombre }}">{{ $barbero->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="input-group">
                        <label for="servicio">Servicio:</label>
                        <select class="form-select selectMax" id="servicio" name="servicio" required>
                            <option value="" selected disabled>-- selecciona un servicio --</option>
                            @foreach($servicios as $servicio)
                            <option value="{{ $servicio->nombre }}">{{ $servicio->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="button-group">
                        <button type="submit" class="small-button" id="saveChangesBtn">Enviar</button>
                    </div>
                </form>
                <div id="processing-message" style="display: none;">
                    <p>Procesando solicitud...</p>
                </div>
                <div id="download-form" style="display: none;">
                    <form id="download-form" action="{{ route('descargar.pdf', ['cita' => $cita->id]) }}" method="POST">
                        @csrf
                        <button type="submit" class="small-button" id="downloadBtn">Descargar PDF</button>
                    </form>
                    <p>Este proceso peude tardar unos 20seg, espere porfavor</p>
                    <h3>Para cancelar la cita pongase en contacto con el Barbero</h3>
                </div>
            </div>
        </div>
    </div>


    <script async defer src="https://apis.google.com/js/api.js" onload="gapiLoaded()"></script>
    <script async defer src="https://accounts.google.com/gsi/client" onload="gisLoaded()"></script>
    <script src="{{ asset('js/cita.js') }}"></script>

</body>

</html>