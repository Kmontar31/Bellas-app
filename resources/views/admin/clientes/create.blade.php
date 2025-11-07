@extends('layouts.admin')
@section('content')
<div class="row">
        <div class="col-12">
        <h1>Nuevo Cliente</h1>
        <div class="container mt-5">
     
    <form method="POST" class="" action="{{ route('admin.clientes.store') }}">
    @csrf
    {{-- Campo Nombre --}}
    <div class="row mb-3">
        <label for="nombre" class="col-md-2 col-form-label text-md-end">{{ __('Nombre') }}</label>
        <div class="col-md-6">
            <input id="nombre" type="text" class="form-control @error('nombre') is-invalid @enderror" name="nombre" value="{{ old('Nombre') }}" required autocomplete="nombre" autofocus>

            @error('nombre')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    {{-- Campo Correo Electrónico --}}
    <div class="row mb-3">
        <label for="email" class="col-md-2 col-form-label text-md-end">{{ __('Email') }}</label>
        <div class="col-md-6">
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('Email') }}" required autocomplete="email">

            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    {{-- Campo Telefono--}}
    <div class="row mb-3">
        <label for="telefono" class="col-md-2 col-form-label text-md-end">{{ __('Teléfono') }}</label>
        <div class="col-md-6">
            <input id="telefono" type="text" class="form-control @error('telefono') is-invalid @enderror" name="telefono" required autocomplete="telefono" autofocus>
            @error('telefono')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

 {{-- Campo Dirrecion--}}
    <div class="row mb-3">
        <label for="direccion" class="col-md-2 col-form-label text-md-end">{{ __('Dirección') }}</label>
        <div class="col-md-6">
            <input id="direccion" type="text" class="form-control @error('direccion') is-invalid @enderror" name="direccion" required autocomplete="direccion" autofocus>
            @error('direccion')
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
                {{ __('Guardar') }}
            </button>
            <a href="{{ route('admin.clientes.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </div>
</form>
</div>
   </div>
</div>

@endsection