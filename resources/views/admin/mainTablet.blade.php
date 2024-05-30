<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Panel</title>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="{{ asset('css/admin/mainTablet.css') }}">
</head>

<body>
    <div class="main-container">
        <span class="tabs-container">
            <span class="tabs">
                <button class="tablink active" onclick="openTab(event, 'Tab1')">Inicio</button>
                <button class="tablink" onclick="openTab(event, 'Tab2')">Datos</button>
                <button class="tablink" onclick="openTab(event, 'Tab3')">Fotos</button>
                <button class="tablink" onclick="openTab(event, 'Tab4')">Gestion de Citas</button>
                <button class="tablink" onclick="openTab(event, 'Tab5')">Facturacion</button>
                <a href="{{ route('view') }}"><button class="tablink">Home</button></a>
                <a href="{{ route('logout') }}"><button class="tablink">Cerrar Sesión</button></a>
            </span>
        </span>



        <span class="tabcontent-container">
            <span id="Tab1" class="tabcontent">
                <h1 class="titulo">Inicio</h1>
                <div class="container">
                    <h3>Este es el panel de el ADMIN donde podras genstionar la barberia</h3>
                    <ul>
                        <li><strong>Gestión de Usuarios:</strong> Administrar los usuarios registrados en esta web,
                            podras filtrar por nombre y editar o eliminar usuarios</li>
                        <li><strong>Gestión de el album de fotos:</strong> Desde aqui pudes agregar las imagenes para el
                            album, y luego podras borrarlas.</li>
                        <li><strong>Gestion de eventos</strong> Usnado google Calendar peudes gestionar los eventos,
                            crearlos, modifcarlos y elimianrlos</li>
                        <li><strong>Gestion de Facturacion</strong> Desde Facturacion, podras agregar el codigo de el
                            clientte y poner cuanto le has cobrado, y le otorgaras sus puntos, agregando la fecha de el
                            mes que quieres puedes descargar un pdf con una lsita de los servicios que has realizado y
                            el dienro total</li>
                    </ul>
                </div>
            </span>

            <span id="Tab2" class="tabcontent">
                <h1 class="titulo">Datos de Usuarios</h1>
                <div class="fondoWapo">
                    <div class="search-container container mt-3">
                        <div class="input-group">
                            <input type="text" id="searchInput" class="form-control"
                                placeholder="Buscar por Nombre o Apllido">
                            <button class="btn btn-primary" onclick="searchUser()">Buscar</button>
                        </div>
                    </div>
                    <br>
                    <div class="table-header">
                        <div class="header-cell">ID</div>
                        <div class="header-cell">Nombre</div>
                        <div class="header-cell">Imagen</div>
                    </div>
                    <div class="table-body">
                        <div class="table-scroll">
                            @foreach($users as $user)
                            <div class="row row-table-scroll" onclick="showUserInfo('{{ $user->id }}')">
                                <div class="cell">{{ $user->id }}</div>
                                <div class="cell">{{ $user->name }} {{ $user->surname }}</div>
                                <div class="cell">
                                    @php
                                    $profileImagePath = "storage/profile_images/" . $user->profile_image;

                                    if (file_exists(public_path($profileImagePath))) {
                                    echo "<img src='" . asset($profileImagePath) . "' alt='Perfil de {$user->username}'
                                        style='max-height: 75px'>";
                                    } else {
                                    echo "<img src='" . asset(' storage/profile_images/default.jpg')
                                        . "' alt='Imagen por defecto'>" ; } @endphp </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="blur-userInfo" style="display: none;"></div>
                        <span id="userInfoTemplate" class="user-info-container" style="display: none;">
                            <form id="userInfoForm" onsubmit="updateUser(event)" method="POST">
                                <input type="hidden" id="userId" name="userId">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="is_admin" class="form-label">¿Es Administrador?</label>
                                            <select class="form-select" id="is_admin" name="is_admin">
                                                <option value="0">No</option>
                                                <option value="1">Sí</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Nombre:</label>
                                            <input type="text" class="form-control" id="name" name="name" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="surname" class="form-label">Apellido:</label>
                                            <input type="text" class="form-control" id="surname" name="surname">
                                        </div>
                                        <div class="mb-3">
                                            <label for="username" class="form-label">Nombre de Usuario:</label>
                                            <input type="text" class="form-control" id="username" name="username"
                                                required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="phone" class="form-label">Teléfono:</label>
                                            <input type="text" class="form-control" id="phone" name="phone" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="date_of_birth" class="form-label">Fecha de Nacimiento:</label>
                                            <input type="date" class="form-control" id="date_of_birth"
                                                name="date_of_birth" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="gender" class="form-label">Género:</label>
                                            <select class="form-select" id="gender" name="gender" required>
                                                <option value="hombre">Hombre</option>
                                                <option value="mujer">Mujer</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email:</label>
                                            <input type="email" class="form-control" id="email" name="email" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="password" class="form-label">Contraseña:</label>
                                            <input type="password" class="form-control" id="password" name="password"
                                                minlength="6">
                                        </div>
                                        <div class="mb-3">
                                            <label for="confirm_password" class="form-label">Confirmar
                                                Contraseña:</label>
                                            <input type="password" class="form-control" id="confirm_password"
                                                name="confirm_password" minlength="6">
                                        </div>
                                        <div class="mb-3">
                                            <label for="profile_image" class="form-label">Imagen de Perfil:</label>
                                            <input type="file" class="form-control" id="profile_image"
                                                name="profile_image" accept="image/*">
                                        </div>
                                        <div class="mt-3 photo-preview1">
                                            <img id="photo-preview1" src="#" alt="Previsualización de la Foto"
                                                style="display: none;">
                                        </div>
                                        <br>
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <button class="btn btn-danger"
                                            onclick="deleteUser('{{ $user->id }}')">Eliminar</button>
                                        <button type="submit" class="btn btn-primary">Guardar</button>
                                    </div>
                                </div>
                            </form>
                        </span>
                    </div>
            </span>

            <script>
            document.getElementById('profile_image').addEventListener('change', function(event) {
                const [file] = event.target.files;
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const preview = document.getElementById('photo-preview1');
                        preview.src = e.target.result;
                        preview.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                }
            });
            </script>

            <span id="Tab3" class="tabcontent">
                <h1 class="titulo">Fotos</h1>
                <div class="row">
                    <div class="col-md-4">
                        <form action="{{ route('admin.upload-photo') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="user_id" class="form-label">Usuario:</label>
                                <select class="form-select" id="user_id" name="user_id" required>
                                    <option value="" selected disabled>-- Selecciona un Cliente --</option>
                                    @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} {{ $user->surname }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="barber_id" class="form-label">Barbero:</label>
                                <select class="form-select" id="barber_id" name="barber_id" required>
                                    <option value="" selected disabled>-- Selecciona un barbero --</option>
                                    @foreach($barberos as $barbero)
                                    <option value="{{ $barbero->id }}">{{ $barbero->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="hairstyle_id" class="form-label">Estilo de Corte:</label>
                                <select class="form-select" id="hairstyle_id" name="hairstyle_id" required>
                                    <option value="" selected disabled>-- Selecciona un Corte --</option>
                                    @foreach($estilos_de_cortes as $estilo)
                                    <option value="{{ $estilo->id }}">{{ $estilo->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="photo" class="form-label">Subir Foto:</label>
                                <input type="file" class="form-control" id="photo" name="photo" accept="image/*"
                                    required>
                                <div class="mt-3 photo-preview">
                                    <img id="photo-preview" src="#" alt="Previsualización de la Foto"
                                        style="display: none;">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Subir Foto</button>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <div class="scrollable-gallery">
                            <div class="row row-cols-3">
                                @foreach($gallery as $photo)
                                <div class="col">
                                    <div class="photo-thumbnail">
                                        <img src="{{ asset('storage/haircut_gallery/' . $photo->photo_name) }}"
                                            alt="{{ $photo->photo_name }}" style="height:75px; width:auto">
                                        <p>{{ $photo->photo_name }}</p>
                                        <button class="btn btn-danger delete-photo-btn"
                                            onclick="deletePhoto('{{ $photo->id }}')">Eliminar</button>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </span>


            <script>
            document.getElementById('photo').addEventListener('change', function(event) {
                const [file] = event.target.files;
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const preview = document.getElementById('photo-preview');
                        preview.src = e.target.result;
                        preview.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                }
            });
            </script>


            <!-- <a href="{{ route('admin.calendar') }}"><button class="tablink"
        style="width: 10%;">Calendario</button></a> -->

            <span id="Tab4" class="tabcontent">
                <h1 class="titulo">Gestion de Citas</h1>
                <br>
                <div id="loading-message">Cargando, por favor espera...</div>
                <div class="citas-pendientes" style="display: none;">
                    <h2>Citas Pendientes</h2>
                    <div class="table-responsive">
                        <div class="input-group">
                            <input type="date" id="fecha" name="fecha" class="form-control">
                            <button class="btn btn-primary" onclick="filtrarCitas()">Buscar</button>
                            <button class="btn btn-secondary" onclick="limpiarFiltro()">Limpiar Filtro</button>
                        </div>
                        <br>
                        <table class="table table-striped text-white">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Fecha</th>
                                    <th>Hora</th>
                                    <th>Usuario</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($citasPendientes as $cita)
                                <tr>
                                    <td class="text-white cita-id" data-cita-id="{{ $cita->id }}">{{ $cita->codigo }}
                                    </td>
                                    <td class="text-white cita-fecha"
                                        data-cita-fecha="{{ $cita->fecha->format('Y-m-d') }}">
                                        {{ $cita->fecha->format('Y-m-d') }}
                                    </td>
                                    <td class="text-white cita-hora" data-cita-hora="{{ $cita->hora }}">
                                        {{ $cita->hora }}</td>
                                    <td class="text-white cita-usuario">{{ $cita->user_id }}</td>
                                    <td>
                                        <form id="delete-form-{{ $cita->id }}"
                                            action="{{ route('eliminar_cita', ['id' => $cita->id]) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="google-calendar-access-token"
                                                value="{{ $accessToken }}">
                                            <input type="hidden" name="cita-id" value="{{ $cita->id }}">
                                            <input type="hidden" name="cita-fecha" value="{{ $cita->fecha }}">
                                            <input type="hidden" name="cita-hora" value="{{ $cita->hora }}">
                                            <button type="button" class="btn btn-danger"
                                                onclick="eliminarCita(this.form)">Eliminar Cita</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </span>

            <script async defer src="https://apis.google.com/js/api.js" onload="gapiLoaded()"></script>
            <script async defer src="https://accounts.google.com/gsi/client" onload="gisLoaded()"></script>
            <script src="{{ asset('js/admin/eliminarEventoAdmin.js') }}"></script>







            <span id="Tab5" class="tabcontent">
                <h1 class="titulo">Facturación</h1>
                <div class="row">
                    <div class="col-md-6">
                        <form id="facturacionForm" method="POST" action="{{ route('admin.facturacion') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="codigo" class="form-label">Código:</label>
                                <input type="text" class="form-control" id="codigo" name="codigo" required>
                            </div>
                            <div class="mb-3">
                                <label for="euros" class="form-label">Dinero:</label>
                                <input type="number" step="0.01" class="form-control" id="euros" name="euros" required>
                            </div>
                            <div class="mb-3">
                                <label for="points" class="form-label">Puntos:</label>
                                <input type="number" class="form-control" id="points" name="points" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Facturar</button>
                        </form>
                    </div>
                    <div class="col-md-5">
                        <h2>Lista de Facturación</h2>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" id="search" name="search"
                                placeholder="Buscar por nombre o apellido" required>
                            <button class="btn btn-primary" type="submit" onclick="filterCitas()">Buscar</button>
                        </div>
                        <div class="custom-table-wrapper">
                            <table class="table custom-table p-4" style="color: white">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Usuario</th>
                                        <th>Fecha</th>
                                        <th>Dinero Cobrado</th>
                                    </tr>
                                </thead>
                                <tbody id="citasTableBody">
                                    @foreach($citas as $cita)
                                    <tr>
                                        <td>{{ $cita->id }}</td>
                                        <td>{{ $cita->usuario->name }} {{ $cita->usuario->surname }}</td>
                                        <td>{{ $cita->fecha->format('d/m/Y') }}</td>
                                        <td>{{ $cita->dinero_cobrado }}€</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <br>
                            <div>
                                <h2>Descargar Facturación por Mes</h2>
                                <form action="{{ route('admin.descargarFacturacion') }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="fecha">Seleccionar Mes y Año:</label>
                                        <input type="date" id="fecha" name="fecha" class="form-control" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Descargar PDF</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </span>




        </span>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/admin/adminPanel.js') }}"></script>

</body>

</html>