<!-- resources/views/login.blade.php -->

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body>
    <div class="center-box">
        <div class="box">
            <span></span>
            <a href="{{ route('index') }}" class="back-button">
                <img src="{{ asset('img/arrow-icon.png') }}" alt="Volver al login">
            </a>
            <div class="container">
                <h1>Iniciar sesión</h1>

                @if(session('error'))
                <div class="error-message">
                    {{ session('error') }}
                </div>
                @endif

                <form action="{{ route('login.post') }}" method="post">
                    @csrf
                    <div class="input-group">
                        <label for="username">Nombre de usuario:</label>
                        <input type="text" id="username" name="username" required>
                    </div>
                    <div class="input-group">
                        <label for="password">Contraseña:</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <div class="button-group">
                        <button type="submit" class="small-button">Entrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>


</html>