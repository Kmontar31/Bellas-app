<?php

namespace App\Http\Controllers;

use App\Models\Horario;
use App\Models\Profesional;
use App\Models\Bloqueo;
use Illuminate\Http\Request;

class HorariosController extends Controller
{
    public function index()
    {
        $horarios = Horario::with('profesional')->get();
        return view('admin.horarios.index', compact('horarios'));
    }

    public function calendar()
    {
        $horarios = Horario::with('profesional')->get();
        $profesionales = Profesional::all();
        $bloqueos = Bloqueo::with('profesional')
            ->orderBy('fecha', 'desc')
            ->get();
        return view('admin.horarios.calendar', compact('horarios', 'profesionales', 'bloqueos'));
    }

    public function create()
    {
        $profesionales = Profesional::all();
        return view('admin.horarios.create', compact('profesionales'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'profesional_id' => 'required|exists:profesionales,id',
            'dia_semana' => 'required|integer|between:0,6',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio'
        ]);

        Horario::create($request->all());
        return redirect()->route('admin.horarios.index')
            ->with('success', 'Horario creado exitosamente.');
    }

    public function show(Horario $horario)
    {
        $horario->load('profesional');
        return view('admin.horarios.show', compact('horario'));
    }

    public function edit(Horario $horario)
    {
        $profesionales = Profesional::all();
        return view('admin.horarios.edit', compact('horario', 'profesionales'));
    }

    public function update(Request $request, Horario $horario)
    {
        $request->validate([
            'profesional_id' => 'required|exists:profesionales,id',
            'dia_semana' => 'required|integer|between:0,6',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio'
        ]);

        $horario->update($request->all());
        return redirect()->route('admin.horarios.index')
            ->with('success', 'Horario actualizado exitosamente.');
    }

    public function destroy(Horario $horario)
    {
        $horario->delete();
        return redirect()->route('admin.horarios.index')
            ->with('success', 'Horario eliminado exitosamente.');
    }
}