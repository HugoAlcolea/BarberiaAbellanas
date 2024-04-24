<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>


    <link rel="stylesheet" href="{{ asset('css/admin/mainTablet.css') }}">
    <script src="{{ asset('js/admin/adminPanel.js') }}"></script>

</head>

<body>

    <div class="main-container">
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
                <h1 class="titulo">Inicio</h1>
            </span>

            <span id="Tab2" class="tabcontent">
                <h1 class="titulo">Datos de Usuarios</h1>
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
                <h1 class="titulo">Fotos</h1>
            </span>

            <span id="Tab4" class="tabcontent">
                <h1 class="titulo">Gestion de Citas</h1>
                <a href="{{ route('admin.calendar') }}"><button class="tablink">Calendario</button></a>
            </span>
        </span>
    </div>
</body>

</html>
