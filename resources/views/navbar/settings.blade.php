<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil - Barberia Abel·lanas</title>
    <link rel="stylesheet" href="{{ asset('css/navbar/settings.css') }}">
</head>

<body>

    <div class="menu-container">
        <div class="navigation">
            <ul>
            <li class="list">
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
                <li class="list active">
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


    <footer>
        <div class="footer">
            <p>Todos los derechos reservados &copy; Barberia Abel·lanas</p>
        </div>
    </footer>
</body>

</html>