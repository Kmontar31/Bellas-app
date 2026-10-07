<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClientesController extends Controller
{
    public function index(Request $request)
    {
        $searchTerm = $request->input('search');
        $clientesQuery = Cliente::query();
        if ($searchTerm) {
            $clientesQuery->where('nombre', 'LIKE', '%' . $searchTerm . '%')
                ->orWhere('email', 'LIKE', '%' . $searchTerm . '%');
        }
        $clientes = $clientesQuery->paginate(15);
        return view('admin.clientes.index', compact('clientes', 'searchTerm'));
    }

    public function create()
    {
        return view('admin.clientes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:clientes',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string'
        ]);

        Cliente::create([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'direccion' => $request->direccion
        ]);
        return redirect()->route('admin.clientes.index')
            ->with('success', 'Cliente creado exitosamente.');
    }

    public function show(Cliente $cliente)
    {
        return view('admin.clientes.show', compact('cliente'));
    }

    public function edit(Cliente $cliente)
    {
        return view('admin.clientes.edit', compact('cliente'));
    }

    public function update(Request $request, Cliente $cliente)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:clientes,email,'.$cliente->id,
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string'
        ]);

        $cliente->update($request->all());
        return redirect()->route('admin.clientes.index')
            ->with('success', 'Cliente actualizado exitosamente.');
    }

    public function destroy(Cliente $cliente)
    {
        $cliente->delete();
        return redirect()->route('admin.clientes.index')
            ->with('success', 'Cliente eliminado exitosamente.');
    }
}