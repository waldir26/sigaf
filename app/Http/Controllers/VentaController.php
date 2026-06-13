<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\MovimientoFinanciero;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class VentaController extends Controller
{
    public function index(Request $request)
    {
        $query = Venta::query();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('articulo', 'LIKE', "%{$search}%");
        }

        if ($request->has('articulo') && $request->articulo != '') {
            $query->where('articulo', $request->articulo);
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

        $ventas = $query->paginate(15);
        $totalIngresos = Venta::sum('monto');

        $articulosUnicos = Venta::select('articulo')->distinct()->pluck('articulo');

        return view('ventas.index', compact('ventas', 'totalIngresos', 'articulosUnicos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'articulo' => 'required|max:200',
            'monto' => 'required|numeric|min:0',
            'fecha' => 'required|date'
        ]);

        $venta = Venta::create($request->all());

        MovimientoFinanciero::create([
            'tipo' => 'Ingreso',
            'origen' => 'Venta',
            'monto' => $venta->monto,
            'fecha' => $venta->fecha,
            'descripcion' => 'Venta de: ' . $venta->articulo,
            'tabla_referencia' => 'ventas_bienes',
            'id_referencia' => $venta->id_venta
        ]);

        return response()->json(['success' => true, 'venta' => $venta]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'articulo' => 'required|max:200',
            'monto' => 'required|numeric|min:0',
            'fecha' => 'required|date'
        ]);

        $venta = Venta::findOrFail($id);
        $venta->update($request->all());

        $movimiento = MovimientoFinanciero::where('tabla_referencia', 'ventas_bienes')
            ->where('id_referencia', $venta->id_venta)
            ->first();

        if ($movimiento) {
            $movimiento->update([
                'monto' => $venta->monto,
                'fecha' => $venta->fecha,
                'descripcion' => 'Venta de: ' . $venta->articulo
            ]);
        } else {
            MovimientoFinanciero::create([
                'tipo' => 'Ingreso',
                'origen' => 'Venta',
                'monto' => $venta->monto,
                'fecha' => $venta->fecha,
                'descripcion' => 'Venta de: ' . $venta->articulo,
                'tabla_referencia' => 'ventas_bienes',
                'id_referencia' => $venta->id_venta
            ]);
        }

        return response()->json(['success' => true, 'venta' => $venta]);
    }

    public function destroy($id)
    {
        $venta = Venta::findOrFail($id);

        MovimientoFinanciero::where('tabla_referencia', 'ventas_bienes')
            ->where('id_referencia', $venta->id_venta)
            ->delete();

        $venta->delete();
        return response()->json(['success' => true]);
    }

    public function show($id)
    {
        $venta = Venta::findOrFail($id);
        return response()->json($venta);
    }

    public function exportPdf($id)
    {
        $venta = Venta::findOrFail($id);

        $pdf = Pdf::loadView('ventas.pdf', compact('venta'));

        $pdf->setOptions([
            'defaultFont' => 'sans-serif',
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
            'dpi' => 96,
        ]);

        return $pdf->stream('comprobante_venta_' . $venta->id_venta . '.pdf');
    }

    public function exportReporte(Request $request)
    {
        $query = Venta::query();

        if ($request->has('articulo') && $request->articulo != '') {
            $query->where('articulo', $request->articulo);
        }
        if ($request->has('fecha_desde') && $request->fecha_desde != '') {
            $query->whereDate('fecha', '>=', $request->fecha_desde);
        }
        if ($request->has('fecha_hasta') && $request->fecha_hasta != '') {
            $query->whereDate('fecha', '<=', $request->fecha_hasta);
        }

        $ventas = $query->orderBy('fecha', 'desc')->get();
        $totalIngresos = $ventas->sum('monto');

        $pdf = Pdf::loadView('ventas.reporte', compact('ventas', 'totalIngresos'));

        $pdf->setOptions([
            'defaultFont' => 'sans-serif',
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
            'dpi' => 96,
        ]);

        return $pdf->stream('reporte_ventas_' . date('Y-m-d') . '.pdf');
    }
}
