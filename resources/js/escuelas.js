document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('modalEscuela');
    const modalEliminar = document.getElementById('modalEliminar');
    const form = document.getElementById('formEscuela');
    const btnNuevo = document.getElementById('btnNuevo');
    const btnGuardar = document.getElementById('btnGuardarEscuela');
    const cerrarModal = document.getElementById('cerrarModal');
    const cancelarModal = document.getElementById('cancelarModal');
    const cancelarEliminar = document.getElementById('cancelarEliminar');
    const confirmarEliminar = document.getElementById('confirmarEliminar');
    let eliminarId = null;

    function abrirModal(titulo, data = null) {
        const modalTitulo = document.getElementById('modalTitulo');
        if (titulo === 'crear') {
            modalTitulo.innerHTML = '<i class="fas fa-plus-circle"></i> Nueva Escuela';
            form.reset();
            document.getElementById('escuela_id').value = '';
            document.getElementById('id_programa').value = '';
        } else if (data) {
            modalTitulo.innerHTML = '<i class="fas fa-edit"></i> Editar Escuela';
            document.getElementById('escuela_id').value = data.id_escuela;
            document.getElementById('nombre_escuela').value = data.nombre_escuela;
            document.getElementById('director').value = data.director || '';
            document.getElementById('municipio').value = data.municipio || '';
            document.getElementById('cantidad_estudiantes').value = data.cantidad_estudiantes || 0;
            document.getElementById('id_programa').value = data.id_programa || '';
        }
        modal.style.display = 'flex';
    }

    function cerrarModales() {
        modal.style.display = 'none';
        modalEliminar.style.display = 'none';
    }

    if (btnNuevo) {
        btnNuevo.addEventListener('click', () => abrirModal('crear'));
    }

    if (cerrarModal) {
        cerrarModal.addEventListener('click', cerrarModales);
    }

    if (cancelarModal) {
        cancelarModal.addEventListener('click', cerrarModales);
    }

    if (cancelarEliminar) {
        cancelarEliminar.addEventListener('click', cerrarModales);
    }

    if (btnGuardar) {
        btnGuardar.addEventListener('click', async function() {
            const id = document.getElementById('escuela_id').value;
            const isEdit = id && id !== '';
            
            const data = {
                nombre_escuela: document.getElementById('nombre_escuela').value,
                director: document.getElementById('director').value,
                municipio: document.getElementById('municipio').value,
                cantidad_estudiantes: document.getElementById('cantidad_estudiantes').value,
                id_programa: document.getElementById('id_programa').value,
                _token: document.querySelector('meta[name="csrf-token"]').content
            };
            
            let url, method;
            
            if (isEdit) {
                url = `/escuelas/${id}`;
                method = 'PUT';
            } else {
                url = '/escuelas';
                method = 'POST';
            }
            
            try {
                const response = await fetch(url, {
                    method: method,
                    headers: { 
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(data)
                });
                const result = await response.json();
                if (result.success) {
                    showNotification(isEdit ? 'Escuela actualizada con éxito' : 'Escuela creada con éxito', 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showNotification('Error al guardar la escuela', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Error al guardar la escuela', 'error');
            }
        });
    }

    document.querySelectorAll('.btn-editar').forEach(btn => {
        btn.addEventListener('click', async () => {
            const id = btn.dataset.id;
            const response = await fetch(`/escuelas/${id}`);
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
            const response = await fetch(`/escuelas/${eliminarId}`, {
                method: 'DELETE',
                headers: { 
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            const result = await response.json();
            if (result.success) {
                showNotification('Escuela eliminada con éxito', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showNotification('Error al eliminar la escuela', 'error');
            }
        });
    }
});