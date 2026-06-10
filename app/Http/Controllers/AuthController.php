<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'correo' => 'required|email',
            'contrasena' => 'required'
        ]);

        // Buscar usuario por correo
        $usuario = \App\Models\Usuario::where('correo', $request->correo)->first();

        // Verificar si el usuario existe, la contraseña es correcta Y el estado es activo
        if ($usuario && password_verify($request->contrasena, $usuario->contrasena) && $usuario->estado == 'activo') {
            session(['usuario' => $usuario]);
            return redirect()->intended('/dashboard');
        }

        // Mensaje de error específico si está inactivo
        if ($usuario && $usuario->estado == 'inactivo') {
            return back()->withErrors([
                'correo' => 'Su cuenta está inactiva. Contacte al administrador.',
            ]);
        }

        return back()->withErrors([
            'correo' => 'Las credenciales no coinciden.',
        ]);
    }

    public function logout()
    {
        session()->forget('usuario');
        return redirect('/login');
    }
}
