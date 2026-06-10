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
    const fotoInput = document.getElementById('fotoInput');
    const fotoOverlay = document.getElementById('fotoOverlay');
    const fotoPreview = document.getElementById('fotoPreview');
    const previewIcon = document.getElementById('previewIcon');
    const previewImg = document.getElementById('previewImg');
    const rolSelect = document.getElementById('rol');
    const nombreInput = document.getElementById('nombre');

    function actualizarAvatarPorDefecto() {
        const rol = rolSelect ? rolSelect.value : 'empleado';
        const nombre = nombreInput ? nombreInput.value : '';

        // Determinar color según rol
        let color;
        if (rol === 'admin') {
            color = '#dc3545'; // rojo para admin
        } else {
            color = '#1a2a4f'; // azul para empleado
        }

        // Determinar inicial
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

    if (fotoOverlay) {
        fotoOverlay.addEventListener('click', function () {
            if (fotoInput) fotoInput.click();
        });
    }

    if (fotoInput) {
        fotoInput.addEventListener('change', function (e) {
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

    function abrirModal(titulo, data = null) {
        const modalTitulo = document.getElementById('modalTitulo');
        const passwordGroup = document.getElementById('passwordGroup');

        // Resetear foto
        fotoPreview.style.background = '#1a2a4f';
        previewIcon.style.display = 'flex';
        previewIcon.innerHTML = '<i class="fas fa-user" style="font-size: 50px; color: white;"></i>';
        previewImg.style.display = 'none';
        if (fotoInput) fotoInput.value = '';

        if (titulo === 'crear') {
            modalTitulo.innerHTML = '<i class="fas fa-plus-circle"></i> Nuevo Usuario';
            form.reset();
            document.getElementById('usuario_id').value = '';
            document.getElementById('estado').value = 'activo';
            document.getElementById('rol').value = 'empleado';
            if (passwordGroup) passwordGroup.style.display = 'block';
            document.getElementById('contrasena').required = true;
            const label = passwordGroup.querySelector('label');
            if (label) label.innerHTML = 'Contraseña *';
            actualizarAvatarPorDefecto();
        } else if (data) {
            modalTitulo.innerHTML = '<i class="fas fa-edit"></i> Editar Usuario';
            document.getElementById('usuario_id').value = data.id_usuario;
            document.getElementById('nombre').value = data.nombre;
            document.getElementById('apellido').value = data.apellido;
            document.getElementById('usuario').value = data.usuario;
            document.getElementById('correo').value = data.correo;
            document.getElementById('rol').value = data.rol;
            document.getElementById('estado').value = data.estado;
            document.getElementById('contrasena').value = '';
            document.getElementById('contrasena').required = false;
            if (passwordGroup) {
                const label = passwordGroup.querySelector('label');
                if (label) label.innerHTML = 'Contraseña';
            }
            // Actualizar avatar según rol y nombre
            if (data.rol === 'admin') {
                fotoPreview.style.background = '#dc3545';
            } else {
                fotoPreview.style.background = '#1a2a4f';
            }
            const inicial = data.nombre ? data.nombre.charAt(0).toUpperCase() : 'U';
            previewIcon.innerHTML = `<span style="font-size: 40px; font-weight: bold;">${inicial}</span>`;
            if (data.foto) {
                previewIcon.style.display = 'none';
                previewImg.src = '/storage/' + data.foto;
                previewImg.style.display = 'block';
                fotoPreview.style.background = 'transparent';
            }
        }
        modal.style.display = 'flex';
    }

    function cerrarModales() {
        modal.style.display = 'none';
        modalEliminar.style.display = 'none';
    }

    if (btnNuevo) btnNuevo.addEventListener('click', () => abrirModal('crear'));
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
            abrirModal('editar', data);
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