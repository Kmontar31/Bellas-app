@extends('layouts.admin')
@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-plus-circle"></i> Nuevo Horario de Disponibilidad
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.horarios.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="profesional_id" class="form-label">
                                <strong>Profesional</strong>
                            </label>
                            <select 
                                id="profesional_id" 
                                name="profesional_id" 
                                class="form-select @error('profesional_id') is-invalid @enderror" 
                                required
                            >
                                <option value="">-- Seleccionar profesional --</option>
                                @foreach($profesionales as $prof)
                                    <option value="{{ $prof->id }}" @selected(old('profesional_id') == $prof->id)>
                                        {{ $prof->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('profesional_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="dia_semana" class="form-label">
                                <strong>Día de la Semana</strong>
                            </label>
                            <select 
                                id="dia_semana" 
                                name="dia_semana" 
                                class="form-select @error('dia_semana') is-invalid @enderror" 
                                required
                            >
                                <option value="">-- Seleccionar día --</option>
                                <option value="0" @selected(old('dia_semana') == 0)>Domingo</option>
                                <option value="1" @selected(old('dia_semana') == 1)>Lunes</option>
                                <option value="2" @selected(old('dia_semana') == 2)>Martes</option>
                                <option value="3" @selected(old('dia_semana') == 3)>Miércoles</option>
                                <option value="4" @selected(old('dia_semana') == 4)>Jueves</option>
                                <option value="5" @selected(old('dia_semana') == 5)>Viernes</option>
                                <option value="6" @selected(old('dia_semana') == 6)>Sábado</option>
                            </select>
                            @error('dia_semana')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="hora_inicio" class="form-label">
                                        <strong>Hora de Inicio</strong>
                                    </label>
                                    <input 
                                        type="time" 
                                        id="hora_inicio" 
                                        name="hora_inicio" 
                                        class="form-control @error('hora_inicio') is-invalid @enderror" 
                                        value="{{ old('hora_inicio') }}"
                                        required
                                    >
                                    @error('hora_inicio')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="hora_fin" class="form-label">
                                        <strong>Hora de Fin</strong>
                                    </label>
                                    <input 
                                        type="time" 
                                        id="hora_fin" 
                                        name="hora_fin" 
                                        class="form-control @error('hora_fin') is-invalid @enderror" 
                                        value="{{ old('hora_fin') }}"
                                        required
                                    >
                                    @error('hora_fin')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info" role="alert">
                            <i class="fas fa-info-circle"></i> <strong>Nota:</strong> Este horario define los tiempos en que este profesional está disponible. 
                            Los clientes solo podrán agendar citas dentro de estos horarios.
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Guardar Horario
                            </button>
                            <a href="{{ route('admin.horarios.index') }}" class="btn btn-secondary">
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
