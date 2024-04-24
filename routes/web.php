<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomUserController;
use App\Http\Controllers\AdminController;

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

// Rutas de Perfil
Route::get('/principal/perfil', [CustomUserController::class, 'showPerfil'])->name('perfil');
// Rutas de Fotos
Route::get('/principal/fotos', [CustomUserController::class, 'showFotos'])->name('fotos');

// Rutas de Settings
Route::get('/principal/settings', [CustomUserController::class, 'showSettings'])->name('settings');
Route::get('/logout', [CustomUserController::class, 'logout'])->name('logout');

// Rutas Admin
Route::get('/admin/user/{id}', [AdminController::class, 'getUserInfo'])->name('admin.user.info');
Route::get('/logout', [AdminController::class, 'logout'])->name('logout');
Route::get('/admin/calendar', [AdminController::class, 'calendar'])->name('admin.calendar');
