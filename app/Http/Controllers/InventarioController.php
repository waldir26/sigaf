<?php

namespace App\Http\Controllers;

use App\Models\Inventario;
use Illuminate\Http\Request;

class InventarioController extends Controller
{
    public function index(Request $request)
    {
        $query = Inventario::query();
        
        // Buscador
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nombre_producto', 'LIKE', "%{$search}%")
                  ->orWhere('categoria', 'LIKE', "%{$search}%");
            });
        }
        
        // Filtro por categoría
        if ($request->has('categoria') && $request->categoria != '') {
            $query->where('categoria', $request->categoria);
        }
        
        // Filtro por estado
        if ($request->has('estado') && $request->estado != '') {
            $query->where('estado', $request->estado);
        }
        
        // Ordenamiento
        $orden = $request->get('orden', 'id_desc');
        switch ($orden) {
            case 'nombre_asc':
                $query->orderBy('nombre_producto', 'asc');
                break;
            case 'nombre_desc':
                $query->orderBy('nombre_producto', 'desc');
                break;
            case 'cantidad_asc':
                $query->orderBy('cantidad', 'asc');
                break;
            case 'cantidad_desc':
                $query->orderBy('cantidad', 'desc');
                break;
            case 'id_asc':
                $query->orderBy('id_producto', 'asc');
                break;
            case 'id_desc':
            default:
                $query->orderBy('id_producto', 'desc');
                break;
        }
        
        $productos = $query->paginate(15);
        
        // Obtener categorías únicas para el filtro
        $categorias = Inventario::select('categoria')->distinct()->whereNotNull('categoria')->pluck('categoria');
        
        return view('inventario.index', compact('productos', 'categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_producto' => 'required|max:200',
            'categoria' => 'nullable|max:100',
            'cantidad' => 'required|integer|min:0',
            'estado' => 'required|in:disponible,agotado,dado_baja'
        ]);

        $producto = Inventario::create($request->all());
        return response()->json(['success' => true, 'producto' => $producto]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre_producto' => 'required|max:200',
            'categoria' => 'nullable|max:100',
            'cantidad' => 'required|integer|min:0',
            'estado' => 'required|in:disponible,agotado,dado_baja'
        ]);

        $producto = Inventario::findOrFail($id);
        $producto->update($request->all());
        return response()->json(['success' => true, 'producto' => $producto]);
    }

    public function destroy($id)
    {
        $producto = Inventario::findOrFail($id);
        $producto->delete();
        return response()->json(['success' => true]);
    }

    public function show($id)
    {
        $producto = Inventario::findOrFail($id);
        return response()->json($producto);
    }
}