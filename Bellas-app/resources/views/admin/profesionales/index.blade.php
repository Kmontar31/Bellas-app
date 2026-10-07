@extends('layouts.admin')
@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1>
                    <i class="fas fa-user-tie"></i> Profesionales
                </h1>
                <a href="{{ route('admin.profesionales.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle"></i> Nuevo Profesional
                </a>
            </div>
        </div>
    </div>

    @if($profesionales->isEmpty())
        <div class="alert alert-warning" role="alert">
            <i class="fas fa-exclamation-triangle"></i>
            <strong>Sin profesionales registrados</strong> - Aún no hay profesionales en el sistema.
            <a href="{{ route('admin.profesionales.create') }}" class="alert-link">Crea el primer profesional aquí</a>.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-striped table-hover table-sm">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 50px;">
                            <i class="fas fa-id-card"></i>
                        </th>
                        <th>
                            <strong>Nombre</strong>
                        </th>
                        <th>
                            <strong>Especialidad</strong>
                        </th>
                        <th>
                            <strong>Email</strong>
                        </th>
                        <th>
                            <strong>Teléfono</strong>
                        </th>
                        <th class="text-end" style="width: 200px;">
                            <strong>Acciones</strong>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($profesionales as $profesional)
                        <tr>
                            <td class="text-center">
                                <i class="fas fa-user-tie text-primary"></i>
                            </td>
                            <td>
                                <strong>{{ $profesional->nombre }}</strong>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $profesional->especialidad ?? 'General' }}</span>
                            </td>
                            <td>
                                {{ $profesional->email ?? '-' }}
                            </td>
                            <td>
                                {{ $profesional->telefono ?? '-' }}
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.profesionales.edit', $profesional->id) }}" class="btn btn-sm btn-outline-warning" title="Editar">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <form action="{{ route('admin.profesionales.destroy', $profesional->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar" onclick="return confirm('¿Eliminar este profesional? Esta acción no puede deshacerse.')">
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
                        <i class="fas fa-lightbulb"></i> <strong>Gestión de Profesionales</strong>
                    </h6>
                    <ul class="mb-0">
                        <li>Registra los profesionales que ofrecen servicios en tu negocio.</li>
                        <li>Define la especialidad de cada profesional para mejor organización.</li>
                        <li>Asigna horarios de disponibilidad a través del módulo de Disponibilidad.</li>
                        <li>Elimina profesionales que ya no trabajen en tu negocio.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection