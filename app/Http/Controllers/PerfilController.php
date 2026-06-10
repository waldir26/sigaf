<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PerfilController extends Controller
{
    public function index()
    {
        $usuario = session('usuario');
        return view('perfil.index', compact('usuario'));
    }

    public function update(Request $request)
    {
        $usuario = Usuario::find(session('usuario')->id_usuario);

        $rules = [
            'nombre' => 'required|max:100',
            'apellido' => 'required|max:100',
            'usuario' => 'required|unique:usuarios,usuario,' . $usuario->id_usuario . ',id_usuario',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:5120'
        ];

        // Solo admin puede cambiar correo
        if (session('usuario')->rol == 'admin') {
            $rules['correo'] = 'required|email|unique:usuarios,correo,' . $usuario->id_usuario . ',id_usuario';
        }

        $request->validate($rules);

        $usuario->nombre = $request->nombre;
        $usuario->apellido = $request->apellido;
        $usuario->usuario = $request->usuario;

        // Solo admin puede cambiar correo
        if (session('usuario')->rol == 'admin') {
            $usuario->correo = $request->correo;
        }

        // Subir foto
        if ($request->hasFile('foto')) {
            if ($usuario->foto && file_exists(public_path($usuario->foto))) {
                unlink(public_path($usuario->foto));
            }
            $file = $request->file('foto');
            $nombre = 'perfil_' . $usuario->id_usuario . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/perfiles'), $nombre);
            $usuario->foto = 'uploads/perfiles/' . $nombre;
        }

        // Solo admin puede cambiar contraseña
        if (session('usuario')->rol == 'admin' && $request->filled('password')) {
            $request->validate([
                'password' => 'min:6|confirmed',
            ]);
            $usuario->contrasena = bcrypt($request->password);
        }

        $usuario->save();
        session(['usuario' => $usuario]);

        return redirect()->route('perfil.index')->with('success', 'Perfil actualizado con éxito');
    }
}
