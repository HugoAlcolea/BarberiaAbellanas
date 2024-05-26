<!-- resources/views/login.blade.php -->

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <script src="{{ asset('js/login.js') }}"></script>

</head>

<body>
    <div class="center-box">
        <div class="box">
            <span></span>
            <a href="{{ route('index') }}" class="back-button">
                <img src="{{ asset('img/arrow-icon.png') }}" alt="Volver al login">
            </a>
            <div class="container">
                <h1 class="text-center">Bienvenido a Barberia Abel·lanas</h1>
                <div class="text-center">
                    <a href="/google-auth/redirect" class="btn btn-primary small-button">
                        <img src="https://cdn1.iconfinder.com/data/icons/google-s-logo/150/Google_Icons-09-512.png"
                            alt="Iniciar sesión con Google" class="google-logo">
                        Iniciar / Registrar
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>