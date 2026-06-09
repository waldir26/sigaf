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
                <!-- INICIO -->
                <a href="{{ route('dashboard') }}" class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>

                <div class="menu-divider"></div>
                <div class="menu-section-title">Gestión</div>

                <a href="{{ route('programas.index') }}" class="menu-item">
                    <i class="fas fa-chalkboard"></i>
                    <span>Programas</span>
                </a>
                <a href="{{ route('escuelas.index') }}" class="menu-item">
                    <i class="fas fa-school"></i>
                    <span>Escuelas Beneficiarias</span>
                </a>
                <a href="{{ route('inscripciones.index') }}" class="menu-item">
                    <i class="fas fa-pen-alt"></i>
                    <span>Inscripciones</span>
                </a>
                <a href="{{ route('participantes.index') }}" class="menu-item">
                    <i class="fas fa-users"></i>
                    <span>Participantes</span>
                </a>
                <a href="{{ route('inventario.index') }}" class="menu-item">
                    <i class="fas fa-boxes"></i>
                    <span>Inventario</span>
                </a>

                <div class="menu-divider"></div>
                <div class="menu-section-title">Finanzas</div>

                <a href="{{ route('donaciones.index') }}" class="menu-item">
                    <i class="fas fa-hand-holding-heart"></i>
                    <span>Donaciones</span>
                </a>
                <a href="{{ route('servicios.index') }}" class="menu-item">
                    <i class="fas fa-concierge-bell"></i>
                    <span>Servicios y Actividades</span>
                </a>
                <a href="{{ route('ventas.index') }}" class="menu-item">
                    <i class="fas fa-tags"></i>
                    <span>Ventas de Bienes</span>
                </a>
                <a href="{{ route('gastos.index') }}" class="menu-item">
                    <i class="fas fa-money-bill-wave"></i>
                    <span>Gastos</span>
                </a>

                <div class="menu-divider"></div>
                <div class="menu-section-title">Configuración</div>

                <a href="{{ route('perfil.index') }}" class="menu-item">
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
                    @php $usuario = session('usuario'); @endphp
                    @if($usuario && $usuario->foto)
                    <img src="{{ asset($usuario->foto) }}" style="width: 35px; height: 35px; border-radius: 50%; object-fit: cover;">
                    @else
                    <i class="fas fa-user-circle"></i>
                    @endif
                    <span>{{ $usuario->nombre ?? 'Usuario' }}</span>
                </div>
            </nav>

            <div class="content-page">
                @yield('content')
            </div>
        </main>
    </div>

    @vite('resources/js/dashboard.js')
    @yield('scripts')

    <script>
        function showNotification(message, type = 'info') {
            const existingToast = document.querySelector('.toast-notification');
            if (existingToast) existingToast.remove();

            const toast = document.createElement('div');
            toast.className = `toast-notification ${type}`;

            let icon = '';
            switch (type) {
                case 'success':
                    icon = '<i class="fas fa-check-circle" style="font-size: 18px;"></i>';
                    break;
                case 'error':
                    icon = '<i class="fas fa-exclamation-circle" style="font-size: 18px;"></i>';
                    break;
                case 'warning':
                    icon = '<i class="fas fa-exclamation-triangle" style="font-size: 18px;"></i>';
                    break;
                default:
                    icon = '<i class="fas fa-info-circle" style="font-size: 18px;"></i>';
            }

            toast.innerHTML = `
                ${icon}
                <span class="toast-content">${message}</span>
            `;

            document.body.appendChild(toast);

            setTimeout(() => {
                toast.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        if (!document.querySelector('#toast-styles')) {
            const style = document.createElement('style');
            style.id = 'toast-styles';
            style.textContent = `
                .toast-notification {
                    position: fixed;
                    bottom: 20px;
                    right: 20px;
                    background: white;
                    border-radius: 8px;
                    padding: 12px 20px;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                    display: flex;
                    align-items: center;
                    gap: 12px;
                    z-index: 9999;
                    animation: slideIn 0.3s ease;
                }
                .toast-notification.success { border-left: 4px solid #28a745; }
                .toast-notification.success i { color: #28a745; }
                .toast-notification.error { border-left: 4px solid #dc3545; }
                .toast-notification.error i { color: #dc3545; }
                .toast-notification.warning { border-left: 4px solid #ffc107; }
                .toast-notification.warning i { color: #ffc107; }
                .toast-notification.info { border-left: 4px solid #17a2b8; }
                .toast-notification.info i { color: #17a2b8; }
                .toast-notification .toast-content { font-size: 14px; color: #343a40; }
                @keyframes slideIn {
                    from { transform: translateX(100%); opacity: 0; }
                    to { transform: translateX(0); opacity: 1; }
                }
                @keyframes slideOut {
                    from { transform: translateX(0); opacity: 1; }
                    to { transform: translateX(100%); opacity: 0; }
                }
                body.dark-mode .toast-notification { background: #1a1a2e; }
                body.dark-mode .toast-notification .toast-content { color: white; }
            `;
            document.head.appendChild(style);
        }
    </script>
</body>

</html>