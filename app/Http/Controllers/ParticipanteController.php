<?php

namespace App\Http\Controllers;

use App\Models\Participante;
use Illuminate\Http\Request;

class ParticipanteController extends Controller
{
    public function index()
    {
        $participantes = Participante::all();
        return view('participantes.index', compact('participantes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombres' => 'required|max:150',
            'apellidos' => 'required|max:150',
            'edad' => 'nullable|integer|min:0|max:120',
            'telefono' => 'nullable|max:20',
            'correo' => 'nullable|email|max:150',
            'direccion' => 'nullable'
        ]);

        $participante = Participante::create($request->all());
        return response()->json(['success' => true, 'participante' => $participante]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombres' => 'required|max:150',
            'apellidos' => 'required|max:150',
            'edad' => 'nullable|integer|min:0|max:120',
            'telefono' => 'nullable|max:20',
            'correo' => 'nullable|email|max:150',
            'direccion' => 'nullable'
        ]);

        $participante = Participante::findOrFail($id);
        $participante->update($request->all());
        return response()->json(['success' => true, 'participante' => $participante]);
    }

    public function destroy($id)
    {
        $participante = Participante::findOrFail($id);
        $participante->delete();
        return response()->json(['success' => true]);
    }

    public function show($id)
    {
        $participante = Participante::findOrFail($id);
        return response()->json($participante);
    }
}