<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Credencial;

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

        // Buscar la credencial por username
        $credencial = Credencial::where('username', $request->username)
            ->with(['rol', 'admin', 'employee'])
            ->first();

        // Debug información (puedes eliminar esto después)
        \Log::info('Login attempt:', [
            'username' => $request->username,
            'credencial_found' => $credencial ? 'yes' : 'no',
            'rol' => $credencial ? $credencial->rol->nombre : 'none'
        ]);

        if (!$credencial) {
            return back()->withErrors([
                'username' => 'Usuario no encontrado.',
            ]);
        }

        // Verificar la contraseña
        if (!Hash::check($request->password, $credencial->password)) {
            return back()->withErrors([
                'username' => 'Contraseña incorrecta.',
            ]);
        }

        // Autenticar al usuario
        Auth::login($credencial);

        // Redirigir según el rol
        if ($credencial->esAdmin()) {
            return redirect()->route('admin.empleados')
                ->with('success', '¡Bienvenido Administrador!');
        } elseif ($credencial->esEmpleado()) {
            return redirect()->route('empleado.dashboard')
                ->with('success', '¡Bienvenido Empleado!');
        }

        // Rol no reconocido
        Auth::logout();
        return back()->withErrors([
            'username' => 'Rol no válido.',
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