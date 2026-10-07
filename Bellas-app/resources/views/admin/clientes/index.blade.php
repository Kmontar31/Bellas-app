@extends('layouts.admin')
@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1>
                    <i class="fas fa-users"></i> Clientes
                </h1>
                <a href="{{ route('admin.clientes.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle"></i> Nuevo Cliente
                </a>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <form action="{{ route('admin.clientes.index') }}" method="GET" class="card shadow-sm">
                <div class="card-body">
                    <div class="input-group">
                        <input 
                            type="text" 
                            name="search" 
                            class="form-control" 
                            placeholder="Buscar por nombre, email, teléfono..." 
                            value="{{ $searchTerm ?? '' }}" 
                        >
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                        @if ($searchTerm)
                            <a href="{{ route('admin.clientes.index') }}" class="btn btn-secondary">
                                <i class="fas fa-redo"></i> Limpiar
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if($clientes->isEmpty())
        <div class="alert alert-warning" role="alert">
            <i class="fas fa-exclamation-triangle"></i>
            <strong>Sin clientes registrados</strong> - Aún no hay clientes en el sistema.
            <a href="{{ route('admin.clientes.create') }}" class="alert-link">Crea el primer cliente aquí</a>.
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
                            <strong>Nombre</strong>
                        </th>
                        <th>
                            <strong>Email</strong>
                        </th>
                        <th>
                            <strong>Teléfono</strong>
                        </th>
                        <th>
                            <strong>Dirección</strong>
                        </th>
                        <th class="text-end" style="width: 200px;">
                            <strong>Acciones</strong>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($clientes as $cliente)
                        <tr>
                            <td class="text-center">
                                <i class="fas fa-user-circle text-primary"></i>
                            </td>
                            <td>
                                <strong>{{ $cliente->nombre }}</strong>
                            </td>
                            <td>
                                {{ $cliente->email }}
                            </td>
                            <td>
                                {{ $cliente->telefono ?? '-' }}
                            </td>
                            <td>
                                {{ $cliente->direccion ?? '-' }}
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.clientes.edit', $cliente->id) }}" class="btn btn-sm btn-outline-warning" title="Editar">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <form action="{{ route('admin.clientes.destroy', $cliente->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar" onclick="return confirm('¿Eliminar este cliente? Esta acción no puede deshacerse.')">
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
            {{ $clientes->appends(['search' => $searchTerm])->links('pagination::bootstrap-5') }}
        </div>
    @endif

    <div class="row mt-5">
        <div class="col-12">
            <div class="card bg-light">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="fas fa-lightbulb"></i> <strong>Gestión de Clientes</strong>
                    </h6>
                    <ul class="mb-0">
                        <li>Registra nuevos clientes con su información de contacto.</li>
                        <li>Actualiza los datos de clientes existentes cuando sea necesario.</li>
                        <li>Visualiza el historial de servicios y citas de cada cliente.</li>
                        <li>Elimina clientes que ya no sean activos en tu negocio.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection