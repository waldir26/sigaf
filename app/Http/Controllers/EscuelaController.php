<?php

namespace App\Http\Controllers;

use App\Models\Escuela;
use App\Models\Programa;
use Illuminate\Http\Request;

class EscuelaController extends Controller
{
    public function index()
    {
        $escuelas = Escuela::with('programa')->get();
        $programas = Programa::all();
        return view('escuelas.index', compact('escuelas', 'programas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_escuela' => 'required|max:200',
            'director' => 'nullable|max:150',
            'municipio' => 'nullable|max:100',
            'cantidad_estudiantes' => 'nullable|integer|min:0',
            'id_programa' => 'nullable|exists:programas,id_programa'
        ]);

        $escuela = Escuela::create($request->all());
        return response()->json(['success' => true, 'escuela' => $escuela]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre_escuela' => 'required|max:200',
            'director' => 'nullable|max:150',
            'municipio' => 'nullable|max:100',
            'cantidad_estudiantes' => 'nullable|integer|min:0',
            'id_programa' => 'nullable|exists:programas,id_programa'
        ]);

        $escuela = Escuela::findOrFail($id);
        $escuela->update($request->all());
        return response()->json(['success' => true, 'escuela' => $escuela]);
    }

    public function destroy($id)
    {
        $escuela = Escuela::findOrFail($id);
        $escuela->delete();
        return response()->json(['success' => true]);
    }

    public function show($id)
    {
        $escuela = Escuela::with('programa')->findOrFail($id);
        return response()->json($escuela);
    }
}