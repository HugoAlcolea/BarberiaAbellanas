<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\CustomUser;
use Illuminate\Support\Facades\Auth;
use App\Models\Barbero;
use App\Models\Servicio;

class AdminController extends Controller
{
    public function index()
    {
        $users = CustomUser::all();
        return view('admin.mainTablet', compact('users'));
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

}