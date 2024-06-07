<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomUserController;
use App\Http\Controllers\AdminController;
use Laravel\Socialite\Facades\Socialite;
use App\Models\CustomUser;
use App\Models\StatsUser;
use Illuminate\Support\Facades\Auth;

Route::get('/', [CustomUserController::class, 'view'])->name('view');
Route::get('/index', function() {
    return redirect('/');
});

Route::get('/google-auth/redirect', function () {
    return Socialite::driver('google')->redirect();
});
 
Route::get('/google-auth/callback', function () {
    $user_google = Socialite::driver('google')->stateless()->user();
    
    $nameParts = explode(' ', $user_google->name);
    $firstName = $nameParts[0];
    $lastName = isset($nameParts[1]) ? $nameParts[1] : '';

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

        $statsUser = new StatsUser();
        $statsUser->user_id = $user->id;
        $statsUser->haircuts = 0;
        $statsUser->points = 0;
        $statsUser->save();

        return redirect()->route('register.with.id', ['id' => $user->id]);
    } else {
        Auth::login($user);
        if ($user->is_admin) {
            return redirect()->route('admin.mainTable');
        } else {
            return redirect()->route('view');
        }
    }
});


// Rutas de register
Route::get('/register/{id}', [CustomUserController::class, 'showRegistrationForm'])->name('register.with.id');
Route::get('/register', [CustomUserController::class, 'showRegistrationForm'])->name('register');
Route::get('/register', [CustomUserController::class, 'redirectToRegisterForm'])->name('new.register');
Route::post('/register/step1', [CustomUserController::class, 'registerStep1'])->name('register.step1');
Route::post('/register', [CustomUserController::class, 'register'])->name('register.post'); 

// Rutas de login
Route::get('/login', [CustomUserController::class, 'showLoginForm'])->name('login');
Route::post('/login', [CustomUserController::class, 'login'])->name('login.post');

// Rutas de Citas
Route::get('/cita', [CustomUserController::class, 'mostrarFormularioCita'])->name('cita.formulario');
Route::post('/guardar-cita', [CustomUserController::class, 'guardarCita'])->name('guardar.cita');
Route::get('/mostrar-ticket/{citaId}', [CustomUserController::class, 'mostrarTicket'])->name('mostrar.ticket');
Route::get('/auth/google-calendar', [CustomUserController::class, 'authenticateWithGoogleCalendar'])->name('auth.google-calendar');

// Rutas de Perfil
Route::delete('/eliminar-cita/{id}', [CustomUserController::class, 'eliminarCita'])->name('eliminar_cita');

// Rutas de Fotos
Route::get('/filterImage', [CustomUserController::class, 'filterImage'])->name('filterImage');


// Rutas de Settings
Route::get('/principal/settings', [CustomUserController::class, 'showSettings'])->name('settings');
Route::post('/update_profile', [CustomUserController::class, 'updateProfile'])->name('update_profile');
Route::post('/delete-account', [CustomUserController::class, 'deleteAccount'])->name('delete_account');

// Rutas de Logout
Route::get('/logout', function () {
    Auth::logout();
    return redirect()->route('view')->withCookie(Cookie::forget('token_user'));
})->name('logout');

// Rutas Admin
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin-panel', [AdminController::class, 'index'])->name('admin.mainTable');
    Route::get('/admin/user/{id}', [AdminController::class, 'getUserInfo'])->name('admin.user.info');
    Route::get('/admin/search-users', [AdminController::class, 'searchUsers'])->name('admin.search.users');
    Route::post('/admin/user/{id}', [AdminController::class, 'updateUser'])->name('admin.user.update');
    Route::delete('/admin/user/{id}', 'AdminController@deleteUser')->name('admin.delete_user');
    Route::delete('/admin/delete-photo/{photoId}', [AdminController::class, 'deletePhoto'])->name('admin.delete_photo');
    Route::get('/admin/calendar', [AdminController::class, 'calendar'])->name('admin.calendar');
    Route::delete('/eliminar-cita/{id}', [AdminController::class, 'eliminarCita'])->name('eliminar_cita');
    Route::post('/admin/upload-photo', [AdminController::class, 'uploadPhoto'])->name('admin.upload-photo');
    Route::post('/admin/facturacion', [AdminController::class, 'processFacturacion'])->name('admin.facturacion');
    Route::post('/descargar-facturacion', [AdminController::class, 'generarPDF'])->name('admin.descargarFacturacion');
    Route::get('/index', [CustomUserController::class, 'index'])->name('index');
    Route::get('/admin/search-facturacion', [AdminController::class, 'searchFacturacion'])->name('admin.searchFacturacion');
    Route::get('/admin-logout', [AdminController::class, 'logout'])->name('admin.logout');
});


