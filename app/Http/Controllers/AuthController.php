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

        if ($usuario && $usuario->contrasena === $request->contrasena) {
            session(['usuario' => $usuario]);
            return redirect()->intended('/dashboard');
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