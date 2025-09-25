<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    //
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
            ->with('rol')
            ->first();

        if ($credencial && Hash::check($request->password, $credencial->password)) {
            
            // Verificar el tipo de usuario y redirigir según el rol
            if ($credencial->rol->nombre === 'admin') {
                Auth::login($credencial);
                return redirect()->route('admin.dashboard');
            } elseif ($credencial->rol->nombre === 'empleado') {
                Auth::login($credencial);
                // Aquí puedes redirigir a un dashboard de empleado si lo necesitas
                return redirect()->route('empleado.dashboard');
            }
        }

        return back()->withErrors([
            'username' => 'Las credenciales proporcionadas no son válidas.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
