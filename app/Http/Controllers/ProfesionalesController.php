<?php

namespace App\Http\Controllers;

use App\Models\Profesional;
use App\Models\Servicio;
use Illuminate\Http\Request;

class ProfesionalesController extends Controller
{
    public function index()
    {
        $profesionales = Profesional::with('servicios')->get();
        return view('admin.profesionales.index', compact('profesionales'));
    }

    public function create()
    {
        $servicios = Servicio::all();
        return view('admin.profesionales.create', compact('servicios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:profesionales',
            'telefono' => 'nullable|string|max:20',
            'especialidad' => 'required|string|max:255',
            'servicios' => 'required|array|exists:servicios,id'
        ]);

        $profesional = Profesional::create($request->except('servicios'));
        $profesional->servicios()->attach($request->servicios);

        return redirect()->route('admin.profesionales.index')
            ->with('success', 'Profesional creado exitosamente.');
    }

    public function show(Profesional $profesional)
    {
        $profesional->load('servicios', 'horarios');
        return view('admin.profesionales.show', compact('profesional'));
    }

    public function edit(Profesional $profesional)
    {
        $servicios = Servicio::all();
        return view('admin.profesionales.edit', compact('profesional', 'servicios'));
    }

    public function update(Request $request, Profesional $profesional)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:profesionales,email,'.$profesional->id,
            'telefono' => 'nullable|string|max:20',
            'especialidad' => 'required|string|max:255',
            'servicios' => 'required|array|exists:servicios,id'
        ]);

        $profesional->update($request->except('servicios'));
        $profesional->servicios()->sync($request->servicios);

        return redirect()->route('admin.profesionales.index')
            ->with('success', 'Profesional actualizado exitosamente.');
    }

    public function destroy(Profesional $profesional)
    {
        $profesional->servicios()->detach();
        $profesional->delete();
        return redirect()->route('admin.profesionales.index')
            ->with('success', 'Profesional eliminado exitosamente.');
    }
}