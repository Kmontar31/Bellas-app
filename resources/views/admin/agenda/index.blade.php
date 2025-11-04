@extends('layouts.admin')
@section('content')
<div class="row">
        <div class="col-12">
                <h1>Agenda</h1>
                <a href="{{ route('admin.agenda.create') }}" class="btn btn-primary mb-3">Nueva Cita</a>
                <div class="table-responsive">
                        <table class="table table-striped table-hover">
                                <thead>
                                        <tr>
                                                <th>Cliente</th>
                                                <th>Profesional</th>
                                                <th>Servicio</th>
                                                <th>Fecha / Hora</th>
                                                <th class="text-end">Acciones</th>
                                        </tr>
                                </thead>
                                <tbody>
                                        @forelse($citas as $cita)
                                                <tr>
                                                        <td>{{ optional($cita->cliente)->nombre ?? '-' }}</td>
                                                        <td>{{ optional($cita->profesional)->nombre ?? '-' }}</td>
                                                        <td>{{ optional($cita->servicio)->nombre ?? '-' }}</td>
                                                        <td>{{ $cita->fecha_hora ?? ($cita->fecha . ' ' . $cita->hora ?? '-') }}</td>
                                                        <td class="text-end">
                                                                <a href="{{ route('admin.agenda.edit', $cita->id) }}" class="btn btn-sm btn-outline-secondary">Editar</a>
                                                                <form action="{{ route('admin.agenda.destroy', $cita->id) }}" method="POST" style="display:inline-block;">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Eliminar cita?')">Eliminar</button>
                                                                </form>
                                                        </td>
                                                </tr>
                                        @empty
                                                <tr>
                                                        <td colspan="5">No hay citas en la agenda.</td>
                                                </tr>
                                        @endforelse
                                </tbody>
                        </table>
                </div>
        </div>
</div>
@endsection