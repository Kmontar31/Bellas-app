@extends('layouts.admin')
@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1>
                    <i class="fas fa-spa"></i> Servicios
                </h1>
                <a href="{{ route('admin.servicios.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle"></i> Nuevo Servicio
                </a>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <form action="{{ route('admin.servicios.index') }}" method="GET" class="card shadow-sm">
                <div class="card-body">
                    <div class="input-group">
                        <input 
                            type="text" 
                            name="search" 
                            class="form-control" 
                            placeholder="Buscar por nombre, categoría..." 
                            value="{{ $searchTerm ?? '' }}" 
                        >
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                        @if ($searchTerm)
                            <a href="{{ route('admin.servicios.index') }}" class="btn btn-secondary">
                                <i class="fas fa-redo"></i> Limpiar
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if($servicios->isEmpty())
        <div class="alert alert-warning" role="alert">
            <i class="fas fa-exclamation-triangle"></i>
            <strong>Sin servicios registrados</strong> - Aún no hay servicios en el sistema.
            <a href="{{ route('admin.servicios.create') }}" class="alert-link">Crea el primer servicio aquí</a>.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-striped table-hover table-sm">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 50px;">
                            <i class="fas fa-briefcase"></i>
                        </th>
                        <th>
                            <strong>Nombre</strong>
                        </th>
                        <th class="text-center">
                            <strong>Categoría</strong>
                        </th>
                        <th class="text-center">
                            <strong>Duración</strong>
                        </th>
                        <th class="text-center">
                            <strong>Precio</strong>
                        </th>
                        <th class="text-end" style="width: 200px;">
                            <strong>Acciones</strong>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($servicios as $servicio)
                        <tr>
                            <td class="text-center">
                                <i class="fas fa-spa text-primary"></i>
                            </td>
                            <td>
                                <strong>{{ $servicio->nombre }}</strong>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-success">{{ $servicio->Categoria->nombre ?? '-' }}</span>
                            </td>
                            <td class="text-center">
                                @php
                                    $duracion = $servicio->duracion_minutos ?? 0;
                                    $horas = intval($duracion / 60);
                                    $minutos = $duracion % 60;
                                @endphp
                                @if($horas > 0)
                                    {{ $horas }}h{{ $minutos > 0 ? ' ' . $minutos . 'm' : '' }}
                                @else
                                    {{ $minutos }}m
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="fw-bold text-success">${{ number_format($servicio->precio, 2) }}</span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.servicios.edit', $servicio->id) }}" class="btn btn-sm btn-outline-warning" title="Editar">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <form action="{{ route('admin.servicios.destroy', $servicio->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar" onclick="return confirm('¿Eliminar este servicio? Esta acción no puede deshacerse.')">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="mt-4 d-flex justify-content-center">
            {{ $servicios->appends(['search' => $searchTerm])->links('pagination::bootstrap-5') }}
        </div>
    @endif

    <div class="row mt-5">
        <div class="col-12">
            <div class="card bg-light">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="fas fa-lightbulb"></i> <strong>Gestión de Servicios</strong>
                    </h6>
                    <ul class="mb-0">
                        <li>Registra los servicios que ofreces a tus clientes.</li>
                        <li>Define la duración y precio de cada servicio.</li>
                        <li>Categoriza los servicios para mejor organización.</li>
                        <li>Los clientes verán estos servicios al agendar citas.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection