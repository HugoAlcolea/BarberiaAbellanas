<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barberia Abel·lanas</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <script src="{{ asset('js/index.js') }}"></script>

</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-transparent fixed-top">
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
                        <div class="nav-link-group">
                            <a class="nav-link" href="{{ route('login') }}" data-text="Login">Login</a>
                            <span class="nav-link-divider"> / </span>
                            <a class="nav-link" href="{{ route('register') }}" data-text="Register">Register</a>
                        </div>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>



    <!-- Contenido de las pestañas -->
    <div id="home" class="tabcontent">
        <section class="hero">
            <div class="container">
                <h2>Bienvenido a Barberia Abel·lanas</h2>
                <p>El mejor lugar para cuidar de tu barba y cabello.</p>
                <a href="#" class="btn btn-primary">Reservar cita</a>
                <!-- <script type="module" src="{{ asset('js/silla.js') }}"></script> -->
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
        </div>


        <div id="fotos" class="tabcontent">
            <h3>Contenido de la pestaña Fotos</h3>
            <p>Este es el contenido de la pestaña Fotos.</p>
        </div>

        <div id="settings" class="tabcontent">
            <h3>Contenido de la pestaña Settings</h3>
            <p>Este es el contenido de la pestaña Settings.</p>
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