@extends('layouts.admin')
@section('content')
<div class="row">
    <div class="col-12">
        <h1>Clientes</h1>
        <a href="{{ route('admin.clientes.create') }}" class="btn btn-primary mb-3">Nuevo Cliente</a>
        <div class="table-responsive">
                <a href="{{'admin/usuarios/create'}}" class="btn btn primary">
                </a>
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Dirección</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clientes as $cliente)
                        <tr>
                            <td>{{ $cliente->nombre }}</td>
                            <td>{{ $cliente->email }}</td>
                            <td>{{ $cliente->telefono ?? '-' }}</td>
                            <td>{{ $cliente->direccion ?? '-' }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.clientes.edit', $cliente->id) }}" class="btn btn-sm btn-outline-secondary">Editar</a>
                                <form action="{{ route('admin.clientes.destroy', $cliente->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Eliminar cliente?')">Eliminar</button>
                                </form>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">No hay clientes registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection