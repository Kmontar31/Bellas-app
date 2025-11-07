@extends('layouts.admin')
@section('content')
<div class="row">
        <div class="col-12">
        <h1>Nuevo Servicio</h1>
        <div class="container mt-5">
    
    <form method="POST" class="" action="{{ route('admin.servicios.store') }}">
    @csrf
    {{-- Campo Nombre --}}
    <div class="row mb-3">
        <label for="nombre" class="col-md-2 col-form-label text-md-end">{{('Nombre') }}</label>
        <div class="col-md-6">
            <input id="nombre" type="text" class="form-control @error('nombre') is-invalid @enderror" name="nombre" value="{{ old('nombre') }}" required autocomplete="nombre" autofocus>
            @error('nombre')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
    {{-- Duracion_minutos--}}
    <div class="row mb-3">
        <label for="duracion_minutos" class="col-md-2 col-form-label text-md-end">{{('Duracion Minutos') }}</label>
        <div class="col-md-6">
            <input id="duracion_minutos" type="numeric" class="form-control @error('duracion_minutos') is-invalid @enderror" name="duracion_minutos" required autocomplete="duracion_minutos" autofocus>
            @error('duracion_minutos')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
    {{-- precio--}}
    <div class="row mb-3">
        <label for="precio" class="col-md-2 col-form-label text-md-end">{{('Precio') }}</label>
        <div class="col-md-6">
            <input id="precio" type="numeric" class="form-control @error('precio') is-invalid @enderror" name="precio" required autocomplete="precio" autofocus>
            @error('precio')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
       {{--categoria--}}
    <div class="row mb-3">
        <label for="categoria" class="col-md-2 col-form-label text-md-end">{{('Categoria') }}</label>
        <div class="col-md-6">
            <select id="categoria" class="form-control @error('precio') is-invalid @enderror" name="categoria" required autocomplete="categoria" autofocus>
                <option value="">Seleccione una Opción</option>
                <option value="1.Pestañas">1.Pestañas</option>
                <option value="2.Cejas">2.Cejas</option>
                <option value="3.Estetica Facial">3.Estética Facial</option>
                <option value="4.Uñas">4.Uñas</option>
            </select>
            @error('categoria')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
    {{-- Botón de Registro --}}
    <div class="row mb-0">
        <div class="col-md-6 offset-md-4">
            <button type="submit" class="btn btn-primary">
                {{('Guardar') }}
            </button>
            <a href="{{ route('admin.servicios.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </div>
</form>
</div>
</div>
</div>

@endsection