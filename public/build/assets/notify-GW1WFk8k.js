if(!document.querySelector("#toast-styles")){const t=document.createElement("style");t.id="toast-styles",t.textContent=`
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
    `,document.head.appendChild(t)}
