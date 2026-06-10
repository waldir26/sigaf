document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('modalUsuario');
    const modalEliminar = document.getElementById('modalEliminar');
    const form = document.getElementById('formUsuario');
    const btnNuevo = document.getElementById('btnNuevo');
    const btnGuardar = document.getElementById('btnGuardarUsuario');
    const cerrarModal = document.getElementById('cerrarModal');
    const cancelarModal = document.getElementById('cancelarModal');
    const cancelarEliminar = document.getElementById('cancelarEliminar');
    const confirmarEliminar = document.getElementById('confirmarEliminar');
    let eliminarId = null;

    // Foto de perfil
    const fotoInputGlobal = document.getElementById('fotoInput');
    const fotoOverlayGlobal = document.getElementById('fotoOverlay');
    const fotoPreview = document.getElementById('fotoPreview');
    const previewIcon = document.getElementById('previewIcon');
    const previewImg = document.getElementById('previewImg');
    const rolSelect = document.getElementById('rol');
    const nombreInput = document.getElementById('nombre');

    // Función para abrir modal con foto en grande
    function abrirModalFoto(src) {
        const modalFoto = document.createElement('div');
        modalFoto.style.position = 'fixed';
        modalFoto.style.top = '0';
        modalFoto.style.left = '0';
        modalFoto.style.width = '100%';
        modalFoto.style.height = '100%';
        modalFoto.style.backgroundColor = 'rgba(0,0,0,0.9)';
        modalFoto.style.zIndex = '9999';
        modalFoto.style.display = 'flex';
        modalFoto.style.alignItems = 'center';
        modalFoto.style.justifyContent = 'center';
        modalFoto.style.flexDirection = 'column';

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
        closeBtn.addEventListener('click', () => modalFoto.remove());

        const img = document.createElement('img');
        img.src = src;
        img.style.maxWidth = '90%';
        img.style.maxHeight = '90%';
        img.style.borderRadius = '10px';
        img.style.boxShadow = '0 0 20px rgba(0,0,0,0.5)';

        modalFoto.appendChild(closeBtn);
        modalFoto.appendChild(img);
        modalFoto.addEventListener('click', (e) => {
            if (e.target === modalFoto) modalFoto.remove();
        });
        document.body.appendChild(modalFoto);
    }

    // Función para reinicializar eventos de la cámara
    function initFotoEventos() {
        const fotoInput = document.getElementById('fotoInput');
        const fotoOverlay = document.getElementById('fotoOverlay');

        if (fotoOverlay) {
            const newFotoOverlay = fotoOverlay.cloneNode(true);
            fotoOverlay.parentNode.replaceChild(newFotoOverlay, fotoOverlay);

            newFotoOverlay.addEventListener('click', function (e) {
                e.stopPropagation();
                const input = document.getElementById('fotoInput');
                if (input) input.click();
            });
        }

        if (fotoInput) {
            const newFotoInput = fotoInput.cloneNode(true);
            fotoInput.parentNode.replaceChild(newFotoInput, fotoInput);

            newFotoInput.addEventListener('change', function (e) {
                if (e.target.files && e.target.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function (loadEvent) {
                        const fotoPreviewImg = document.getElementById('fotoPreviewImg');
                        const fotoPreviewDefault = document.getElementById('fotoPreviewDefault');
                        if (fotoPreviewImg && fotoPreviewDefault) {
                            fotoPreviewImg.src = loadEvent.target.result;
                            fotoPreviewImg.style.display = 'block';
                            fotoPreviewDefault.style.display = 'none';
                        }
                    };
                    reader.readAsDataURL(e.target.files[0]);
                }
            });
        }
    }

    function actualizarAvatarPorDefecto() {
        const rol = rolSelect ? rolSelect.value : 'empleado';
        const nombre = nombreInput ? nombreInput.value : '';

        let color;
        if (rol === 'admin') {
            color = '#dc3545';
        } else {
            color = '#1a2a4f';
        }

        let inicial = 'U';
        if (nombre && nombre.length > 0) {
            inicial = nombre.charAt(0).toUpperCase();
        }

        fotoPreview.style.background = color;
        previewIcon.style.display = 'flex';
        previewIcon.innerHTML = `<span style="font-size: 40px; font-weight: bold;">${inicial}</span>`;
        previewImg.style.display = 'none';
    }

    function actualizarAvatarConFoto(file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            previewIcon.style.display = 'none';
            previewImg.src = e.target.result;
            previewImg.style.display = 'block';
            fotoPreview.style.background = 'transparent';
        };
        reader.readAsDataURL(file);
    }

    if (fotoOverlayGlobal) {
        fotoOverlayGlobal.addEventListener('click', function () {
            if (fotoInputGlobal) fotoInputGlobal.click();
        });
    }

    if (fotoInputGlobal) {
        fotoInputGlobal.addEventListener('change', function (e) {
            if (e.target.files && e.target.files[0]) {
                actualizarAvatarConFoto(e.target.files[0]);
            }
        });
    }

    if (rolSelect) {
        rolSelect.addEventListener('change', actualizarAvatarPorDefecto);
    }
    if (nombreInput) {
        nombreInput.addEventListener('input', actualizarAvatarPorDefecto);
    }

    // Toggle password
    document.querySelectorAll('.toggle-password').forEach(btn => {
        btn.addEventListener('click', function () {
            const target = document.getElementById(this.dataset.target);
            if (target) {
                const type = target.type === 'password' ? 'text' : 'password';
                target.type = type;
                this.classList.toggle('fa-eye');
                this.classList.toggle('fa-eye-slash');
            }
        });
    });

    // Búsqueda
    const btnBuscar = document.getElementById('btnBuscar');
    const btnLimpiar = document.getElementById('btnLimpiar');
    const searchInput = document.getElementById('searchInput');

    function aplicarBusqueda() {
        const search = searchInput.value;
        window.location.href = `/usuarios?search=${encodeURIComponent(search)}`;
    }

    function limpiarBusqueda() {
        window.location.href = '/usuarios';
    }

    if (btnBuscar) btnBuscar.addEventListener('click', aplicarBusqueda);
    if (btnLimpiar) btnLimpiar.addEventListener('click', limpiarBusqueda);
    if (searchInput) {
        searchInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') aplicarBusqueda();
        });
    }

    function cerrarModales() {
        modal.style.display = 'none';
        modalEliminar.style.display = 'none';
    }

    if (btnNuevo) {
        btnNuevo.addEventListener('click', () => {
            document.getElementById('modalTitulo').innerHTML = '<i class="fas fa-plus-circle"></i> Nuevo Usuario';
            form.reset();
            document.getElementById('usuario_id').value = '';
            document.getElementById('estado').value = 'activo';
            document.getElementById('rol').value = 'empleado';
            document.getElementById('contrasena').required = true;

            const passwordGroup = document.getElementById('passwordGroup');
            if (passwordGroup) passwordGroup.style.display = 'block';

            const fotoPreviewElem = document.getElementById('fotoPreview');
            if (fotoPreviewElem) {
                fotoPreviewElem.innerHTML = '<div style="width: 100px; height: 100px; border-radius: 50%; background: #1a2a4f; display: flex; align-items: center; justify-content: center;"><i class="fas fa-user" style="font-size: 40px; color: white;"></i></div>';
            }

            modal.style.display = 'flex';
            initFotoEventos();
        });
    }

    if (cerrarModal) cerrarModal.addEventListener('click', cerrarModales);
    if (cancelarModal) cancelarModal.addEventListener('click', cerrarModales);
    if (cancelarEliminar) cancelarEliminar.addEventListener('click', cerrarModales);

    if (btnGuardar) {
        btnGuardar.addEventListener('click', async function () {
            const id = document.getElementById('usuario_id').value;
            const isEdit = id && id !== '';

            const formData = new FormData();
            formData.append('nombre', document.getElementById('nombre').value);
            formData.append('apellido', document.getElementById('apellido').value);
            formData.append('usuario', document.getElementById('usuario').value);
            formData.append('correo', document.getElementById('correo').value);
            formData.append('rol', document.getElementById('rol').value);
            formData.append('estado', document.getElementById('estado').value);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

            const contrasena = document.getElementById('contrasena').value;
            if (contrasena) {
                formData.append('contrasena', contrasena);
            }

            const foto = document.getElementById('fotoInput').files[0];
            if (foto) {
                formData.append('foto', foto);
            }

            if (isEdit) {
                formData.append('_method', 'PUT');
            }

            let url = isEdit ? `/usuarios/${id}` : '/usuarios';

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData
                });
                const result = await response.json();
                if (result.success) {
                    showNotification(isEdit ? 'Usuario actualizado con éxito' : 'Usuario creado con éxito', 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showNotification(result.message || 'Error al guardar', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Error al guardar', 'error');
            }
        });
    }

    document.querySelectorAll('.btn-editar').forEach(btn => {
        btn.addEventListener('click', async () => {
            const id = btn.dataset.id;
            const response = await fetch(`/usuarios/${id}`);
            const data = await response.json();

            document.getElementById('usuario_id').value = data.id_usuario;
            document.getElementById('nombre').value = data.nombre;
            document.getElementById('apellido').value = data.apellido;
            document.getElementById('usuario').value = data.usuario;
            document.getElementById('correo').value = data.correo;
            document.getElementById('rol').value = data.rol;
            document.getElementById('estado').value = data.estado;
            document.getElementById('contrasena').value = '';
            document.getElementById('contrasena').required = false;

            const fotoPreviewImg = document.getElementById('fotoPreviewImg');
            const fotoPreviewDefault = document.getElementById('fotoPreviewDefault');

            if (fotoPreviewImg && fotoPreviewDefault) {
                if (data.foto && data.foto !== '') {
                    fotoPreviewImg.src = data.foto;
                    fotoPreviewImg.style.display = 'block';
                    fotoPreviewDefault.style.display = 'none';
                    fotoPreviewImg.style.cursor = 'pointer';
                    fotoPreviewImg.onclick = function () {
                        abrirModalFoto(this.src);
                    };
                } else {
                    fotoPreviewImg.style.display = 'none';
                    fotoPreviewDefault.style.display = 'flex';
                    const inicial = data.nombre ? data.nombre.charAt(0).toUpperCase() : 'U';
                    const inicialApellido = data.apellido ? data.apellido.charAt(0).toUpperCase() : '';
                    fotoPreviewDefault.innerHTML = '<span style="color: white; font-size: 32px; font-weight: bold;">' + inicial + inicialApellido + '</span>';
                }
            }

            document.getElementById('modalTitulo').innerHTML = '<i class="fas fa-edit"></i> Editar Usuario';

            const passwordGroup = document.getElementById('passwordGroup');
            if (passwordGroup) {
                const label = passwordGroup.querySelector('label');
                if (label) label.innerHTML = 'Contraseña (dejar en blanco para no cambiar)';
            }

            modal.style.display = 'flex';
            initFotoEventos();
        });
    });

    document.querySelectorAll('.btn-eliminar').forEach(btn => {
        btn.addEventListener('click', () => {
            eliminarId = btn.dataset.id;
            modalEliminar.style.display = 'flex';
        });
    });

    if (confirmarEliminar) {
        confirmarEliminar.addEventListener('click', async () => {
            const response = await fetch(`/usuarios/${eliminarId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            const result = await response.json();
            if (result.success) {
                showNotification('Usuario eliminado con éxito', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showNotification(result.message || 'Error al eliminar', 'error');
            }
        });
    }
});