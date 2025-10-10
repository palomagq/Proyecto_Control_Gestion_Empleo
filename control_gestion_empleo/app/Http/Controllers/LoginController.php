<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
            
            // DEBUG
            \Log::info("LOGIN EXITOSO - Usuario: {$user->username}, Rol ID: {$user->rol_id}");

            // REDIRECCIÓN SIMPLE Y DIRECTA
            if ($user->rol_id == 1) { // Administrador
                return redirect()->route('admin.empleados')
                    ->with('success', '¡Bienvenido Administrador!');
            } 
            else { // Cualquier otro número = Empleado
                $empleado = DB::table('tabla_empleados')
                    ->where('credencial_id', $user->id)
                    ->first();

                if ($empleado) {
                    \Log::info("Redirigiendo a empleado: {$empleado->id} - {$empleado->nombre}");
                    return redirect()->route('empleado.perfil', $empleado->id)
                        ->with('success', "¡Bienvenido {$empleado->nombre}!");
                } else {
                    Auth::logout();
                    return back()->withErrors([
                        'username' => 'Error: No se encontró información del empleado.',
                    ]);
                }
            }
        }

        return back()->withErrors([
            'username' => 'Usuario o contraseña incorrectos.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login')->with('status', 'Sesión cerrada correctamente.');
    }
}