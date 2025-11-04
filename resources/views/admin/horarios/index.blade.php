@extends('layouts.admin')
@section('content')
<div class="row">
        <div class="col-12">
                <h1>Disponibilidad</h1>
                <div class="mb-3">
                    <a href="{{ route('admin.horarios.create') }}" class="btn btn-primary">Nuevo Horario</a>
                    <a href="{{ route('admin.horarios.calendar') }}" class="btn btn-info ms-2">
                        <i class="fas fa-calendar-alt"></i> Ver Calendario
                    </a>
                </div>
                <div class="table-responsive">
                        <table class="table table-striped table-hover">
                                <thead>
                                        <tr>
                                                <th>Profesional</th>
                                                <th>Día</th>
                                                <th>Hora inicio</th>
                                                <th>Hora fin</th>
                                                <th class="text-end">Acciones</th>
                                        </tr>
                                </thead>
                                <tbody>
                                        @forelse($horarios as $horario)
                                                <tr>
                                                        <td>{{ optional($horario->profesional)->nombre ?? '-' }}</td>
                                                        <td>{{ ['Dom','Lun','Mar','Mié','Jue','Vie','Sáb'][$horario->dia_semana] ?? $horario->dia_semana }}</td>
                                                        <td>{{ $horario->hora_inicio }}</td>
                                                        <td>{{ $horario->hora_fin }}</td>
                                                        <td class="text-end">
                                                                <a href="{{ route('admin.horarios.edit', $horario->id) }}" class="btn btn-sm btn-outline-secondary">Editar</a>
                                                                <form action="{{ route('admin.horarios.destroy', $horario->id) }}" method="POST" style="display:inline-block;">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Eliminar horario?')">Eliminar</button>
                                                                </form>
                                                        </td>
                                                </tr>
                                        @empty
                                                <tr>
                                                        <td colspan="5">No hay horarios definidos.</td>
                                                </tr>
                                        @endforelse
                                </tbody>
                        </table>
                </div>
        </div>
</div>
@endsection
