<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use App\Models\CustomUser;
use App\Models\StatsUser;
use App\Models\Barbero;
use App\Models\Servicio;

use Google_Client;
use Google_Service_Calendar;

class CustomUserController extends Controller
{
    public function view(){
        return view('index');
    }

    public function index(){
        return view('index');
    }

    public function register(Request $request){
        $request->validate([
            'name' => 'required',
            'surname' => 'required',
            'username' => 'required|unique:custom_users',
            'phone' => 'required',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:hombre,mujer',
            'email' => 'required|email',
            'password' => 'required|min:6',
            'confirm_password' => 'required|same:password',
            'profile_image' => 'image|mimes:jpeg,png,jpg,gif',
        ], [
            'username.unique' => 'El nombre de usuario ya está en uso.',
            'email.unique' => 'El correo electrónico ya está en uso.',
            'gender.in' => 'El campo de género debe ser hombre o mujer.',
            'confirm_password.same' => 'La contraseña y la confirmación de contraseña no coinciden.',
        ]);
    
        $user = CustomUser::where('email', $request->input('email'))->first();
    
        if ($user) {
            $user->name = $request->input('name');
            $user->surname = $request->input('surname');
            $user->username = $request->input('username');
            $user->phone = $request->input('phone');
            $user->date_of_birth = $request->input('date_of_birth');
            $user->gender = $request->input('gender');
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
                $user->profile_image = 'default.jpg';
            }
    
            $user->registration_completed = true;
            $user->save();

            Auth::logout();
    
            return redirect('/login');
        } else {
            return redirect()->back()->withInput()->withErrors(['email' => 'El correo electrónico no existe.']);
        }
    }
    
    
    
    public function showRegistrationForm(){
        $user = Auth::user();
        if ($user) {
            return view('register', [
                'step' => 1,
                'user' => $user,
            ]);
        } else {
            return redirect()->route('login');
        }
    }
    

    public function showLoginForm(){
        return View::first(['login', 'auth.login', 'auth.form']);
    }


    public function login(Request $request){
        $credentials = $request->only('username', 'password');
    
        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            $statsUser = StatsUser::where('user_id', $user->id)->first();
    
            if (!$statsUser) {
                $statsUser = new StatsUser();
                $statsUser->user_id = $user->id;
                $statsUser->haircuts = 0;
                $statsUser->points = 0;
                $statsUser->save();
            }
    
            if ($user->isAdmin()) {
                return redirect()->route('admin.mainTable');
            }
    
            if ($user->registration_completed) {
                return redirect()->route('index');
            }
    
            return redirect()->route('register');
        }
    
        return redirect()->back()->with('error', 'Credenciales no válidas. Inténtalo de nuevo.');
    }
    


    public function authenticateWithGoogleCalendar()
    {
        $client = new Google_Client();
        $client->setApplicationName('Barberia Abellanas');
        $client->setScopes(Google_Service_Calendar::CALENDAR_EVENTS);
        $client->setAuthConfig(storage_path('app/service-accounts/calendaralcolea-978337d38352.json'));
        $client->setAccessType('offline');

        // Obtener un token de acceso
        $accessToken = $client->fetchAccessTokenWithAssertion();
        
        return response()->json(['access_token' => $accessToken['access_token']]);
    }


    public function mostrarFormularioCita()
    {
        $response = $this->authenticateWithGoogleCalendar();
        $accessToken = json_decode($response->getContent(), true)['access_token'];
        $barberos = Barbero::all(); 
        $servicios = Servicio::all(); 
        return view('formulario_cita', compact('barberos', 'servicios', 'accessToken'));
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