@extends('layouts.dashboard')

@section('title', 'Mi Perfil')

@section('styles')
<style>
    .perfil-container {
        max-width: 600px;
        margin: 0 auto;
    }

    .perfil-card {
        background: white;
        border-radius: 16px;
        padding: 30px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .perfil-header {
        text-align: center;
        margin-bottom: 30px;
    }

    .foto-perfil {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        margin-bottom: 15px;
        border: 3px solid var(--azul-marino);
    }

    .perfil-header h2 {
        color: var(--azul-marino);
        margin-bottom: 5px;
    }

    .perfil-header p {
        color: var(--gris-medio);
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: var(--gris-oscuro);
    }

    .form-group input {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 14px;
    }

    .form-group input:focus {
        outline: none;
        border-color: var(--azul-marino);
    }

    .form-row {
        display: flex;
        gap: 15px;
    }

    .form-row .form-group {
        flex: 1;
    }

    .btn-guardar {
        background: var(--azul-marino);
        color: white;
        padding: 12px 25px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 14px;
        font-weight: bold;
        width: 100%;
    }

    .btn-guardar:hover {
        background: var(--azul-marino-claro);
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 20px;
        text-align: center;
    }

    .foto-actual {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: 10px;
    }

    .foto-actual img {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
    }

    body.dark-mode .perfil-card {
        background: #1a1a2e;
    }

    body.dark-mode .form-group label {
        color: rgba(255, 255, 255, 0.8);
    }

    body.dark-mode .form-group input {
        background: #2d2d3f;
        border-color: rgba(255, 255, 255, 0.1);
        color: white;
    }
</style>
@endsection

@section('content')
<div class="perfil-container">
    <div class="perfil-card">
        <div class="perfil-header">
            @if($usuario->foto)
            <img src="{{ asset($usuario->foto) }}" class="foto-perfil" alt="Foto">
            @else
            <i class="fas fa-user-circle" style="font-size: 80px; color: var(--azul-marino);"></i>
            @endif
            <h2>Mi Perfil</h2>
            <p>Gestiona tu información personal</p>
        </div>

        @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
        @endif

        <form method="POST" action="{{ route('perfil.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Foto de Perfil</label>
                <input type="file" name="foto" accept="image/jpeg,image/png,image/jpg">
                @if($usuario->foto)
                <div class="foto-actual">
                    <img src="{{ asset($usuario->foto) }}" alt="Foto actual">
                    <small>Foto actual</small>
                </div>
                @endif
                <small style="font-size: 11px; color: #6c7a8a;">JPG, PNG (max 1MB)</small>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Nombre *</label>
                    <input type="text" name="nombre" value="{{ old('nombre', $usuario->nombre) }}" required>
                    @error('nombre') <span style="color: red; font-size: 12px;">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label>Apellido *</label>
                    <input type="text" name="apellido" value="{{ old('apellido', $usuario->apellido) }}" required>
                    @error('apellido') <span style="color: red; font-size: 12px;">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-group">
                <label>Correo Electrónico *</label>
                <input type="email" name="correo" value="{{ old('correo', $usuario->correo) }}" required>
                @error('correo') <span style="color: red; font-size: 12px;">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label>Nombre de Usuario *</label>
                <input type="text" name="usuario" value="{{ old('usuario', $usuario->usuario) }}" required>
                @error('usuario') <span style="color: red; font-size: 12px;">{{ $message }}</span> @enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Nueva Contraseña</label>
                    <input type="password" name="password" placeholder="Dejar en blanco para no cambiar">
                </div>
                <div class="form-group">
                    <label>Confirmar Contraseña</label>
                    <input type="password" name="password_confirmation" placeholder="Repite la nueva contraseña">
                </div>
            </div>
            @error('password') <span style="color: red; font-size: 12px;">{{ $message }}</span> @enderror

            <button type="submit" class="btn-guardar">
                <i class="fas fa-save"></i> Guardar Cambios
            </button>
        </form>
    </div>
</div>
@endsection