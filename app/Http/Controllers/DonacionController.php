<?php

namespace App\Http\Controllers;

use App\Models\Donante;
use App\Models\Donacion;
use Illuminate\Http\Request;
use Spatie\LaravelPdf\Facades\Pdf;

class DonacionController extends Controller
{
    public function index(Request $request)
    {
        $query = Donacion::with('donante');
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('donante', function($q) use ($search) {
                $q->where('nombre', 'LIKE', "%{$search}%");
            })->orWhere('monto', 'LIKE', "%{$search}%")
              ->orWhere('tipo_donacion', 'LIKE', "%{$search}%");
        }
        
        if ($request->has('tipo') && $request->tipo != '') {
            $query->where('tipo_donacion', $request->tipo);
        }
        
        if ($request->has('donante_id') && $request->donante_id != '') {
            $query->where('id_donante', $request->donante_id);
        }
        
        if ($request->has('fecha_desde') && $request->fecha_desde != '') {
            $query->whereDate('fecha', '>=', $request->fecha_desde);
        }
        if ($request->has('fecha_hasta') && $request->fecha_hasta != '') {
            $query->whereDate('fecha', '<=', $request->fecha_hasta);
        }
        
        $orden = $request->get('orden', 'fecha_desc');
        switch ($orden) {
            case 'fecha_asc': $query->orderBy('fecha', 'asc'); break;
            case 'monto_asc': $query->orderBy('monto', 'asc'); break;
            case 'monto_desc': $query->orderBy('monto', 'desc'); break;
            default: $query->orderBy('fecha', 'desc'); break;
        }
        
        $donaciones = $query->paginate(15);
        $donantes = Donante::orderBy('nombre', 'asc')->get();
        $totalMonetario = Donacion::where('tipo_donacion', 'monetaria')->sum('monto');
        
        return view('donaciones.index', compact('donaciones', 'donantes', 'totalMonetario'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_donante' => 'required|exists:donantes,id_donante',
            'tipo_donacion' => 'required|in:monetaria,especie',
            'monto' => 'nullable|numeric|min:0',
            'descripcion' => 'nullable|string',
            'fecha' => 'required|date'
        ]);

        $donacion = Donacion::create($request->all());
        return response()->json(['success' => true, 'donacion' => $donacion]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_donante' => 'required|exists:donantes,id_donante',
            'tipo_donacion' => 'required|in:monetaria,especie',
            'monto' => 'nullable|numeric|min:0',
            'descripcion' => 'nullable|string',
            'fecha' => 'required|date'
        ]);

        $donacion = Donacion::findOrFail($id);
        $donacion->update($request->all());
        return response()->json(['success' => true, 'donacion' => $donacion]);
    }

    public function destroy($id)
    {
        $donacion = Donacion::findOrFail($id);
        $donacion->delete();
        return response()->json(['success' => true]);
    }

    public function show($id)
    {
        $donacion = Donacion::with('donante')->findOrFail($id);
        return response()->json($donacion);
    }

    public function storeDonante(Request $request)
    {
        $request->validate([
            'nombre' => 'required|max:200',
            'telefono' => 'nullable|max:20',
            'correo' => 'nullable|email|max:150',
            'direccion' => 'nullable'
        ]);

        $donante = Donante::create($request->all());
        return response()->json(['success' => true, 'donante' => $donante]);
    }

    public function exportPdf($id)
    {
        $donacion = Donacion::with('donante')->findOrFail($id);
        
        return Pdf::view('donaciones.pdf', compact('donacion'))
            ->format('a4')
            ->name('donacion_' . $donacion->id_donacion . '.pdf');
    }

    public function exportReporte(Request $request)
    {
        $query = Donacion::with('donante');
        
        if ($request->has('tipo') && $request->tipo != '') {
            $query->where('tipo_donacion', $request->tipo);
        }
        if ($request->has('donante_id') && $request->donante_id != '') {
            $query->where('id_donante', $request->donante_id);
        }
        if ($request->has('fecha_desde') && $request->fecha_desde != '') {
            $query->whereDate('fecha', '>=', $request->fecha_desde);
        }
        if ($request->has('fecha_hasta') && $request->fecha_hasta != '') {
            $query->whereDate('fecha', '<=', $request->fecha_hasta);
        }
        
        $donaciones = $query->orderBy('fecha', 'desc')->get();
        $totalMonetario = $donaciones->where('tipo_donacion', 'monetaria')->sum('monto');
        
        return Pdf::view('donaciones.reporte', compact('donaciones', 'totalMonetario'))
            ->format('a4')
            ->name('reporte_donaciones_' . date('Y-m-d') . '.pdf');
    }
    //para subir el documento escaneado y sellado
        public function subirDocumentoSellado(Request $request, $id)
    {
        $request->validate([
            'documento_sellado' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120'
        ]);

        $donacion = Donacion::findOrFail($id);
        
        // Eliminar documento anterior si existe
        if ($donacion->documento_sellado && file_exists(public_path($donacion->documento_sellado))) {
            unlink(public_path($donacion->documento_sellado));
        }
        
        $file = $request->file('documento_sellado');
        $nombre = 'donacion_sellada_' . $donacion->id_donacion . '_' . time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('documentos_sellados'), $nombre);
        
        $donacion->documento_sellado = 'documentos_sellados/' . $nombre;
        $donacion->estado_sellado = 'sellado';
        $donacion->save();
        
        return response()->json(['success' => true, 'message' => 'Documento sellado subido correctamente']);
}
}