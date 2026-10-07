@extends('layouts.admin')
@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1>
                    <i class="fas fa-clock"></i> Disponibilidad de Profesionales
                </h1>
                <div>
                    <a href="{{ route('admin.horarios.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus-circle"></i> Nuevo Horario
                    </a>
                    <a href="{{ route('admin.horarios.calendar') }}" class="btn btn-info ms-2">
                        <i class="fas fa-calendar-alt"></i> Ver Calendario
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if($horarios->isEmpty())
        <div class="alert alert-warning" role="alert">
            <i class="fas fa-exclamation-triangle"></i>
            <strong>Sin horarios definidos</strong> - Aún no hay horarios de disponibilidad registrados. 
            <a href="{{ route('admin.horarios.create') }}" class="alert-link">Crea el primer horario aquí</a>.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-striped table-hover table-sm">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 50px;">
                            <i class="fas fa-user"></i>
                        </th>
                        <th>
                            <strong>Profesional</strong>
                        </th>
                        <th class="text-center">
                            <strong>Día</strong>
                        </th>
                        <th class="text-center">
                            <strong>Hora Inicio</strong>
                        </th>
                        <th class="text-center">
                            <strong>Hora Fin</strong>
                        </th>
                        <th class="text-center">
                            <strong>Duración</strong>
                        </th>
                        <th class="text-end" style="width: 200px;">
                            <strong>Acciones</strong>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($horarios as $horario)
                        @php
                            $dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
                            $diaNombre = $dias[$horario->dia_semana] ?? 'Desconocido';
                            
                            // Calcular duración
                            $inicio = \Carbon\Carbon::parse('2000-01-01 ' . $horario->hora_inicio);
                            $fin = \Carbon\Carbon::parse('2000-01-01 ' . $horario->hora_fin);
                            $duracionMinutos = $fin->diffInMinutes($inicio);
                            $horas = intval($duracionMinutos / 60);
                            $minutos = $duracionMinutos % 60;
                        @endphp
                        <tr>
                            <td class="text-center">
                                <i class="fas fa-user-tie text-primary"></i>
                            </td>
                            <td>
                                <strong>{{ optional($horario->profesional)->nombre ?? 'Sin asignar' }}</strong>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-info">{{ $diaNombre }}</span>
                            </td>
                            <td class="text-center">
                                <code>{{ $horario->hora_inicio }}</code>
                            </td>
                            <td class="text-center">
                                <code>{{ $horario->hora_fin }}</code>
                            </td>
                            <td class="text-center">
                                @if($horas > 0)
                                    {{ $horas }}h{{ $minutos > 0 ? ' ' . $minutos . 'm' : '' }}
                                @else
                                    {{ $minutos }}m
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.horarios.edit', $horario->id) }}" class="btn btn-sm btn-outline-warning" title="Editar">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <form action="{{ route('admin.horarios.destroy', $horario->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button 
                                        type="submit" 
                                        class="btn btn-sm btn-outline-danger" 
                                        title="Eliminar"
                                        onclick="return confirm('¿Eliminar este horario? Esto afectará la disponibilidad del profesional.');"
                                    >
                                        <i class="fas fa-trash"></i> Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <div class="row mt-5">
        <div class="col-12">
            <div class="card bg-light">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="fas fa-lightbulb"></i> <strong>¿Cómo funciona la disponibilidad?</strong>
                    </h6>
                    <ul class="mb-0">
                        <li>Define los horarios en que cada profesional está disponible para atender citas.</li>
                        <li>Los clientes solo podrán agendar citas <strong>dentro</strong> de los horarios definidos.</li>
                        <li>Si un profesional no tiene horarios definidos para un día, no podrá recibir citas ese día.</li>
                        <li>Puedes tener múltiples bloques horarios por día (ej: mañana 09:00-13:00 y tarde 15:00-18:00).</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
