<!-- /resources/views/principal.blade.php -->

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barberia Abel·lanas</title>
    <link rel="stylesheet" href="{{ asset('css/principal.css') }}">
</head>

<body>
    <div class="menu-container">
        <div class="navigation">
            <ul>
                <li class="list active">
                    <a href="{{ route('principal') }}">
                        <span class="icon">
                            <ion-icon name="home-outline"></ion-icon>
                        </span>
                        <span class="text">Home</span>
                    </a>
                </li>
                <li class="list">
                    <a href="{{ route('perfil') }}">
                        <span class="icon">
                            <ion-icon name="person-outline"></ion-icon>
                        </span>
                        <span class="text">Perfil</span>
                    </a>
                </li>
                <li class="list">
                    <a href="{{ route('fotos') }}#">
                        <span class="icon">
                            <ion-icon name="camera-outline"></ion-icon>
                        </span>
                        <span class="text">Fotos</span>
                    </a>
                </li>
                <li class="list">
                    <a href="{{ route('settings') }}">
                        <span class="icon">
                            <ion-icon name="settings-outline"></ion-icon>
                        </span>
                        <span class="text">Settings</span>
                    </a>
                </li>
                <li class="list">
                    <a href="{{ route('logout') }}">
                        <span class="icon">
                            <ion-icon name="log-out-outline"></ion-icon>
                        </span>
                        <span class="text">logout</span>
                    </a>
                </li>
                <div class="indicator"></div>
            </ul>
        </div>
    </div>
    <script>
    const list = document.querySelectorAll(".list");

    function activeLink() {
        list.forEach((item) => item.classList.remove("active"));
        this.classList.add("active");
    }
    list.forEach((item) => item.addEventListener("mouseover", activeLink));
    </script>

    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

    <section class="hero">
        <div class="container">
            <h2>Bienvenido a Barberia Abel·lanas</h2>
            <p>El mejor lugar para cuidar de tu barba y cabello.</p>
            <a href="#" class="button">Reservar cita</a>
            <!-- https://www.youtube.com/watch?v=D1p2Sl6lxX4 -->
        </div>
    </section>

    <section class="services">
        <div class="container">
            <h3 class="title_H3">Nuestros Servicios:</h3>
            <div class="services-grid">
                <div class="service">
                    <img src="{{ asset('img/corte-de-pelo.png') }}" alt="Corte de pelo">
                    <h4 class="service-title">Corte de pelo</h4>
                </div>
                <div class="service">
                    <img src="{{ asset('img/afeitado-barba.png') }}" alt="Afeitado de barba">
                    <h4 class="service-title">Afeitado de barba</h4>
                </div>
                <div class="service">
                    <img src="{{ asset('img/teñir-el-pelo.png') }}" alt="Teñir el pelo">
                    <h4 class="service-title">Teñir el pelo</h4>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="footer">
            <p>Todos los derechos reservados &copy; Barberia Abel·lanas</p>
        </div>
    </footer>
</body>

</html>