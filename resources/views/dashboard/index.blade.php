@extends('layouts.dashboard')

@section('title', 'Dashboard')

@section('content')
<div>
    <h1 style="color: var(--azul-marino); margin-bottom: 30px;">Bienvenido, {{ session('usuario')->nombre ?? 'Usuario' }}</h1>
    <p style="color: var(--gris-oscuro);">Aquí irán las tarjetas con estadísticas del sistema.</p>
</div>
@endsection