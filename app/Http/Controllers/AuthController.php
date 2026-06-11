<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'correo' => 'required|email',
            'contrasena' => 'required'
        ]);

        // Verificar si ha excedido el límite de intentos
        if ($this->hasTooManyLoginAttempts($request)) {
            return $this->sendLockoutResponse($request);
        }

        // Buscar usuario por correo
        $usuario = \App\Models\Usuario::where('correo', $request->correo)->first();

        // Verificar si el usuario existe, la contraseña es correcta Y el estado es activo
        if ($usuario && password_verify($request->contrasena, $usuario->contrasena) && $usuario->estado == 'activo') {
            $this->clearLoginAttempts($request);
            session(['usuario' => $usuario]);
            return redirect()->intended('/dashboard');
        }

        // Incrementar contador de intentos fallidos
        $this->incrementLoginAttempts($request);

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

    // Métodos para manejar intentos de login
    protected function hasTooManyLoginAttempts(Request $request)
    {
        $attempts = session('login_attempts', 0);
        $lastAttempt = session('login_last_attempt', 0);

        if ($attempts >= 5) {
            $timeSinceLastAttempt = time() - $lastAttempt;
            if ($timeSinceLastAttempt < 60) {
                return true;
            } else {
                $this->clearLoginAttempts($request);
                return false;
            }
        }
        return false;
    }

    protected function incrementLoginAttempts(Request $request)
    {
        session(['login_attempts' => session('login_attempts', 0) + 1]);
        session(['login_last_attempt' => time()]);
    }

    protected function clearLoginAttempts(Request $request)
    {
        session()->forget('login_attempts');
        session()->forget('login_last_attempt');
    }

    protected function sendLockoutResponse(Request $request)
    {
        $seconds = 60 - (time() - session('login_last_attempt', time()));

        throw ValidationException::withMessages([
            'correo' => [trans('auth.throttle', ['seconds' => $seconds])],
        ]);
    }
}
