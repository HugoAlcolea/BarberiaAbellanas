<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomUser;
use App\Models\Barbero;
use App\Models\Servicio;
use App\Models\HaircutGallery;
use App\Models\EstilosDeCortes;
use App\Models\Cita;
use App\Models\StatsUser;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;


class AdminController extends Controller
{
    public function index()
    {
        $users = CustomUser::all();
        $barberos = Barbero::all();
        $estilos_de_cortes = EstilosDeCortes::all();
        $gallery = HaircutGallery::all();
        $citas = Cita::whereNotNull('dinero_cobrado')->get();
        
        foreach ($citas as $cita) {
            $cita->fecha = Carbon::parse($cita->fecha);
        }
    
        $facturaciones = Cita::with('usuario')->get();
    
        return view('admin.mainTablet', compact('users', 'barberos', 'estilos_de_cortes', 'gallery', 'citas', 'facturaciones'));
    }
    


    public function getUserInfo($id)
    {
        $user = CustomUser::findOrFail($id);
        return response()->json($user);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('index');
    }

    public function calendar()
    {
        $users = CustomUser::all();
        $barberos = Barbero::all(); 
        $servicios = Servicio::all(); 
        return view('admin.calendar', compact('users', 'barberos', 'servicios'));
    }

    public function searchUsers(Request $request)
    {
        $searchTerm = $request->input('search');
        $users = CustomUser::where('name', 'LIKE', "%{$searchTerm}%")
                            ->orWhere('surname', 'LIKE', "%{$searchTerm}%")
                            ->get();
    
        return response()->json($users);
    }
    
    


    public function updateUser(Request $request, $id)
    {
        $validatedData = $request->validate([
            'is_admin' => 'required|boolean',
            'name' => 'required|string',
            'surname' => 'nullable|string',
            'username' => 'required|string',
            'phone' => 'required|string',
            'date_of_birth' => 'required|date',
            'gender' => 'required|string|in:hombre,mujer',
            'email' => 'required|email',
            'password' => 'nullable|string|min:6|confirmed',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        try {
            $user = CustomUser::findOrFail($id);
    
            $newProfileImageName = $validatedData['name'] . $validatedData['surname'];
    
            if ($request->hasFile('profile_image')) {
                if ($user->profile_image) {
                    Storage::delete('public/profile_images/' . $user->profile_image);
                }
                $filePath = $request->file('profile_image')->storeAs('public/profile_images', $newProfileImageName);
                $validatedData['profile_image'] = $newProfileImageName;
            }
    
            if (!empty($validatedData['password'])) {
                $validatedData['password'] = Hash::make($validatedData['password']);
            } else {
                unset($validatedData['password']);
            }
    
            $user->update($validatedData);
    
            return response()->json($user); 
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al actualizar los datos del usuario: ' . $e->getMessage()], 500);
        }
    }

    public function createPhoto(array $dataToSave)
    {
        $photoRecord = new HaircutGallery();
        $photoRecord->photo_name = $dataToSave['photo_name'];
        $photoRecord->user_id = $dataToSave['user_id'];
        $photoRecord->barber_id = $dataToSave['barber_id'];
        $photoRecord->hairstyle_id = $dataToSave['hairstyle_id'];
    
        $saved = $photoRecord->save();
    
        if ($saved) {
            return $photoRecord;
        } else {
            throw new \Exception('Error al guardar la foto en la base de datos');
        }
    }

    public function uploadPhoto(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:custom_users,id',
            'barber_id' => 'required|exists:barberos,id',
            'hairstyle_id' => 'required|exists:estilos_de_cortes,id',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        try {
            $user = CustomUser::findOrFail($validatedData['user_id']);
            $hairstyle = EstilosDeCortes::findOrFail($validatedData['hairstyle_id']);
    
            $photoName = $user->name . $user->surname . '_' . $hairstyle->nombre . '_' . uniqid();
            $fileName = $photoName . '.' . $request->file('photo')->getClientOriginalExtension();
    
            $filePath = $request->file('photo')->storeAs('public/haircut_gallery', $fileName);
    
            $dataToSave = [
                'photo_name' => $fileName,
                'user_id' => $validatedData['user_id'],
                'barber_id' => $validatedData['barber_id'],
                'hairstyle_id' => $validatedData['hairstyle_id'],
            ];
    
            $photoRecord = $this->createPhoto($dataToSave);
    
            return redirect()->back()->with('success', 'Foto subida y vinculada al usuario correctamente');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al subir la foto: ' . $e->getMessage());
        }
    }

    public function deletePhoto($photoId)
    {
        try {
            $photo = HaircutGallery::findOrFail($photoId);
            Storage::delete('public/haircut_gallery/' . $photo->photo_name);
            $photo->delete();
            return response()->json(['message' => 'Foto eliminada correctamente.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar la foto: ' . $e->getMessage()], 500);
        }
    }
    
    public function processFacturacion(Request $request)
    {
        $validatedData = $request->validate([
            'codigo' => [
                'required',
                'string',
                'max:255',
                'exists:citas,codigo',
                'regex:/^A\d{4}$/'
            ],
            'euros' => 'required|numeric|min:0',
            'points' => 'required|integer',
        ]);

        try {
            $cita = Cita::where('codigo', $validatedData['codigo'])->firstOrFail();

            $cita->dinero_cobrado = $validatedData['euros'];
            $cita->save();

            $userId = $cita->user_id;

            $stats = StatsUser::firstOrNew(['user_id' => $userId]);

            $stats->haircuts += 1;
            $stats->points += $validatedData['points'];
            $stats->save();

            return redirect()->back()->with('success', 'Facturación procesada correctamente');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al procesar la facturación: ' . $e->getMessage());
        }
    }

    public function generarPDF(Request $request)
    {
        $validatedData = $request->validate([
            'fecha' => 'required|date',
        ]);
    
        $fecha = $request->input('fecha');
        $carbonFecha = Carbon::parse($fecha);
        $primerDiaMes = $carbonFecha->copy()->startOfMonth();
        $ultimoDiaMes = $carbonFecha->copy()->endOfMonth();
    
        $citas = Cita::whereBetween('fecha', [$primerDiaMes, $ultimoDiaMes])
                     ->whereNotNull('dinero_cobrado')
                     ->get();
    
        $carbonFechaFormateada = $carbonFecha->format('d/m/Y');
    
        $pdf = PDF::loadView('admin.pdfAdmin', compact('citas', 'carbonFechaFormateada'));
    
        return $pdf->download('facturacion_' . $carbonFecha->format('F_Y') . '.pdf');
    }
    
    


}