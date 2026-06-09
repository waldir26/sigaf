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
            $query->where(function ($q) use ($search) {
                $q->where('id_participante', 'LIKE', "%{$search}%")
                    ->orWhere('nombres', 'LIKE', "%{$search}%")
                    ->orWhere('apellidos', 'LIKE', "%{$search}%")
                    ->orWhere('correo', 'LIKE', "%{$search}%")
                    ->orWhere('telefono', 'LIKE', "%{$search}%");
            });
        }

        // Filtro por sexo
        if ($request->has('sexo') && $request->sexo != '') {
            $query->where('sexo', $request->sexo);
        }

        // Filtro por Programa
        if ($request->has('programa_id') && $request->programa_id != '') {
            $programaId = $request->programa_id;
            $participantesIds = Inscripcion::where('id_programa', $programaId)
                ->pluck('id_participante')
                ->unique();
            $query->whereIn('id_participante', $participantesIds);
        }

        // Filtro por Tipo de inscripción
        if ($request->has('tipo_inscripcion') && $request->tipo_inscripcion != '') {
            $tipo = $request->tipo_inscripcion;
            $participantesIds = Inscripcion::where('tipo_inscripcion', $tipo)
                ->pluck('id_participante')
                ->unique();
            $query->whereIn('id_participante', $participantesIds);
        }

        // Filtro por Escuela
        if ($request->has('escuela_id') && $request->escuela_id != '') {
            $escuelaId = $request->escuela_id;
            $participantesIds = Inscripcion::where('id_escuela', $escuelaId)
                ->pluck('id_participante')
                ->unique();
            $query->whereIn('id_participante', $participantesIds);
        }

        // Ordenamiento
        $orden = $request->get('orden', 'id_desc');
        switch ($orden) {
            case 'nombre_asc':
                $query->orderBy('nombres', 'asc');
                break;
            case 'nombre_desc':
                $query->orderBy('nombres', 'desc');
                break;
            case 'apellido_asc':
                $query->orderBy('apellidos', 'asc');
                break;
            case 'apellido_desc':
                $query->orderBy('apellidos', 'desc');
                break;
            case 'id_asc':
                $query->orderBy('id_participante', 'asc');
                break;
            default:
                $query->orderBy('id_participante', 'desc');
                break;
        }

        $participantes = $query->paginate(15);
        $programas = Programa::where('estado', 'activo')->get();
        $escuelas = Escuela::all();

        $participantesPorEscuela = [];
        $escuelasList = Escuela::all();
        foreach ($escuelasList as $escuela) {
            $participantesIds = Inscripcion::where('id_escuela', $escuela->id_escuela)->pluck('id_participante')->unique();
            $participantesPorEscuela[$escuela->nombre_escuela] = Participante::whereIn('id_participante', $participantesIds)->count();
        }

        return view('participantes.index', compact('participantes', 'programas', 'escuelas', 'participantesPorEscuela'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombres' => 'required|max:150',
            'apellidos' => 'required|max:150',
            'edad' => 'nullable|integer|min:0|max:120',
            'telefono' => 'nullable|max:20',
            'correo' => 'nullable|email|max:150',
            'direccion' => 'nullable',
            'sexo' => 'nullable|in:M,F'
        ]);

        $participante = Participante::findOrFail($id);
        $participante->update($request->all());
        return response()->json(['success' => true, 'participante' => $participante]);
    }

    public function destroy($id)
    {
        $inscripcionesActivas = Inscripcion::where('id_participante', $id)->where('estado', 'activo')->count();

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

            $programa = Programa::find($request->id_programa);
            if ($programa && $programa->estado !== 'activo') {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede inscribir porque el programa no está activo'
                ], 400);
            }

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
