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
                <h1 class="text-center">Iniciar sesión</h1>

                @if(session('error'))
                <div class="alert alert-danger" role="alert">
                    {{ session('error') }}
                </div>
                @endif

                <form action="{{ route('login.post') }}" method="post">
                    @csrf
                    <div class="mb-3">
                        <label for="username" class="form-label">Nombre de usuario:</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña:</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3 text-center">
                        <p calss="p_google">Inicia Sesion tambien con:</p>
                        <a href="/google-auth/redirect">
                            <img src="https://cdn1.iconfinder.com/data/icons/google-s-logo/150/Google_Icons-09-512.png"
                                alt="Iniciar sesión con Google" class="google-logo">
                        </a>
                    </div>

                    <div class="mb-3 text-center">
                        <button type="submit" class="btn btn-primary">Entrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
