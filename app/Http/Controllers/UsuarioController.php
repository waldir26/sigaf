<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function index(Request $request)
    {
        $query = Usuario::query();

        // Excluir al usuario actual
        $query->where('id_usuario', '!=', session('usuario')->id_usuario);

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'LIKE', "%{$search}%")
                    ->orWhere('apellido', 'LIKE', "%{$search}%")
                    ->orWhere('correo', 'LIKE', "%{$search}%")
                    ->orWhere('usuario', 'LIKE', "%{$search}%");
            });
        }

        $usuarios = $query->orderBy('id_usuario', 'desc')->paginate(15);

        return view('usuarios.index', compact('usuarios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|max:100',
            'apellido' => 'required|max:100',
            'correo' => 'required|email|unique:usuarios,correo',
            'usuario' => 'required|unique:usuarios,usuario',
            'contrasena' => 'required|min:6',
            'rol' => 'required|in:admin,empleado',
            'estado' => 'required|in:activo,inactivo',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:1024'
        ]);

        $data = $request->all();
        $data['contrasena'] = bcrypt($request->contrasena);

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $nombre = 'usuario_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/usuarios'), $nombre);
            $data['foto'] = 'uploads/usuarios/' . $nombre;
        }

        $usuario = Usuario::create($data);

        return response()->json(['success' => true, 'usuario' => $usuario]);
    }

    public function update(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);

        $request->validate([
            'nombre' => 'required|max:100',
            'apellido' => 'required|max:100',
            'correo' => 'required|email|unique:usuarios,correo,' . $id . ',id_usuario',
            'usuario' => 'required|unique:usuarios,usuario,' . $id . ',id_usuario',
            'rol' => 'required|in:admin,empleado',
            'estado' => 'required|in:activo,inactivo',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:1024'
        ]);

        $data = $request->all();

        if ($request->filled('contrasena')) {
            $request->validate(['contrasena' => 'min:6']);
            $data['contrasena'] = bcrypt($request->contrasena);
        } else {
            unset($data['contrasena']);
        }

        if ($request->hasFile('foto')) {
            if ($usuario->foto && file_exists(public_path($usuario->foto))) {
                unlink(public_path($usuario->foto));
            }
            $file = $request->file('foto');
            $nombre = 'usuario_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/usuarios'), $nombre);
            $data['foto'] = 'uploads/usuarios/' . $nombre;
        }

        $usuario->update($data);

        return response()->json(['success' => true, 'usuario' => $usuario]);
    }

    public function destroy($id)
    {
        $usuario = Usuario::findOrFail($id);

        if ($usuario->id_usuario == session('usuario')->id_usuario) {
            return response()->json(['success' => false, 'message' => 'No puedes eliminar tu propio usuario'], 400);
        }

        if ($usuario->foto && file_exists(public_path($usuario->foto))) {
            unlink(public_path($usuario->foto));
        }

        $usuario->delete();
        return response()->json(['success' => true]);
    }

    public function show($id)
    {
        $usuario = Usuario::findOrFail($id);
        return response()->json($usuario);
    }
}
