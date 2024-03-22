<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\CustomUser;
use Illuminate\Support\Facades\Auth;
use Google\Client;
use Google\Service\Calendar;

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
}