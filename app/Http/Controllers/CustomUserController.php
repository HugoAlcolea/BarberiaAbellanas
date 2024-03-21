<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomUser;
use App\Models\StatsUser;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class CustomUserController extends Controller
{
    public function view(){
        return view('index');
    }

    public function index(){
        return view('index');
    }

    public function register(Request $request){
        // Validar los datos del formulario
        $request->validate([
            'name' => 'required',
            'surname' => 'required',
            'username' => 'required|unique:custom_users',
            'phone' => 'required',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:hombre,mujer',
            'email' => 'required|email|unique:custom_users',
            'password' => 'required|min:6',
            'confirm_password' => 'required|same:password',
            'profile_image' => 'image|mimes:jpeg,png,jpg,gif',
        ], [
            'username.unique' => 'El nombre de usuario ya está en uso.',
            'email.unique' => 'El correo electrónico ya está en uso.',
            'gender.in' => 'El campo de género debe ser hombre o mujer.',
            'confirm_password.same' => 'La contraseña y la confirmación de contraseña no coinciden.',
        ]);
    
        // Crear un nuevo usuario en la base de datos
        $user = new CustomUser();
        $user->name = $request->input('name');
        $user->surname = $request->input('surname');
        $user->username = $request->input('username');
        $user->phone = $request->input('phone');
        $user->date_of_birth = $request->input('date_of_birth');
        $user->gender = $request->input('gender');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->confirm_password = Hash::make($request->input('confirm_password'));
    
        // Procesar la imagen del perfil
        if ($request->hasFile('profile_image')) {
            $name = $request->input('name');
            $surname = $request->input('surname');
            $imageExtension = $request->file('profile_image')->getClientOriginalExtension();
            $imageName = $name . $surname . '.' . $imageExtension;
            $request->file('profile_image')->storeAs('public/profile_images', $imageName);
            $user->profile_image = $imageName;
        } else {
            // Utilizar una ruta relativa para la imagen por defecto
            $user->profile_image = 'default.jpg';
        }
    
        $user->save();
    
        return redirect('/login');
    }
    
    
    

    public function showRegistrationForm(){
        return view('register')->with('step', 1);
    }

    public function showLoginForm(){
        return View::first(['login', 'auth.login', 'auth.form']);
    }

    public function login(Request $request){
    $credentials = $request->only('username', 'password');

    if (Auth::attempt($credentials)) {
        $user = Auth::user();

        // Verificar si el usuario tiene una fila en stats_users
        $statsUser = StatsUser::where('user_id', $user->id)->first();

        if (!$statsUser) {
            // Si no tiene una fila, crear una nueva con valores predeterminados
            $statsUser = new StatsUser();
            $statsUser->user_id = $user->id;
            $statsUser->haircuts = 0;
            $statsUser->points = 0;
            $statsUser->save();
        }

        if ($user->isAdmin()) {
            return redirect()->route('admin.mainTable');
        }

        return redirect()->route('index');
    }

    return redirect()->back()->with('error', 'Credenciales no válidas. Inténtalo de nuevo.');
    }



    public function showPerfil(){
        return view('navbar.perfil');
    }

    public function showFotos(){
        return view('navbar.fotos');
    }

    public function showSettings(){
        return view('navbar.settings');
    }

    public function logout(){
        Auth::logout();
        return redirect()->route('index');
    }
}