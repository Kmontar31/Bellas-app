@extends('layouts.admin')
@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="fas fa-edit"></i> Editar Cliente
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.clientes.update', $cliente->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="nombre" class="form-label">
                                <strong>Nombre Completo</strong>
                            </label>
                            <input 
                                type="text" 
                                id="nombre" 
                                name="nombre" 
                                class="form-control @error('nombre') is-invalid @enderror" 
                                value="{{ old('nombre', $cliente->nombre) }}"
                                placeholder="Juan Pérez"
                                required
                            >
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">
                                <strong>Correo Electrónico</strong>
                            </label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                class="form-control @error('email') is-invalid @enderror" 
                                value="{{ old('email', $cliente->email) }}"
                                placeholder="juan@example.com"
                                required
                            >
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="telefono" class="form-label">
                                <strong>Teléfono</strong>
                            </label>
                            <input 
                                type="text" 
                                id="telefono" 
                                name="telefono" 
                                class="form-control @error('telefono') is-invalid @enderror" 
                                value="{{ old('telefono', $cliente->telefono) }}"
                                placeholder="+34 123 456 789"
                                required
                            >
                            @error('telefono')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="direccion" class="form-label">
                                <strong>Dirección</strong>
                            </label>
                            <input 
                                type="text" 
                                id="direccion" 
                                name="direccion" 
                                class="form-control @error('direccion') is-invalid @enderror" 
                                value="{{ old('direccion', $cliente->direccion) }}"
                                placeholder="Calle Principal 123, Madrid"
                                required
                            >
                            @error('direccion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-save"></i> Actualizar Cliente
                            </button>
                            <a href="{{ route('admin.clientes.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Volver
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
