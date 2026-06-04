<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SIGAF - @yield('title')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/css/dashboard.css', 'resources/css/dark-mode.css'])
    @yield('styles')
</head>
<body>
    <div class="dashboard-container">
        <aside class="sidebar">
            <div class="sidebar-logo">
                <img src="{{ asset('images/logo.png') }}" alt="Logo">
            </div>
            
            <nav class="sidebar-menu">
                <a href="{{ route('dashboard') }}" class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('programas.index') }}" class="menu-item">
                    <i class="fas fa-chalkboard"></i>
                    <span>Programas</span>
                </a>
                <a href="{{ route('escuelas.index') }}" class="menu-item">
                    <i class="fas fa-school"></i>
                    <span>Escuelas Beneficiarias</span>
                </a>
                <a href="#" class="menu-item">
                    <i class="fas fa-pen-alt"></i>
                    <span>Inscripciones</span>
                </a>
                <a href="#" class="menu-item">
                    <i class="fas fa-users"></i>
                    <span>Participantes</span>
                </a>
                <a href="#" class="menu-item">
                    <i class="fas fa-boxes"></i>
                    <span>Inventario</span>
                </a>
                <a href="#" class="menu-item">
                    <i class="fas fa-hand-holding-usd"></i>
                    <span>Donantes</span>
                </a>
                <a href="#" class="menu-item">
                    <i class="fas fa-gift"></i>
                    <span>Donaciones</span>
                </a>
                <a href="#" class="menu-item">
                    <i class="fas fa-concierge-bell"></i>
                    <span>Servicios y Actividades</span>
                </a>
                <a href="#" class="menu-item">
                    <i class="fas fa-tags"></i>
                    <span>Ventas de Bienes</span>
                </a>
                <a href="#" class="menu-item">
                    <i class="fas fa-money-bill-wave"></i>
                    <span>Gastos</span>
                </a>
                
                <div class="menu-divider"></div>
                
                <div class="menu-section-title">Configuración</div>
                <a href="#" class="menu-item">
                    <i class="fas fa-user-circle"></i>
                    <span>Perfil</span>
                </a>
                <a href="#" class="menu-item">
                    <i class="fas fa-users-cog"></i>
                    <span>Usuarios</span>
                </a>
            </nav>
            
            <div class="sidebar-footer">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Cerrar Sesión</span>
                    </button>
                </form>
            </div>
        </aside>
        
        <main class="main-content">
            <nav class="top-navbar">
                <button class="theme-toggle" id="theme-toggle">
                    <i class="fas fa-moon"></i>
                </button>
                <div class="datetime" id="current-datetime"></div>
                <div class="user-info">
                    <i class="fas fa-user-circle"></i>
                    <span>{{ session('usuario')->nombre ?? 'Usuario' }}</span>
                </div>
            </nav>
            
            <div class="content-page">
                @yield('content')
            </div>
        </main>
    </div>
    
    @vite('resources/js/dashboard.js')
    @yield('scripts')
</body>
</html>