<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barberia Abel·lanas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"
        integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous">
    </script>
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <script src="{{ asset('js/index.js') }}"></script>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-transparent" style="margin-top: 20px">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <div class="nav-link" data-text="Home">
                            <ion-icon name="home-outline"></ion-icon>
                            <span class="nav-text">Home</span>
                        </div>
                    </li>
                    @if(Auth::check())
                    <li class="nav-item">
                        <div class="nav-link" data-text="Perfil">
                            <ion-icon name="person-outline"></ion-icon>
                            <span class="nav-text">Perfil</span>
                        </div>
                    </li>
                    @endif
                    <li class="nav-item">
                        <div class="nav-link" data-text="Fotos">
                            <ion-icon name="camera-outline"></ion-icon>
                            <span class="nav-text">Fotos</span>
                        </div>
                    </li>
                    @if(Auth::check())
                    <li class="nav-item">
                        <div class="nav-link" data-text="Settings">
                            <ion-icon name="settings-outline"></ion-icon>
                            <span class="nav-text">Settings</span>
                        </div>
                    </li>
                    <li class="nav-item">
                        <div class="nav-link" data-text="Logout">
                            <a href="{{ route('logout') }}" class="text-white">
                                <ion-icon name="log-out-outline"></ion-icon>
                                <span class="nav-text">Logout</span>
                            </a>
                        </div>
                    </li>


                    @else
                    <li class="nav-item">
                        <div class="nav-link" data-text="Login">
                            <a href="{{ route('login') }}" class="text-white">
                                <ion-icon name="log-in-outline"></ion-icon>
                                <span class="nav-text">Login</span>
                            </a>
                        </div>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <div id="home" class="tabcontent">
        <section class="hero">
            <div class="container">
                <h2>Bienvenido a Barbería Abel·lanas</h2>
                <p>Somos tu mejor opción para el cuidado de tu barba y cabello, ¡donde sea que estés!</p>
                <div class="video-container">
                    <video id="introVideo" width="900" height="auto" autoplay loop style="border-radius: 15px;">
                        <source src="{{ asset('video/videointro.mp4') }}" type="video/mp4">
                        Tu navegador no soporta el elemento de video.
                    </video>
                    <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        var video = document.getElementById("introVideo");
                        var startTime = 7;
                        var endTime = 67.5;

                        video.volume = 0.10;
                        video.currentTime = startTime;

                        video.addEventListener('timeupdate', function() {
                            if (video.currentTime >= endTime) {
                                video.currentTime = startTime;
                                video.play();
                            }
                        });

                        video.addEventListener('seeked', function() {
                            if (video.currentTime < startTime) {
                                video.currentTime =
                                    startTime;
                                video.play();
                            }
                        });
                    });
                    </script>
                </div>
                <div calss="button-container" style="margin-top: 30px">
                    @if(Auth::check())
                    <p>¡Reserva tu cita ahora mismo!</p>
                    <a href="{{ route('cita.formulario') }}" class="btn btn-primary btn-lg"
                        style="margin-top: -15px !important">Reservar cita</a>
                    @else
                    <p>Regístrate para reservar una cita y disfrutar de nuestros servicios exclusivos.</p>
                    @endif
                </div>
            </div>
        </section>
    </div>

    <div id="perfil" class="tabcontent">
        <div class="perfil-container">
            <div class="perfil">
                <div class="banner">
                    <div class="linea-separativa1"></div>
                </div>

                <div class="logo-perfil">
                    @php
                    if (Auth::check() && Auth::user()->profile_image) {
                    $profileImagePath = "storage/profile_images/" . Auth::user()->profile_image;
                    if (file_exists(public_path($profileImagePath))) {
                    echo "<img src='" . asset($profileImagePath) . "' alt='Perfil de " . Auth::user()->username . "'>";
                    } else {
                    echo "<img src='" . asset('storage/profile_images/default.jpg') . "' alt='Imagen por defecto'>" ; }
                        } @endphp </div>

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
                        @if(Auth::check())
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
                        @endif
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="formulario-container col-md-6"
                        style="background-color: white; border-radius: 15px; padding: 20px;">
                        <div class="contenedor">
                            <div class="pestaña">
                                <span class="circulo rojo"></span>
                                <span class="circulo amarillo"></span>
                                <span class="circulo verde"></span>
                            </div>
                        </div>
                        <br>
                        <div class="registro-container" style="margin-top: -10px">
                            <div class="ticket text-center">
                                @if($citaMasCercana)
                                <div class="row">
                                    <div class="col-sm-6"><strong>Fecha:</strong></div>
                                    <div class="col-sm-6"><strong>Hora:</strong></div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        {{ \Carbon\Carbon::parse($citaMasCercana->fecha)->format('d/m/Y') }}</div>
                                    <div class="col-sm-6">
                                        {{ \Carbon\Carbon::parse($citaMasCercana->hora)->format('H:i') }}</div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <strong>Código:</strong>
                                        <h3 style="color:red">{{ $citaMasCercana->codigo }}</h3>
                                    </div>
                                </div>
                                <div class="row">
                                    <form id="delete-form"
                                        action="{{ route('eliminar_cita', ['id' => $citaMasCercana->id]) }}"
                                        method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" id="google-calendar-access-token"
                                            value="{{ $accessToken }}">
                                        <input type="hidden" id="cita-id" value="{{ $citaMasCercana->id }}">
                                        <input type="hidden" id="cita-fecha" value="{{ $citaMasCercana->fecha }}">
                                        <input type="hidden" id="cita-hora" value="{{ $citaMasCercana->hora }}">
                                        <button type="button" class="btn btn-danger" onclick="eliminarCita()">Eliminar
                                            Cita</button>
                                    </form>
                                </div>
                                @else
                                <p>No tienes citas próximas.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script async defer src="https://apis.google.com/js/api.js" onload="gapiLoaded()"></script>
            <script async defer src="https://accounts.google.com/gsi/client" onload="gisLoaded()"></script>
            <!-- <script src="{{ asset('js/eliminarEvento.js') }}"></script> -->
        </div>

        <div id="fotos" class="tabcontent">
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
                    <div class="container album-container text-center" style="color: white; margin-top: 30px">
                        <h1 style="color:black">Album de Fotos</h1>
                        @if(Auth::check())
                        <form method="GET" action="{{ route('filterImage') }}" class="mb-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="user_id">Filtrar por Usuario:</label>
                                    <select name="user_id" id="user_id" class="form-control"
                                        style="width: 100%;color:black; background-color: white; border: 2px solid black;">
                                        <option value="">Todos los Usuarios</option>
                                        <option value="{{ Auth::user()->id }}">Mis Fotos</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="estilo_id">Filtrar por Estilo de Corte:</label>
                                    <select name="estilo_id" id="estilo_id" class="form-control"
                                        style="width: 100%;color:black; background-color: white; border: 2px solid black;">
                                        <option value="">Todos los Estilos</option>
                                        @foreach($estilos as $estilo)
                                        <option value="{{ $estilo->id }}">{{ $estilo->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-12 mt-3 d-flex justify-content-center">
                                    <button type="submit" class="btn btn-primary"
                                        style="width: 100%;color:black; background-color: white; border: 2px solid black;">Filtrar</button>
                                </div>
                            </div>
                        </form>
                        @endif
                        <div class="row">
                            @foreach($fotos as $foto)
                            <div class="col-md-4 mb-4">
                                <div class="photo-card">
                                    <div class="d-flex flex-column align-items-center"
                                        style="background-color: white; border-radius: 10px; border: 2px solid black;">
                                        <div
                                            style="background-color: black; border-radius: 50%; height: 10px; width: 10px; margin-top: 10px">
                                        </div>
                                        <div>
                                            <img src="{{ asset('storage/haircut_gallery/' . $foto->photo_name) }}"
                                                class="img-fluid photo-thumbnail" alt="Foto de {{ $foto->photo_name }}"
                                                style="width: 400px; height: 400px; object-fit: cover; margin-top: 10px; margin-bottom: 10px">
                                        </div>
                                        <div class="text-center">
                                            <div class="row">
                                                <div class="col">
                                                    <div style="color: black">
                                                        <p><strong>Barbero:</strong>
                                                            {{ $foto->barbero ? $foto->barbero->nombre : 'Sin barbero' }}
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div style="color: black">
                                                        <p><strong>Corte:</strong>
                                                            {{ $foto->estilo ? $foto->estilo->nombre : 'Sin estilo' }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="pagination justify-content-center">
                            {{ $fotos->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="settings" class="tabcontent">
            <div class="center-box">
                <div class="box">
                    <span></span>
                    <div class="container-settings">
                        <div class="profile-section">
                            <div class="logo-perfil-settings">
                                @php
                                if (Auth::check() && Auth::user()->profile_image) {
                                $profileImagePath = "storage/profile_images/" . Auth::user()->profile_image;
                                if (file_exists(public_path($profileImagePath))) {
                                echo "<img src='" . asset($profileImagePath) . "'
                                    alt='Perfil de " . Auth::user()->username . "'>";
                                } else {
                                echo "<img src='" . asset('storage/profile_images/default.jpg')
                                    . "' alt='Imagen por defecto'>" ; } } @endphp </div>
                                <div class="profile-info">
                                    <div class="nombre-perfil-settings">
                                        @php
                                        $user = Auth::user();
                                        if ($user) {
                                        echo "<h2>{$user->name} {$user->surname}</h2>";
                                        echo "<h3>@{$user->username}</h3>";

                                        $joinedDate = date('d M Y', strtotime($user->created_at));
                                        echo "<p>Joined {$joinedDate}</p>";
                                        }
                                        @endphp
                                    </div>
                                </div>
                                <div class="user-role">
                                    @php
                                    if ($user) {
                                    if ($user->is_admin) {
                                    echo "<p>Administrador</p>";
                                    } else {
                                    echo "<p>Cliente</p>";
                                    }
                                    }
                                    @endphp
                                </div>
                            </div>
                            @if(Auth::check())
                            <div class="edit-form container">
                                <form action="{{ route('update_profile') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="name">Nombre:</label>
                                                <input type="text" id="name" name="name"
                                                    value="{{ Auth::user()->name }}" class="form-control" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="surname">Apellido:</label>
                                                <input type="text" id="surname" name="surname"
                                                    value="{{ Auth::user()->surname }}" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label for="username">Nombre de usuario:</label>
                                                <input type="text" id="username" name="username"
                                                    value="{{ Auth::user()->username }}" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label for="date_of_birth">Fecha de nacimiento:</label>
                                                <input type="date" id="date_of_birth" name="date_of_birth"
                                                    value="{{ Auth::user()->date_of_birth }}" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label for="phone">Teléfono:</label>
                                                <input type="text" id="phone" name="phone"
                                                    value="{{ Auth::user()->phone }}" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label for="gender">Género:</label>
                                                <select id="gender" name="gender" class="form-control">
                                                    <option value="hombre"
                                                        {{ Auth::user()->gender === 'hombre' ? 'selected' : '' }}>Hombre
                                                    </option>
                                                    <option value="mujer"
                                                        {{ Auth::user()->gender === 'mujer' ? 'selected' : '' }}>Mujer
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="email">Correo electrónico:</label>
                                                <input type="email" id="email" name="email"
                                                    value="{{ Auth::user()->email }}" class="form-control" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="password">Contraseña:</label>
                                                <input type="password" id="password" name="password"
                                                    class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label for="confirm_password">Confirmar contraseña:</label>
                                                <input type="password" id="confirm_password" name="confirm_password"
                                                    class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label for="profile_image">Cambiar foto de perfil:</label>
                                                <input type="file" id="profile_image" name="profile_image"
                                                    class="form-control" accept="image/*">
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-5">
                                                    <div class="form-group">
                                                        <button type="submit" formaction="{{ route('delete_account') }}"
                                                            formmethod="POST" class="btn btn-danger"
                                                            onclick="return confirm('¿Está seguro de que desea eliminar su cuenta? Esta acción no se puede deshacer.');">Eliminar
                                                            cuenta</button>
                                                    </div>
                                                </div>
                                                <div class="col-sm-5">
                                                    <div class="form-group">
                                                        <button type="submit" class="btn btn-primary">Guardar
                                                            cambios</button>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </form>
                            </div>
                            @else
                            <p style="color: white">Debe estar autenticado para editar su perfil.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>












            <footer>
                <div class="footer">
                    <p>Todos los derechos reservados &copy; Barberia Abel·lanas</p>
                </div>
            </footer>

            <!-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script> -->



            <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
            <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

</body>

</html>