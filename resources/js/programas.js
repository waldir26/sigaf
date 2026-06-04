document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('modalPrograma');
    const modalEliminar = document.getElementById('modalEliminar');
    const form = document.getElementById('formPrograma');
    const btnNuevo = document.getElementById('btnNuevo');
    const btnGuardar = document.getElementById('btnGuardarPrograma');
    const cerrarModal = document.getElementById('cerrarModal');
    const cancelarModal = document.getElementById('cancelarModal');
    const cancelarEliminar = document.getElementById('cancelarEliminar');
    const confirmarEliminar = document.getElementById('confirmarEliminar');
    let eliminarId = null;

    function abrirModal(titulo, data = null) {
        const modalTitulo = document.getElementById('modalTitulo');
        if (titulo === 'crear') {
            modalTitulo.innerHTML = '<i class="fas fa-plus-circle"></i> Nuevo Programa';
            form.reset();
            document.getElementById('programa_id').value = '';
            document.getElementById('estado').value = 'activo';
        } else if (data) {
            modalTitulo.innerHTML = '<i class="fas fa-edit"></i> Editar Programa';
            document.getElementById('programa_id').value = data.id_programa;
            document.getElementById('nombre').value = data.nombre;
            document.getElementById('descripcion').value = data.descripcion || '';
            document.getElementById('fecha_inicio').value = data.fecha_inicio || '';
            document.getElementById('fecha_fin').value = data.fecha_fin || '';
            document.getElementById('estado').value = data.estado;
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

    // Evento para guardar (crear o editar)
    if (btnGuardar) {
        btnGuardar.addEventListener('click', async function() {
            const id = document.getElementById('programa_id').value;
            const isEdit = id && id !== '';
            
            const data = {
                nombre: document.getElementById('nombre').value,
                descripcion: document.getElementById('descripcion').value,
                fecha_inicio: document.getElementById('fecha_inicio').value,
                fecha_fin: document.getElementById('fecha_fin').value,
                estado: document.getElementById('estado').value,
                _token: document.querySelector('meta[name="csrf-token"]').content
            };
            
            let url, method;
            
            if (isEdit) {
                url = `/programas/${id}`;
                method = 'PUT';
            } else {
                url = '/programas';
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
                alert('Error al guardar el programa');
            }
        });
    }

    document.querySelectorAll('.btn-editar').forEach(btn => {
        btn.addEventListener('click', async () => {
            const id = btn.dataset.id;
            const response = await fetch(`/programas/${id}`);
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
            const response = await fetch(`/programas/${eliminarId}`, {
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