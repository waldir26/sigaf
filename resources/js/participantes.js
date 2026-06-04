document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('modalParticipante');
    const modalEliminar = document.getElementById('modalEliminar');
    const form = document.getElementById('formParticipante');
    const btnNuevo = document.getElementById('btnNuevo');
    const btnGuardar = document.getElementById('btnGuardarParticipante');
    const cerrarModal = document.getElementById('cerrarModal');
    const cancelarModal = document.getElementById('cancelarModal');
    const cancelarEliminar = document.getElementById('cancelarEliminar');
    const confirmarEliminar = document.getElementById('confirmarEliminar');
    let eliminarId = null;

    function abrirModal(titulo, data = null) {
        const modalTitulo = document.getElementById('modalTitulo');
        if (titulo === 'crear') {
            modalTitulo.innerHTML = '<i class="fas fa-plus-circle"></i> Nuevo Participante';
            form.reset();
            document.getElementById('participante_id').value = '';
        } else if (data) {
            modalTitulo.innerHTML = '<i class="fas fa-edit"></i> Editar Participante';
            document.getElementById('participante_id').value = data.id_participante;
            document.getElementById('nombres').value = data.nombres;
            document.getElementById('apellidos').value = data.apellidos;
            document.getElementById('edad').value = data.edad || '';
            document.getElementById('telefono').value = data.telefono || '';
            document.getElementById('correo').value = data.correo || '';
            document.getElementById('direccion').value = data.direccion || '';
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
            const id = document.getElementById('participante_id').value;
            const isEdit = id && id !== '';
            
            const data = {
                nombres: document.getElementById('nombres').value,
                apellidos: document.getElementById('apellidos').value,
                edad: document.getElementById('edad').value,
                telefono: document.getElementById('telefono').value,
                correo: document.getElementById('correo').value,
                direccion: document.getElementById('direccion').value,
                _token: document.querySelector('meta[name="csrf-token"]').content
            };
            
            let url, method;
            
            if (isEdit) {
                url = `/participantes/${id}`;
                method = 'PUT';
            } else {
                url = '/participantes';
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
                    location.reload();
                } else {
                    alert('Error: ' + JSON.stringify(result));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error al guardar el participante');
            }
        });
    }

    document.querySelectorAll('.btn-editar').forEach(btn => {
        btn.addEventListener('click', async () => {
            const id = btn.dataset.id;
            const response = await fetch(`/participantes/${id}`);
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
            const response = await fetch(`/participantes/${eliminarId}`, {
                method: 'DELETE',
                headers: { 
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            const result = await response.json();
            if (result.success) {
                location.reload();
            }
        });
    }
});