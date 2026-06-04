<?php

namespace App\Http\Controllers;

use App\Models\Participante;
use App\Models\Inscripcion;
use App\Models\Programa;
use App\Models\Escuela;
use Illuminate\Http\Request;

class ParticipanteController extends Controller
{
    public function index(Request $request)
    {
        $query = Participante::query();
        
        // Buscador
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id_participante', 'LIKE', "%{$search}%")
                  ->orWhere('nombres', 'LIKE', "%{$search}%")
                  ->orWhere('apellidos', 'LIKE', "%{$search}%")
                  ->orWhere('correo', 'LIKE', "%{$search}%")
                  ->orWhere('telefono', 'LIKE', "%{$search}%");
            });
        }
        
        $participantes = $query->orderBy('id_participante', 'desc')->paginate(10);
        $programas = Programa::all();
        $escuelas = Escuela::all();
        
        return view('participantes.index', compact('participantes', 'programas', 'escuelas'));
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
    $inscripcionesActivas = Inscripcion::where('id_participante', $id)
        ->where('estado', 'activo')
        ->count();
    
    if ($inscripcionesActivas > 0) {
        return response()->json([
            'success' => false, 
            'message' => 'No se puede eliminar porque tiene inscripciones ACTIVAS. Cambie el estado a Finalizado o Cancelado primero.'
        ], 400);
    }
    
    Inscripcion::where('id_participante', $id)->delete();
    $participante = Participante::findOrFail($id);
    $participante->delete();
    
    return response()->json(['success' => true]);
}

    public function show($id)
    {
        $participante = Participante::findOrFail($id);
        $inscripciones = Inscripcion::with(['programa', 'escuela'])
            ->where('id_participante', $id)
            ->get();
        
        return response()->json([
            'participante' => $participante,
            'inscripciones' => $inscripciones
        ]);
    }
    
    public function addInscripcion(Request $request)
    {
        try {
            $request->validate([
                'id_participante' => 'required|exists:participantes,id_participante',
                'id_programa' => 'required|exists:programas,id_programa',
                'tipo_inscripcion' => 'required|in:escolar,sabatino,externo',
                'id_escuela' => 'nullable|required_if:tipo_inscripcion,escolar|exists:escuelas_beneficiarias,id_escuela'
            ]);
            
            // Verificar si ya está inscrito en ese programa
            $existe = Inscripcion::where('id_participante', $request->id_participante)
                ->where('id_programa', $request->id_programa)
                ->exists();
                
            if ($existe) {
                return response()->json([
                    'success' => false, 
                    'message' => 'El participante ya está inscrito en este programa'
                ], 422);
            }
            
            $inscripcion = Inscripcion::create([
                'id_participante' => $request->id_participante,
                'id_programa' => $request->id_programa,
                'tipo_inscripcion' => $request->tipo_inscripcion,
                'id_escuela' => $request->id_escuela,
                'fecha_inscripcion' => date('Y-m-d'),
                'estado' => 'activo'
            ]);
            
            return response()->json(['success' => true, 'inscripcion' => $inscripcion]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación: Verifique los datos ingresados'
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}