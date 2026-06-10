@extends('layouts.dashboard')

@section('title', 'Mi Perfil')

@section('styles')
@vite('resources/css/perfil.css')
@endsection

@section('content')
<div class="perfil-container">
    <div class="perfil-card">
        <div class="perfil-header">
            <div class="foto-container">
                @if($usuario->foto)
                <img src="{{ asset($usuario->foto) }}" class="foto-perfil" id="foto_perfil" alt="Foto">
                @else
                <div class="foto-perfil-default" id="foto_perfil_default">
                    <i class="fas fa-user"></i>
                </div>
                @endif
                <div class="foto-overlay" id="foto_overlay">
                    <i class="fas fa-camera"></i>
                </div>
            </div>
            <h2>Mi Perfil</h2>
            <p>Gestiona tu información personal</p>
        </div>

        @if(session('success'))
        <div class="alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
        @endif

        <form method="POST" action="{{ route('perfil.update') }}" enctype="multipart/form-data" id="perfilForm">
            @csrf
            @method('PUT')

            <input type="file" name="foto" id="foto_input" class="foto-input" accept="image/jpeg,image/png,image/jpg">

            <div class="form-row">
                <div class="form-group">
                    <label><i class="fas fa-user"></i> Nombre *</label>
                    <input type="text" name="nombre" value="{{ old('nombre', $usuario->nombre) }}" required>
                    @error('nombre') <span style="color: red; font-size: 12px;">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label><i class="fas fa-user-tag"></i> Apellido *</label>
                    <input type="text" name="apellido" value="{{ old('apellido', $usuario->apellido) }}" required>
                    @error('apellido') <span style="color: red; font-size: 12px;">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-group">
                <label><i class="fas fa-envelope"></i> Correo Electrónico *</label>
                <input type="email" name="correo" value="{{ old('correo', $usuario->correo) }}" required>
                @error('correo') <span style="color: red; font-size: 12px;">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label><i class="fas fa-user-circle"></i> Nombre de Usuario *</label>
                <input type="text" name="usuario" value="{{ old('usuario', $usuario->usuario) }}" required>
                @error('usuario') <span style="color: red; font-size: 12px;">{{ $message }}</span> @enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label><i class="fas fa-lock"></i> Nueva Contraseña</label>
                    <div class="password-wrapper">
                        <input type="password" name="password" id="password" placeholder="Dejar en blanco para no cambiar">
                        <i class="fas fa-eye toggle-password" data-target="password"></i>
                    </div>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-lock"></i> Confirmar Contraseña</label>
                    <div class="password-wrapper">
                        <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Repite la nueva contraseña">
                        <i class="fas fa-eye toggle-password" data-target="password_confirmation"></i>
                    </div>
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

@section('scripts')
@vite('resources/js/perfil.js')
@endsection