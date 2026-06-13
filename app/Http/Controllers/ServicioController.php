<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use App\Models\MovimientoFinanciero;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ServicioController extends Controller
{
    public function index(Request $request)
    {
        $query = Servicio::query();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('tipo_servicio', 'LIKE', "%{$search}%")
                ->orWhere('responsable', 'LIKE', "%{$search}%")
                ->orWhere('descripcion', 'LIKE', "%{$search}%");
        }

        if ($request->has('tipo') && $request->tipo != '') {
            $query->where('tipo_servicio', $request->tipo);
        }

        if ($request->has('responsable') && $request->responsable != '') {
            $query->where('responsable', 'LIKE', "%{$request->responsable}%");
        }

        if ($request->has('fecha_desde') && $request->fecha_desde != '') {
            $query->whereDate('fecha', '>=', $request->fecha_desde);
        }
        if ($request->has('fecha_hasta') && $request->fecha_hasta != '') {
            $query->whereDate('fecha', '<=', $request->fecha_hasta);
        }

        $orden = $request->get('orden', 'fecha_desc');
        switch ($orden) {
            case 'fecha_asc':
                $query->orderBy('fecha', 'asc');
                break;
            case 'monto_asc':
                $query->orderBy('monto', 'asc');
                break;
            case 'monto_desc':
                $query->orderBy('monto', 'desc');
                break;
            default:
                $query->orderBy('fecha', 'desc');
                break;
        }

        $servicios = $query->paginate(15);
        $totalIngresos = Servicio::sum('monto');

        $tipos = Servicio::select('tipo_servicio')->distinct()->pluck('tipo_servicio');

        return view('servicios.index', compact('servicios', 'totalIngresos', 'tipos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipo_servicio' => 'required|max:150',
            'descripcion' => 'nullable',
            'responsable' => 'nullable|max:150',
            'fecha' => 'required|date',
            'monto' => 'required|numeric|min:0'
        ]);

        $servicio = Servicio::create($request->all());

        MovimientoFinanciero::create([
            'tipo' => 'Ingreso',
            'origen' => 'Servicio',
            'monto' => $servicio->monto,
            'fecha' => $servicio->fecha,
            'descripcion' => $servicio->tipo_servicio . ' - ' . ($servicio->descripcion ?? ''),
            'tabla_referencia' => 'servicios_actividades',
            'id_referencia' => $servicio->id_servicio
        ]);

        return response()->json(['success' => true, 'servicio' => $servicio]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tipo_servicio' => 'required|max:150',
            'descripcion' => 'nullable',
            'responsable' => 'nullable|max:150',
            'fecha' => 'required|date',
            'monto' => 'required|numeric|min:0'
        ]);

        $servicio = Servicio::findOrFail($id);
        $servicio->update($request->all());

        $movimiento = MovimientoFinanciero::where('tabla_referencia', 'servicios_actividades')
            ->where('id_referencia', $servicio->id_servicio)
            ->first();

        if ($movimiento) {
            $movimiento->update([
                'monto' => $servicio->monto,
                'fecha' => $servicio->fecha,
                'descripcion' => $servicio->tipo_servicio . ' - ' . ($servicio->descripcion ?? '')
            ]);
        } else {
            MovimientoFinanciero::create([
                'tipo' => 'Ingreso',
                'origen' => 'Servicio',
                'monto' => $servicio->monto,
                'fecha' => $servicio->fecha,
                'descripcion' => $servicio->tipo_servicio . ' - ' . ($servicio->descripcion ?? ''),
                'tabla_referencia' => 'servicios_actividades',
                'id_referencia' => $servicio->id_servicio
            ]);
        }

        return response()->json(['success' => true, 'servicio' => $servicio]);
    }

    public function destroy($id)
    {
        $servicio = Servicio::findOrFail($id);

        MovimientoFinanciero::where('tabla_referencia', 'servicios_actividades')
            ->where('id_referencia', $servicio->id_servicio)
            ->delete();

        $servicio->delete();
        return response()->json(['success' => true]);
    }

    public function show($id)
    {
        $servicio = Servicio::findOrFail($id);
        return response()->json($servicio);
    }

    public function exportPdf($id)
    {
        $servicio = Servicio::findOrFail($id);

        $pdf = Pdf::loadView('servicios.pdf', compact('servicio'));

        $pdf->setOptions([
            'defaultFont' => 'sans-serif',
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
            'dpi' => 96,
        ]);

        return $pdf->stream('comprobante_servicio_' . $servicio->id_servicio . '.pdf');
    }

    public function exportReporte(Request $request)
    {
        $query = Servicio::query();

        if ($request->has('tipo') && $request->tipo != '') {
            $query->where('tipo_servicio', $request->tipo);
        }
        if ($request->has('responsable') && $request->responsable != '') {
            $query->where('responsable', 'LIKE', "%{$request->responsable}%");
        }
        if ($request->has('fecha_desde') && $request->fecha_desde != '') {
            $query->whereDate('fecha', '>=', $request->fecha_desde);
        }
        if ($request->has('fecha_hasta') && $request->fecha_hasta != '') {
            $query->whereDate('fecha', '<=', $request->fecha_hasta);
        }

        $servicios = $query->orderBy('fecha', 'desc')->get();
        $totalIngresos = $servicios->sum('monto');

        $pdf = Pdf::loadView('servicios.reporte', compact('servicios', 'totalIngresos'));

        $pdf->setOptions([
            'defaultFont' => 'sans-serif',
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
            'dpi' => 96,
        ]);

        return $pdf->stream('reporte_servicios_' . date('Y-m-d') . '.pdf');
    }
}
