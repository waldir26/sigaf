<?php

namespace App\Http\Controllers;

use App\Models\Inscripcion;
use App\Models\Participante;
use App\Models\Programa;
use App\Models\Escuela;
use Illuminate\Http\Request;

class InscripcionController extends Controller
{
    public function index()
    {
        $inscripciones = Inscripcion::with(['participante', 'programa', 'escuela'])->get();
        $participantes = Participante::all();
        $programas = Programa::all();
        $escuelas = Escuela::all();
        
        return view('inscripciones.index', compact('inscripciones', 'participantes', 'programas', 'escuelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombres' => 'required|max:150',
            'apellidos' => 'required|max:150',
            'edad' => 'nullable|integer|min:0|max:120',
            'telefono' => 'nullable|max:20',
            'correo' => 'nullable|email|max:150',
            'direccion' => 'nullable',
            'id_programa' => 'required|exists:programas,id_programa',
            'fecha_inscripcion' => 'nullable|date',
            'estado' => 'required|in:activo,finalizado,cancelado',
            'tipo_inscripcion' => 'required|in:escolar,sabatino,externo',
            'id_escuela' => 'nullable|required_if:tipo_inscripcion,escolar|exists:escuelas_beneficiarias,id_escuela'
        ]);

        // Crear el participante
        $participante = Participante::create([
            'nombres' => $request->nombres,
            'apellidos' => $request->apellidos,
            'edad' => $request->edad,
            'telefono' => $request->telefono,
            'correo' => $request->correo,
            'direccion' => $request->direccion
        ]);

        // Crear la inscripción
        $data = [
            'id_participante' => $participante->id_participante,
            'id_programa' => $request->id_programa,
            'fecha_inscripcion' => $request->fecha_inscripcion ?? date('Y-m-d'),
            'estado' => $request->estado,
            'tipo_inscripcion' => $request->tipo_inscripcion,
            'id_escuela' => $request->id_escuela
        ];

        $inscripcion = Inscripcion::create($data);
        
        return response()->json(['success' => true, 'inscripcion' => $inscripcion]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_programa' => 'required|exists:programas,id_programa',
            'fecha_inscripcion' => 'nullable|date',
            'estado' => 'required|in:activo,finalizado,cancelado',
            'tipo_inscripcion' => 'required|in:escolar,sabatino,externo',
            'id_escuela' => 'nullable|required_if:tipo_inscripcion,escolar|exists:escuelas_beneficiarias,id_escuela'
        ]);

        $inscripcion = Inscripcion::findOrFail($id);
        $inscripcion->update($request->all());
        return response()->json(['success' => true, 'inscripcion' => $inscripcion]);
    }

    public function destroy($id)
    {
        $inscripcion = Inscripcion::findOrFail($id);
        $inscripcion->delete();
        return response()->json(['success' => true]);
    }

    public function show($id)
    {
        $inscripcion = Inscripcion::with(['participante', 'programa', 'escuela'])->findOrFail($id);
        return response()->json($inscripcion);
    }
}