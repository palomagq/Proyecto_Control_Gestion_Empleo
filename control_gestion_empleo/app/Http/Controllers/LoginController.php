<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; 

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
{
    $request->validate([
        'username' => 'required|string',
        'password' => 'required|string',
    ]);

    if (Auth::attempt($request->only('username', 'password'))) {
        $request->session()->regenerate();
        
        $user = Auth::user();
        
        if ($user->rol_id == 1) {
            return redirect()->route('admin.empleados')->with('success', '¡Bienvenido Administrador!');
        } else {
            // Buscar el empleado relacionado
            $empleado = \App\Models\Empleado::where('credencial_id', $user->id)->first();
            
            if ($empleado) {
                // Redirigir al perfil del empleado con SU ID
                return redirect()->route('empleado.perfil', $empleado->id)
                    ->with('success', "¡Bienvenido {$empleado->nombre}!");
            } else {
                Auth::logout();
                return back()->withErrors(['username' => 'Error: No se encontró información del empleado.']);
            }
        }
    }

    return back()->withErrors(['username' => 'Usuario o contraseña incorrectos.']);
}
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login')->with('status', 'Sesión cerrada correctamente.');
    }
}