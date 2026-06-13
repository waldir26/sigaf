<?php

namespace App\Http\Controllers;

use App\Models\Gasto;
use App\Models\MovimientoFinanciero;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class GastoController extends Controller
{
    public function index(Request $request)
    {
        $query = Gasto::query();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('descripcion', 'LIKE', "%{$search}%")
                ->orWhere('categoria', 'LIKE', "%{$search}%");
        }

        if ($request->has('categoria') && $request->categoria != '') {
            $query->where('categoria', $request->categoria);
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

        $gastos = $query->paginate(15);
        $totalGastos = Gasto::sum('monto');
        $categorias = Gasto::select('categoria')->distinct()->pluck('categoria');

        return view('gastos.index', compact('gastos', 'totalGastos', 'categorias'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'categoria' => 'required|max:100',
                'descripcion' => 'nullable',
                'monto' => 'required|numeric|min:0',
                'fecha' => 'required|date'
            ]);

            $gasto = Gasto::create($validated);

            MovimientoFinanciero::create([
                'tipo' => 'Gasto',
                'origen' => $gasto->categoria,
                'monto' => $gasto->monto,
                'fecha' => $gasto->fecha,
                'descripcion' => $gasto->descripcion ?? 'Gasto de ' . $gasto->categoria,
                'tabla_referencia' => 'gastos',
                'id_referencia' => $gasto->id_gasto
            ]);

            return response()->json(['success' => true, 'gasto' => $gasto]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Complete todos los campos correctamente'
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'categoria' => 'required|max:100',
                'descripcion' => 'nullable',
                'monto' => 'required|numeric|min:0',
                'fecha' => 'required|date'
            ]);

            $gasto = Gasto::findOrFail($id);
            $gasto->update($validated);

            $movimiento = MovimientoFinanciero::where('tabla_referencia', 'gastos')
                ->where('id_referencia', $gasto->id_gasto)
                ->first();

            if ($movimiento) {
                $movimiento->update([
                    'origen' => $gasto->categoria,
                    'monto' => $gasto->monto,
                    'fecha' => $gasto->fecha,
                    'descripcion' => $gasto->descripcion ?? 'Gasto de ' . $gasto->categoria
                ]);
            }

            return response()->json(['success' => true, 'gasto' => $gasto]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al actualizar'], 500);
        }
    }

    public function destroy($id)
    {
        $gasto = Gasto::findOrFail($id);

        MovimientoFinanciero::where('tabla_referencia', 'gastos')
            ->where('id_referencia', $gasto->id_gasto)
            ->delete();

        $gasto->delete();
        return response()->json(['success' => true]);
    }

    public function show($id)
    {
        $gasto = Gasto::findOrFail($id);
        return response()->json($gasto);
    }

    public function exportPdf($id)
    {
        $gasto = Gasto::findOrFail($id);

        $pdf = Pdf::loadView('gastos.pdf', compact('gasto'));

        $pdf->setOptions([
            'defaultFont' => 'sans-serif',
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
            'dpi' => 96,
        ]);

        return $pdf->stream('comprobante_gasto_' . $gasto->id_gasto . '.pdf');
    }

    public function exportReporte(Request $request)
    {
        $query = Gasto::query();

        if ($request->has('categoria') && $request->categoria != '') {
            $query->where('categoria', $request->categoria);
        }
        if ($request->has('fecha_desde') && $request->fecha_desde != '') {
            $query->whereDate('fecha', '>=', $request->fecha_desde);
        }
        if ($request->has('fecha_hasta') && $request->fecha_hasta != '') {
            $query->whereDate('fecha', '<=', $request->fecha_hasta);
        }

        $gastos = $query->orderBy('fecha', 'desc')->get();
        $totalGastos = $gastos->sum('monto');

        $pdf = Pdf::loadView('gastos.reporte', compact('gastos', 'totalGastos'));

        $pdf->setOptions([
            'defaultFont' => 'sans-serif',
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
            'dpi' => 96,
        ]);

        return $pdf->stream('reporte_gastos_' . date('Y-m-d') . '.pdf');
    }
}
