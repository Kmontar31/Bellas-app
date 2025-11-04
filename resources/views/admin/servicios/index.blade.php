@extends('layouts.admin')
@section('content')
<div class="row">
    <div class="col-12">
        <h1>Servicios</h1>
        <a href="{{ route('admin.servicios.create') }}" class="btn btn-primary mb-3">Nuevo Servicio</a>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Duración (min)</th>
                        <th>Precio</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($servicios as $servicio)
                        <tr>
                            <td>{{ $servicio->nombre }}</td>
                            <td>{{ $servicio->descripcion ?? '-' }}</td>
                            <td>{{ $servicio->duracion_minutos ?? '-' }}</td>
                            <td>{{ isset($servicio->precio) ? number_format($servicio->precio, 2) : '-' }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.servicios.edit', $servicio->id) }}" class="btn btn-sm btn-outline-secondary">Editar</a>
                                <form action="{{ route('admin.servicios.destroy', $servicio->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Eliminar servicio?')">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">No hay servicios registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection