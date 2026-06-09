<?php

namespace App\Http\Controllers;

use App\Models\Gasto;
use Illuminate\Http\Request;
use Spatie\LaravelPdf\Facades\Pdf;

class GastoController extends Controller
{
    public function index(Request $request)
    {
        $query = Gasto::query();

        // Buscador general
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('descripcion', 'LIKE', "%{$search}%")
                ->orWhere('categoria', 'LIKE', "%{$search}%");
        }

        // Filtro por categoría
        if ($request->has('categoria') && $request->categoria != '') {
            $query->where('categoria', $request->categoria);
        }

        // Filtros de fecha
        if ($request->has('fecha_desde') && $request->fecha_desde != '') {
            $query->whereDate('fecha', '>=', $request->fecha_desde);
        }
        if ($request->has('fecha_hasta') && $request->fecha_hasta != '') {
            $query->whereDate('fecha', '<=', $request->fecha_hasta);
        }

        // Ordenamiento
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

        // Categorías únicas existentes para el filtro
        $categorias = Gasto::select('categoria')->distinct()->pluck('categoria');

        return view('gastos.index', compact('gastos', 'totalGastos', 'categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'categoria' => 'required|max:100',
            'descripcion' => 'nullable',
            'monto' => 'required|numeric|min:0',
            'fecha' => 'required|date'
        ]);

        $gasto = Gasto::create($request->all());
        return response()->json(['success' => true, 'gasto' => $gasto]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'categoria' => 'required|max:100',
            'descripcion' => 'nullable',
            'monto' => 'required|numeric|min:0',
            'fecha' => 'required|date'
        ]);

        $gasto = Gasto::findOrFail($id);
        $gasto->update($request->all());
        return response()->json(['success' => true, 'gasto' => $gasto]);
    }

    public function destroy($id)
    {
        $gasto = Gasto::findOrFail($id);
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

        return Pdf::view('gastos.pdf', compact('gasto'))
            ->format('a4')
            ->name('comprobante_gasto_' . $gasto->id_gasto . '.pdf');
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

        return Pdf::view('gastos.reporte', compact('gastos', 'totalGastos'))
            ->format('a4')
            ->name('reporte_gastos_' . date('Y-m-d') . '.pdf');
    }
}
