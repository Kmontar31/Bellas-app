@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Reservar turno</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
n            </ul>
        </div>
    @endif

    <form action="{{ route('booking.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" name="nombre" id="nombre" class="form-control" value="{{ old('nombre') }}" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required>
        </div>

        <div class="mb-3">
            <label for="telefono" class="form-label">Teléfono</label>
            <input type="text" name="telefono" id="telefono" class="form-control" value="{{ old('telefono') }}">
        </div>

        <div class="mb-3">
            <label for="profesional_id" class="form-label">Profesional</label>
            <select name="profesional_id" id="profesional_id" class="form-control" required>
                <option value="">-- Seleccione --</option>
                @foreach($profesionales as $p)
                    <option value="{{ $p->id }}" {{ old('profesional_id') == $p->id ? 'selected' : '' }}>{{ $p->nombre }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="servicio_id" class="form-label">Servicio</label>
            <select name="servicio_id" id="servicio_id" class="form-control" required>
                <option value="">-- Seleccione --</option>
                @foreach($servicios as $s)
                    <option value="{{ $s->id }}" data-duracion="{{ $s->duracion_minutos ?? 30 }}" {{ old('servicio_id') == $s->id ? 'selected' : '' }}>{{ $s->nombre }} @if($s->duracion_minutos) ({{ $s->duracion_minutos }} min)@endif</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="fecha" class="form-label">Fecha</label>
            <input type="date" name="fecha" id="fecha" class="form-control" value="{{ old('fecha') }}" required>
        </div>

        <div class="mb-3">
            <label for="hora_inicio" class="form-label">Hora (HH:MM)</label>
            <input type="time" name="hora_inicio" id="hora_inicio" class="form-control" value="{{ old('hora_inicio') }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Reservar</button>
    </form>
</div>
@endsection
