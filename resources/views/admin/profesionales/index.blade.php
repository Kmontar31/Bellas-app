@extends('layouts.admin')
@section('content')
<div class="row">
    <div class="col-12">
        <h1>Profesionales</h1>
        <a href="{{ route('admin.profesionales.create') }}" class="btn btn-primary mb-3">Nuevo Profesional</a>
        <div class="table-responsive">
            <a href="{{'admin/profesionales/create'}}" class="btn btn primary">
                </a>
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Especialidad</th>
                        <th>Teléfono</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($profesionales as $profesional)
                        <tr>
                            <td>{{ $profesional->nombre }}</td>
                            <td>{{ $profesional->especialidad ?? '-' }}</td>
                            <td>{{ $profesional->telefono ?? '-' }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.profesionales.edit', $profesional->id) }}" class="btn btn-sm btn-outline-secondary">Editar</a>
                                <form action="{{ route('admin.profesionales.destroy', $profesional->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Eliminar profesional?')">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">No hay profesionales registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection