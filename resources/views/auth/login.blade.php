@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="login-card">
    <div class="logo">
        <img src="{{ asset('images/logo.png') }}" alt="Logo FUSALMO">
    </div>

    <h2>Bienvenido</h2>

    @if($errors->any())
        <div class="error-message">
            <i class="fas fa-exclamation-circle"></i> {{ $errors->first('correo') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="input-group">
            <label><i class="fas fa-envelope"></i> Correo Electrónico</label>
            <input type="email" name="correo" placeholder="ejemplo@fuslamo.com" value="{{ old('correo') }}" required autofocus>
        </div>

        <div class="input-group">
            <label><i class="fas fa-lock"></i> Contraseña</label>
            <div class="password-wrapper">
                <input type="password" name="contrasena" id="password" placeholder="Ingresa tu contraseña" required>
                <i class="fa-regular fa-eye toggle-password" id="toggleIcon"></i>
            </div>
        </div>

        <button type="submit" class="btn-login">
            <i class="fas fa-sign-in-alt"></i> Ingresar
        </button>

        <div class="help-link">
            <a href="#">
                <i class="fas fa-headset"></i> ¿Problemas para ingresar? Comuníquese con administración
            </a>
        </div>
    </form>
</div>

<script>
    const toggleIcon = document.getElementById('toggleIcon');
    const password = document.getElementById('password');

    toggleIcon.addEventListener('click', function() {
        const type = password.type === 'password' ? 'text' : 'password';
        password.type = type;
        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
    });
</script>
@endsection