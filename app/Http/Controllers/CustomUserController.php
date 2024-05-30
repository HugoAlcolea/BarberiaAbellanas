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
use App\Models\Cita;
use App\Models\EstilosDeCortes;
use App\Models\HaircutGallery;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Log;

use Google_Client;
use Google_Service_Calendar;

class CustomUserController extends Controller
{
    public function view(){
        $barber = Barbero::all();
        $hairstyle = EstilosDeCortes::all();
        $fotos = HaircutGallery::paginate(9);
        $usuarios = CustomUser::all(); 
        $estilos = EstilosDeCortes::all(); 
        $cita = Cita::all();
        $accessToken = null;

        $citaMasCercana = Cita::where('user_id', auth()->id())
        ->where('fecha', '>=', now())
        ->orderBy('fecha', 'asc')
        ->first();
        
        if (Auth::check()) {
            $response = $this->authenticateWithGoogleCalendar();
            $accessToken = json_decode($response->getContent(), true)['access_token'];
        }

        return view('index', compact('fotos', 'barber', 'hairstyle', 'usuarios', 'estilos', 'cita', 'citaMasCercana', 'accessToken'));
    }

    public function index()
    {
        $barber = Barbero::all();
        $hairstyle = EstilosDeCortes::all();
        $fotos = HaircutGallery::paginate(9);
        $usuarios = CustomUser::all(); 
        $estilos = EstilosDeCortes::all(); 
        $cita = Cita::all();
        $accessToken = null;

        $citaMasCercana = Cita::where('user_id', auth()->id())
        ->where('fecha', '>=', now())
        ->orderBy('fecha', 'asc')
        ->first();
        
        if (Auth::check()) {
            $response = $this->authenticateWithGoogleCalendar();
            $accessToken = json_decode($response->getContent(), true)['access_token'];
        }

        return view('index', compact('fotos', 'barber', 'hairstyle', 'usuarios', 'estilos', 'cita', 'citaMasCercana', 'accessToken'));
    }

    public function register(Request $request)
    {
        $userId = $request->input('id') ?? Auth::id();
    
        $rules = [
            'name' => 'required',
            'surname' => 'required',
            'username' => 'required|unique:custom_users,username,' . $userId,
            'phone' => 'required',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:hombre,mujer',
            'email' => 'required|email|unique:custom_users,email,' . $userId,
            'password' => 'required|min:6',
            'confirm_password' => 'required|same:password',
            'profile_image' => 'image|mimes:jpeg,png,jpg,gif',
        ];
    
        $messages = [
            'username.unique' => 'El nombre de usuario ya está en uso.',
            'email.unique' => 'El correo electrónico ya está en uso.',
            'gender.in' => 'El campo de género debe ser hombre o mujer.',
            'confirm_password.same' => 'La contraseña y la confirmación de contraseña no coinciden.',
        ];
    
        $request->validate($rules, $messages);
    
        $user = CustomUser::find($userId);
    
        if ($user) {
            // Existing user - update
            $user->name = $request->input('name');
            $user->surname = $request->input('surname');
            $user->username = $request->input('username');
            $user->phone = $request->input('phone');
            $user->date_of_birth = $request->input('date_of_birth');
            $user->gender = $request->input('gender');
            $user->email = $request->input('email');
            $user->password = Hash::make($request->input('password'));
            $user->confirm_password = Hash::make($request->input('confirm_password'));
    
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
            // New user - create
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
        }
    }
    
    
    
    
    
    public function showRegistrationForm($id = null)
    {
        $user = null;
        if ($id) {
            $user = CustomUser::find($id);
        }

        return view('register', ['user' => $user]);
    }

    public function redirectToRegisterForm() {
        return view('register');
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
                return redirect()->route('view');
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

        $accessToken = $client->fetchAccessTokenWithAssertion();
        
        return response()->json(['access_token' => $accessToken['access_token']]);
    }


    public function mostrarFormularioCita()
    {
        $response = $this->authenticateWithGoogleCalendar();
        $accessToken = json_decode($response->getContent(), true)['access_token'];
        $barberos = Barbero::all(); 
        $servicios = Servicio::all(); 
        $cita = Cita::first();
        return view('formulario_cita', compact('barberos', 'servicios', 'accessToken', 'cita'));
        }


    public function guardarCita(Request $request)
    {
        $userId = Auth::id();
        $fecha = $request->input('fecha');
        $hora = $request->input('hora');
        
        do {
            $codigo = 'A' . rand(1000, 9999);
        } while (Cita::where('codigo', $codigo)->exists());
    
        $cita = new Cita();
        $cita->user_id = $userId;
        $cita->fecha = $fecha;
        $cita->hora = $hora;
        $cita->codigo = $codigo;
        $cita->save();

        Log::info('Cita guardada con ID:', ['cita_id' => $cita->id]);
    
        $usuario = CustomUser::find($userId);
        return redirect()->route('mostrar.ticket', ['citaId' => $cita->id]);
    }

    public function mostrarTicket($citaId)
    {
        $cita = Cita::find($citaId);
    
        if (!$cita) {
            return redirect()->back()->with('error', 'Cita no encontrada.');
        }
    
        if (Auth::id() !== $cita->user_id) {
            return redirect()->route('view')->with('error', 'No tienes permiso para acceder a esta cita.');
        }
    
        return view('ticket', compact('cita'));
    }
    

    public function eliminarCita($id)
    {
        $cita = Cita::findOrFail($id);
        $cita->delete();
        return redirect()->route('view')->with('success', '¡La cita ha sido eliminada correctamente!');
    }



    public function filterImage(Request $request)
    {
        $user_id = Auth::id();
        $hairstyle_id = $request->input('estilo_id');
    
        $fotosQuery = HaircutGallery::query();
        
        if ($request->has('user_id') && $request->input('user_id') === strval($user_id)) {
            $fotosQuery->where('user_id', $user_id);
        }
    
        if ($hairstyle_id) {
            $fotosQuery->where('hairstyle_id', $hairstyle_id);
        }
    
        $fotos = $fotosQuery->paginate(9);
    
        $barber = Barbero::all();
        $hairstyle = EstilosDeCortes::all();
        $usuarios = CustomUser::all();
        $estilos = EstilosDeCortes::all();
    
        return view('index', compact('fotos', 'barber', 'hairstyle', 'usuarios', 'estilos'));
    }
    
    
    
    
    
    



    public function updateProfile(Request $request) {
        $user = Auth::user();
    
        $request->validate([
            'name' => 'required',
            'surname' => 'nullable',
            'username' => 'required|unique:custom_users,username,' . $user->id,
            'phone' => 'required',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:hombre,mujer',
            'email' => 'required|email|unique:custom_users,email,' . $user->id,
            'password' => 'nullable|min:6',
            'confirm_password' => 'nullable|same:password',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);
    
        if ($request->input('name') !== $user->name) {
            $user->name = $request->input('name');
        }
    
        if ($request->input('surname') !== $user->surname) {
            $user->surname = $request->input('surname');
        }
    
        if ($request->input('username') !== $user->username) {
            $user->username = $request->input('username');
        }
    
        if ($request->input('phone') !== $user->phone) {
            $user->phone = $request->input('phone');
        }
    
        if ($request->input('date_of_birth') !== $user->date_of_birth) {
            $user->date_of_birth = $request->input('date_of_birth');
        }
    
        if ($request->input('gender') !== $user->gender) {
            $user->gender = $request->input('gender');
        }
    
        if ($request->input('email') !== $user->email) {
            $user->email = $request->input('email');
        }
    
        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }
    
        if ($request->hasFile('profile_image')) {
            $name = $user->name;
            $surname = $user->surname;
            $imageExtension = $request->file('profile_image')->getClientOriginalExtension();
            $imageName = $name . $surname . '.' . $imageExtension;
            $request->file('profile_image')->storeAs('public/profile_images', $imageName);
            $user->profile_image = $imageName;
        }
    
        $user->save();
    
        return redirect()->back()->with('success', 'Perfil actualizado correctamente.');
    }
    
    public function deleteAccount(Request $request)
{
    $user = Auth::user();

    if ($user) {
        $user->delete();
        
        return redirect()->route('login')->with('success', 'Cuenta eliminada correctamente.');
    }

    return redirect()->back()->with('error', 'Hubo un problema al intentar eliminar la cuenta.');
}
    

    public function logout(){
        Auth::logout();
        return redirect()->route('view');
    }
}