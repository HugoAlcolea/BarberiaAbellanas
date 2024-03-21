<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil - Barberia Abel·lanas</title>
    <link rel="stylesheet" href="{{ asset('css/navbar/perfil.css') }}">
</head>

<body>
    <div class="perfil-container">
        <div class="perfil">
            <div class="banner">
                <div class="linea-separativa1"></div>
            </div>

            <div class="logo-perfil">
                @php
                $profileImagePath = "storage/profile_images/" . Auth::user()->profile_image;

                if (file_exists(public_path($profileImagePath))) {
                echo "<img src='" . asset($profileImagePath) . "' alt='Perfil de " . Auth::user()->username . "'>";
                } else {
                echo "<img src='" . asset(' storage/profile_images/default.jpg') . "' alt='Imagen por defecto'>" ; }
                    @endphp </div>


                <div class="nombre-perfil">
                    @php
                    $user = Auth::user();
                    if ($user) {
                    echo "<h2>{$user->username} #{$user->id}</h2>";
                    }
                    @endphp
                </div>

                <div class="espacio-separativo">
                    <p>Estadisticas:</p>
                    <div class="linea-separativa2"></div>
                </div>

                <div class="datos-usuario">
                    <div class="estadisticas">
                        <span class="dato">
                            <p>Edad</p>
                            <p>{{ \Carbon\Carbon::parse($user->date_of_birth)->age }}</p>
                        </span>
                        <span class="dato">
                            <p>Haircuts</p>
                            <p>{{ optional($user->stats)->haircuts }}</p>
                        </span>
                        <span class="dato">
                            <p>Puntos</p>
                            <p>{{ optional($user->stats)->points }}</p>
                        </span>
                    </div>
                </div>
            </div>
        </div>

</body>

</html>