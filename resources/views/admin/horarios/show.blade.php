@extends('layouts.admin')
@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-eye"></i> Detalles del Horario
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">
                            <strong>Profesional</strong>
                        </label>
                        <p class="form-control-plaintext">
                            {{ optional($horario->profesional)->nombre ?? 'Sin asignar' }}
                        </p>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">
                                    <strong>Día de la Semana</strong>
                                </label>
                                <p class="form-control-plaintext">
                                    @php
                                        $dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
                                        echo $dias[$horario->dia_semana] ?? 'Desconocido';
                                    @endphp
                                </p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">
                                    <strong>Duración</strong>
                                </label>
                                <p class="form-control-plaintext">
                                    @php
                                        $inicio = \Carbon\Carbon::parse('2000-01-01 ' . $horario->hora_inicio);
                                        $fin = \Carbon\Carbon::parse('2000-01-01 ' . $horario->hora_fin);
                                        $duracionMinutos = $fin->diffInMinutes($inicio);
                                        $horas = intval($duracionMinutos / 60);
                                        $minutos = $duracionMinutos % 60;
                                        
                                        if ($horas > 0) {
                                            echo $horas . 'h' . ($minutos > 0 ? ' ' . $minutos . 'm' : '');
                                        } else {
                                            echo $minutos . 'm';
                                        }
                                    @endphp
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">
                                    <strong>Hora de Inicio</strong>
                                </label>
                                <p class="form-control-plaintext">
                                    <code>{{ $horario->hora_inicio }}</code>
                                </p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">
                                    <strong>Hora de Fin</strong>
                                </label>
                                <p class="form-control-plaintext">
                                    <code>{{ $horario->hora_fin }}</code>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.horarios.edit', $horario->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <a href="{{ route('admin.horarios.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
