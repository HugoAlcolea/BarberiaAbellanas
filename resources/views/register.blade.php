<!-- resources/views/register.blade.php -->

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Registrarte</title>
    <link rel="stylesheet" href="{{ asset('css/register.css') }}" />
    <script src="{{ asset('js/register.js') }}" defer></script>
</head>

<body>
    <div class="center-box">
        <div class="box">
            <div class="lights-box">
                <span></span>
                <a href="{{ route('login') }}" class="back-button">
                    <img src="{{ asset('img/arrow-icon.png') }}" alt="Volver al login">
                </a>
                <div class="container">
                    <h1>Regístrate</h1>
                    <div class="steps">
                        <div class="progress-bar">
                            <div class="indicator"></div>
                        </div>
                        <div class="circle active">1</div>
                        <div class="circle">2</div>
                        <div class="circle">3</div>
                        <div class="circle">4</div>
                        <div class="circle">5</div>
                    </div>

                    <div class="error-message" id="error-messages">
                        @if($errors->any())
                        @foreach ($errors->all() as $error)
                        {{ $error }}<br>
                        @endforeach
                        @endif
                    </div>

                    <div class="form-steps">
                        <form action="{{ route('register.post') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="step">
                                @if(isset($user))
                                <div class="input-group">
                                    <label for="name">Nombre:</label>
                                    <input type="text" id="name" name="name" value="{{ $user->name }}" required autofocus>
                                </div>
                                <div class="input-group">
                                    <label for="surname">Apellido:</label>
                                    <input type="text" id="surname" name="surname" value="{{ $user->surname }}"
                                        required>
                                </div>
                                @else
                                <div class="input-group">
                                    <label for="name">Nombre:</label>
                                    <input type="text" id="name" name="name" required autofocus>
                                </div>
                                <div class="input-group">
                                    <label for="surname">Apellido:</label>
                                    <input type="text" id="surname" name="surname" required>
                                </div>
                                @endif
                            </div>

                            <div class="step">
                                <div class="input-group">
                                    <label for="username">Nombre de usuario:</label>
                                    <input type="text" id="username" name="username" required autofocus>
                                </div>
                                <div class="input-group">
                                    <label for="phone">Teléfono:</label>
                                    <input type="text" id="phone" name="phone" required>
                                </div>
                                <div class="input-group">
                                    <label for="date_of_birth">Fecha de nacimiento:</label>
                                    <input type="date" id="date_of_birth" name="date_of_birth" required max="{{ date('d-m-Y') }}">
                                </div>
                            </div>

                            <div class="step">
                                <div class="input-group">
                                    @if(isset($user))
                                    <label for="email">Correo electrónico:</label>
                                    <input type="email" id="email" name="email" value="{{ $user->email }}" required autofocus>
                                    @else
                                    <label for="email">Correo electrónico:</label>
                                    <input type="email" id="email" name="email" required autofocus>
                                    @endif
                                </div>
                                <div class="input-group">
                                    <label for="password">Contraseña:</label>
                                    <input type="password" id="password" name="password" required>
                                </div>
                                <div class="input-group">
                                    <label for="confirm_password">Confirmar Contraseña:</label>
                                    <input type="password" id="confirm_password" name="confirm_password" required>
                                </div>
                            </div>

                            <div class="step">
                                <br><br>
                                <div class="input-group">
                                    <label for="gender">Género:</label>
                                    <select id="gender" name="gender" required>
                                        <option value="" disabled selected>Seleccione su género</option>
                                        <option value="hombre">Hombre</option>
                                        <option value="mujer">Mujer</option>
                                    </select>
                                </div>
                            </div>

                            <div class="step">
                                <div class="input-group">
                                    <img id="image-preview" src="#" alt="Vista previa de la imagen"
                                        style="display: none; max-width: 125px; max-height: 125px; border-radius: 50%; margin: 10px auto 10px; display: block;">
                                    <br>
                                    <label for="profile_image">Imagen de perfil:</label>
                                    <input style="color: white" type="file" id="profile_image" name="profile_image"
                                        accept="image/*" onchange="previewImage(this)">
                                </div>
                            </div>

                            <script>
                            function previewImage(input) {
                                var preview = document.getElementById('image-preview');
                                var file = input.files[0];
                                var reader = new FileReader();

                                reader.onload = function(e) {
                                    preview.src = e.target.result;
                                    preview.style.display = 'block';
                                };

                                if (file) {
                                    reader.readAsDataURL(file);
                                }
                            }

                            document.getElementById('date_of_birth').max = new Date().toISOString().split('T')[0];
                            </script>

                            <div class="buttons">
                                <button id="prev">Prev</button>
                                <button id="next">Next</button>
                                <button type="submit" id="register" style="display: none;">Registrarse</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>