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

// Agregar estilos completos del toast
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