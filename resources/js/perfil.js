document.addEventListener('DOMContentLoaded', function () {
    // Toggle password visibility
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function () {
            const targetId = this.dataset.target;
            const input = document.getElementById(targetId);
            if (input) {
                const type = input.type === 'password' ? 'text' : 'password';
                input.type = type;
                this.classList.toggle('fa-eye');
                this.classList.toggle('fa-eye-slash');
            }
        });
    });

    // Previsualizar foto antes de subir
    const fotoInput = document.getElementById('foto_input');
    const fotoPerfil = document.getElementById('foto_perfil');
    const fotoPerfilDefault = document.getElementById('foto_perfil_default');

    if (fotoInput) {
        fotoInput.addEventListener('change', function (e) {
            if (e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function (loadEvent) {
                    if (fotoPerfil) {
                        fotoPerfil.src = loadEvent.target.result;
                    } else if (fotoPerfilDefault) {
                        const newImg = document.createElement('img');
                        newImg.src = loadEvent.target.result;
                        newImg.className = 'foto-perfil';
                        newImg.id = 'foto_perfil';
                        newImg.alt = 'Foto';
                        fotoPerfilDefault.parentNode.replaceChild(newImg, fotoPerfilDefault);
                    }
                };
                reader.readAsDataURL(e.target.files[0]);
            }
        });
    }

    // Click en el overlay (cámara) para cambiar foto
    const fotoOverlay = document.getElementById('foto_overlay');
    if (fotoOverlay) {
        fotoOverlay.addEventListener('click', function (e) {
            e.stopPropagation();
            if (fotoInput) fotoInput.click();
        });
    }

    // Función para abrir modal con foto
    function abrirModalFoto(src) {
        const modal = document.createElement('div');
        modal.style.position = 'fixed';
        modal.style.top = '0';
        modal.style.left = '0';
        modal.style.width = '100%';
        modal.style.height = '100%';
        modal.style.backgroundColor = 'rgba(0,0,0,0.9)';
        modal.style.zIndex = '9999';
        modal.style.display = 'flex';
        modal.style.alignItems = 'center';
        modal.style.justifyContent = 'center';
        modal.style.flexDirection = 'column';

        const closeBtn = document.createElement('div');
        closeBtn.innerHTML = '<i class="fas fa-times"></i>';
        closeBtn.style.position = 'absolute';
        closeBtn.style.top = '20px';
        closeBtn.style.right = '30px';
        closeBtn.style.fontSize = '30px';
        closeBtn.style.color = 'white';
        closeBtn.style.cursor = 'pointer';
        closeBtn.style.zIndex = '10000';
        closeBtn.style.backgroundColor = 'rgba(0,0,0,0.5)';
        closeBtn.style.width = '50px';
        closeBtn.style.height = '50px';
        closeBtn.style.borderRadius = '50%';
        closeBtn.style.display = 'flex';
        closeBtn.style.alignItems = 'center';
        closeBtn.style.justifyContent = 'center';

        closeBtn.addEventListener('mouseenter', () => {
            closeBtn.style.backgroundColor = 'rgba(255,255,255,0.2)';
        });
        closeBtn.addEventListener('mouseleave', () => {
            closeBtn.style.backgroundColor = 'rgba(0,0,0,0.5)';
        });
        closeBtn.addEventListener('click', () => modal.remove());

        const img = document.createElement('img');
        img.src = src;
        img.style.maxWidth = '90%';
        img.style.maxHeight = '90%';
        img.style.borderRadius = '10px';

        modal.appendChild(closeBtn);
        modal.appendChild(img);
        modal.addEventListener('click', (e) => {
            if (e.target === modal) modal.remove();
        });
        document.body.appendChild(modal);
    }

    // Click en la foto para VERLA en grande
    const fotoParaVer = document.getElementById('foto_perfil');
    if (fotoParaVer) {
        fotoParaVer.addEventListener('click', function (e) {
            const src = this.src;
            if (src) {
                abrirModalFoto(src);
            }
        });
    }

    // Click en el div default (sin foto) - mostrar mensaje
    const fotoDefaultVer = document.getElementById('foto_perfil_default');
    if (fotoDefaultVer) {
        fotoDefaultVer.addEventListener('click', function () {
            showNotification('No hay foto de perfil para ver', 'info');
        });
    }

    // FORZAR ACTUALIZACIÓN DE LA FOTO DESPUÉS DE GUARDAR
    const perfilForm = document.getElementById('perfilForm');
    if (perfilForm) {
        perfilForm.addEventListener('submit', function () {
            setTimeout(function () {
                location.reload();
            }, 500);
        });
    }
});