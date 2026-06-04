<?php

namespace App\Http\Controllers;

use App\Models\Programa;
use Illuminate\Http\Request;

class ProgramaController extends Controller
{
    public function index()
    {
        $programas = Programa::all();
        return view('programas.index', compact('programas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|max:150',
            'descripcion' => 'nullable',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'estado' => 'required|in:activo,inactivo,finalizado'
        ]);

        $programa = Programa::create($request->all());
        return response()->json(['success' => true, 'programa' => $programa]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|max:150',
            'descripcion' => 'nullable',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'estado' => 'required|in:activo,inactivo,finalizado'
        ]);

        $programa = Programa::findOrFail($id);
        $programa->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'estado' => $request->estado
        ]);
        
        return response()->json(['success' => true, 'programa' => $programa]);
    }

    public function destroy($id)
    {
        $programa = Programa::findOrFail($id);
        $programa->delete();
        return response()->json(['success' => true]);
    }

    public function show($id)
    {
        $programa = Programa::findOrFail($id);
        return response()->json($programa);
    }
}