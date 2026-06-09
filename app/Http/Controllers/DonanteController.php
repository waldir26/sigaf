<?php

namespace App\Http\Controllers;

use App\Models\Donante;
use Illuminate\Http\Request;

class DonanteController extends Controller
{
    public function index(Request $request)
    {
        $query = Donante::query();
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('nombre', 'LIKE', "%{$search}%")
                  ->orWhere('correo', 'LIKE', "%{$search}%")
                  ->orWhere('telefono', 'LIKE', "%{$search}%");
        }
        
        $donantes = $query->orderBy('nombre')->paginate(15);
        return view('donantes.index', compact('donantes'));
    }

    public function store(Request $request)
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

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|max:200',
            'telefono' => 'nullable|max:20',
            'correo' => 'nullable|email|max:150',
            'direccion' => 'nullable'
        ]);

        $donante = Donante::findOrFail($id);
        $donante->update($request->all());
        return response()->json(['success' => true, 'donante' => $donante]);
    }

    public function destroy($id)
    {
        $donante = Donante::findOrFail($id);
        $donante->delete();
        return response()->json(['success' => true]);
    }

    public function show($id)
    {
        $donante = Donante::findOrFail($id);
        return response()->json($donante);
    }
}