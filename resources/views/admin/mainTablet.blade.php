<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('css/admin/mainTablet.css') }}">
    <script src="{{ asset('js/admin/adminPanel.js') }}"></script>
</head>

<body>

    <div class="container">
        <span class="tabs-container">
            <span class="tabs">
                <button class="tablink" onclick="openTab(event, 'Tab1')">Inicio</button>
                <button class="tablink active" onclick="openTab(event, 'Tab2')">Datos</button>
                <button class="tablink" onclick="openTab(event, 'Tab3')">Fotos</button>
                <button class="tablink" onclick="openTab(event, 'Tab4')">Gestion de Citas</button>
                <a href="{{ route('logout') }}"><button class="tablink">Cerrar Sesión</button></a>
            </span>
        </span>


        <span class="tabcontent-container">
            <span id="Tab1" class="tabcontent">
                <h1>Inicio</h1>
            </span>

            <span id="Tab2" class="tabcontent">
                <h1>Datos de Usuarios</h1>
                <div class="fondoWapo">
                    <div class="table-header">
                        <div class="header-cell">ID</div>
                        <div class="header-cell">Nombre</div>
                        <div class="header-cell">Imagen</div>
                    </div>
                    <div class="table-body">
                        <div class="table-scroll">
                            @foreach($users as $user)
                            <div class="row" onclick="showUserInfo('{{ $user->id }}')">
                                <div class="cell">{{ $user->id }}</div>
                                <div class="cell">{{ $user->name }} {{ $user->surname }}</div>
                                <div class="cell">
                                    @php
                                    $profileImagePath = "storage/profile_images/" . $user->profile_image;

                                    if (file_exists(public_path($profileImagePath))) {
                                    echo "<img src='" . asset($profileImagePath) . "'
                                        alt='Perfil de {$user->username}'>";
                                    } else {
                                    echo "<img src='" . asset('storage/profile_images/default.jpg')
                                        . "' alt='Imagen por defecto'>" ; } @endphp </div>


                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="blur-userInfo" style="display: none;"></div>
                    <div id="userInfoTemplate" class="user-info-container" style="display: none;">

                    </div>

            </span>

            <span id="Tab3" class="tabcontent">
                <h1>Fotos</h1>
            </span>

            <span id="Tab4" class="tabcontent">
                <h1>Gestion de Citas</h1>
                <iframe
                    src="https://calendar.google.com/calendar/embed?src=barberiaabellanas%40gmail.com&ctz=Europe%2FMadrid"
                    style="border: 0" width="800" height="600" frameborder="0" scrolling="no"></iframe>
            </span>
        </span>
    </div>
</body>

</html>