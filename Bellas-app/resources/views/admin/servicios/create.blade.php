@extends('layouts.admin')
@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-spa"></i> Nuevo Servicio
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.servicios.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="nombre" class="form-label">
                                <strong>Nombre del Servicio</strong>
                            </label>
                            <input 
                                type="text" 
                                id="nombre" 
                                name="nombre" 
                                class="form-control @error('nombre') is-invalid @enderror" 
                                value="{{ old('nombre') }}"
                                placeholder="Ej: Extensión de Pestañas"
                                required
                            >
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="descripcion" class="form-label">
                                <strong>Descripción</strong>
                            </label>
                            <textarea 
                                id="descripcion" 
                                name="descripcion" 
                                class="form-control @error('descripcion') is-invalid @enderror" 
                                placeholder="Describe el servicio detalladamente..."
                                rows="3"
                            >{{ old('descripcion') }}</textarea>
                            @error('descripcion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="duracion_minutos" class="form-label">
                                        <strong>Duración (minutos)</strong>
                                    </label>
                                    <input 
                                        type="number" 
                                        id="duracion_minutos" 
                                        name="duracion_minutos" 
                                        class="form-control @error('duracion_minutos') is-invalid @enderror" 
                                        value="{{ old('duracion_minutos') }}"
                                        placeholder="60"
                                        required
                                    >
                                    @error('duracion_minutos')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="precio" class="form-label">
                                        <strong>Precio ($)</strong>
                                    </label>
                                    <input 
                                        type="number" 
                                        id="precio" 
                                        name="precio" 
                                        class="form-control @error('precio') is-invalid @enderror" 
                                        value="{{ old('precio') }}"
                                        placeholder="0.00"
                                        step="0.01"
                                        required
                                    >
                                    @error('precio')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="categoria_id" class="form-label">
                                <strong>Categoría</strong>
                            </label>
                            <select 
                                id="categoria_id" 
                                name="categoria_id" 
                                class="form-select @error('categoria_id') is-invalid @enderror" 
                                required
                            >
                                <option value="">-- Seleccionar Categoría --</option>
                                @foreach(\App\Models\Categoria::all() as $categoria)
                                    <option value="{{ $categoria->id }}" @selected(old('categoria_id') == $categoria->id)>
                                        {{ $categoria->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('categoria_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Guardar Servicio
                            </button>
                            <a href="{{ route('admin.servicios.index') }}" class="btn btn-secondary">
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