document.addEventListener('DOMContentLoaded', function () {
    const themeToggle = document.getElementById('theme-toggle');

    // Verificar modo guardado o iniciar en claro
    const savedMode = localStorage.getItem('theme-mode');
    if (savedMode === 'dark') {
        document.body.classList.add('dark-mode');
        updateThemeIcon('dark');
    } else {
        document.body.classList.remove('dark-mode');
        updateThemeIcon('light');
    }

    if (themeToggle) {
        themeToggle.addEventListener('click', function () {
            document.body.classList.toggle('dark-mode');
            const isDark = document.body.classList.contains('dark-mode');
            localStorage.setItem('theme-mode', isDark ? 'dark' : 'light');
            updateThemeIcon(isDark ? 'dark' : 'light');
        });
    }

    function updateThemeIcon(mode) {
        if (themeToggle) {
            const icon = themeToggle.querySelector('i');
            if (mode === 'dark') {
                icon.classList.remove('fa-moon');
                icon.classList.add('fa-sun');
            } else {
                icon.classList.remove('fa-sun');
                icon.classList.add('fa-moon');
            }
        }
    }

    updateDateTime();
    setInterval(updateDateTime, 1000);
});

function updateDateTime() {
    const now = new Date();
    const options = {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    };
    const dateTimeStr = now.toLocaleDateString('es', options);
    const datetimeElement = document.getElementById('current-datetime');
    if (datetimeElement) {
        datetimeElement.textContent = dateTimeStr;
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const menuToggle = document.getElementById('menuToggle');
    const sidebar = document.getElementById('sidebar');
    const sidebarClose = document.getElementById('sidebarClose');

    // Abrir sidebar
    if (menuToggle) {
        menuToggle.addEventListener('click', function () {
            sidebar.classList.toggle('open');
        });
    }

    // Cerrar sidebar con botón X
    if (sidebarClose) {
        sidebarClose.addEventListener('click', function () {
            sidebar.classList.remove('open');
        });
    }

    // Cerrar sidebar al hacer clic fuera de él (en móviles)
    document.addEventListener('click', function (event) {
        if (window.innerWidth <= 768 && sidebar) {
            const isClickInsideSidebar = sidebar.contains(event.target);
            const isClickOnToggle = menuToggle && menuToggle.contains(event.target);
            const isClickOnClose = sidebarClose && sidebarClose.contains(event.target);

            if (!isClickInsideSidebar && !isClickOnToggle && !isClickOnClose && sidebar.classList.contains('open')) {
                sidebar.classList.remove('open');
            }
        }
    });

    // Cerrar sidebar al redimensionar la ventana
    window.addEventListener('resize', function () {
        if (window.innerWidth > 768 && sidebar) {
            sidebar.classList.remove('open');
        }
    });

    // Cerrar sidebar al hacer clic en un enlace del menú (en móviles)
    document.querySelectorAll('.menu-item').forEach(item => {
        item.addEventListener('click', function () {
            if (window.innerWidth <= 768 && sidebar) {
                sidebar.classList.remove('open');
            }
        });
    });
});