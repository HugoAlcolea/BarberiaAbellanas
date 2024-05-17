<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomUserController;
use App\Http\Controllers\AdminController;
use Laravel\Socialite\Facades\Socialite;
use App\Models\CustomUser;
use App\Models\StatsUser;
use Illuminate\Support\Facades\Auth;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [CustomUserController::class, 'view']);
Route::get('/index', [CustomUserController::class, 'index'])->name('index');

Route::get('/google-auth/redirect', function () {
    return Socialite::driver('google')->redirect();
});
 
Route::get('/google-auth/callback', function () {
    $user_google = Socialite::driver('google')->stateless()->user();
    
    // Divide el nombre completo en nombre y apellido
    $nameParts = explode(' ', $user_google->name);
    $firstName = $nameParts[0];
    $lastName = isset($nameParts[1]) ? $nameParts[1] : '';

    // Buscar o crear el usuario
    $user = CustomUser::updateOrCreate([
        'google_id' => $user_google->id,
    ],[
        'name' => $firstName,
        'surname' => $lastName,
        'email' => $user_google->email,
    ]);

    if (!$user->registration_completed) {
        $user->registration_completed = false;
        $user->save();

        Auth::login($user);

        // Crear la fila en stats_users
        $statsUser = new StatsUser();
        $statsUser->user_id = $user->id;
        $statsUser->haircuts = 0;
        $statsUser->points = 0;
        $statsUser->save(); 

        return redirect()->route('register', ['id' => $user->id]);
    } else {
        Auth::login($user);
        // $cookie = cookie()->forever('token_user', $user_google->token);
        // return redirect()->route('index')->withCookie($cookie);
        return redirect()->route('index');
    }
});




// Rutas de register
Route::get('/register', [CustomUserController::class, 'showRegistrationForm'])->name('register');
Route::post('/register/step1', [CustomUserController::class, 'registerStep1'])->name('register.step1');
Route::post('/register', [CustomUserController::class, 'register'])->name('register.post'); 

// Rutas de login
Route::get('/login', [CustomUserController::class, 'showLoginForm'])->name('login');
Route::post('/login', [CustomUserController::class, 'login'])->name('login.post');
Route::get('/admin-panel', [AdminController::class, 'index'])->name('admin.mainTable');

// Rutas de Principal
Route::get('/principal', function () {
    return view('principal');
})->name('principal');

// Rutas de Citas
Route::get('/cita', [CustomUserController::class, 'mostrarFormularioCita'])->name('cita.formulario');
Route::get('/auth/google-calendar', [CustomUserController::class, 'authenticateWithGoogleCalendar'])->name('auth.google-calendar');


// Rutas de Perfil
Route::get('/principal/perfil', [CustomUserController::class, 'showPerfil'])->name('perfil');
// Rutas de Fotos
Route::get('/principal/fotos', [CustomUserController::class, 'showFotos'])->name('fotos');

// Rutas de Settings
Route::get('/principal/settings', [CustomUserController::class, 'showSettings'])->name('settings');
Route::get('/logout', function () {
    Auth::logout();
    return redirect()->route('index')->withCookie(Cookie::forget('token_user'));
})->name('logout');



// Rutas Admin
Route::get('/admin/user/{id}', [AdminController::class, 'getUserInfo'])->name('admin.user.info');
Route::get('/logout', [AdminController::class, 'logout'])->name('logout');
Route::get('/admin/calendar', [AdminController::class, 'calendar'])->name('admin.calendar');