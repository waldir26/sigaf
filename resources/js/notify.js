function showNotification(message, type = 'info') {
    const existingToast = document.querySelector('.toast-notification');
    if (existingToast) {
        existingToast.remove();
    }
    
    const toast = document.createElement('div');
    toast.className = `toast-notification ${type}`;
    
    let icon = '';
    switch(type) {
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

// Agregar animación de salida
const style = document.createElement('style');
style.textContent = `
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);