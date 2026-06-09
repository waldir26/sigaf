document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('modalInscripcion');
    const modalEliminar = document.getElementById('modalEliminar');
    const form = document.getElementById('formInscripcion');
    const btnNuevo = document.getElementById('btnNuevo');
    const btnGuardar = document.getElementById('btnGuardarInscripcion');
    const cerrarModal = document.getElementById('cerrarModal');
    const cancelarModal = document.getElementById('cancelarModal');
    const cancelarEliminar = document.getElementById('cancelarEliminar');
    const confirmarEliminar = document.getElementById('confirmarEliminar');
    let eliminarId = null;

    // Mostrar/ocultar campo escuela según tipo de inscripción
    const tipoSelect = document.getElementById('tipo_inscripcion');
    const escuelaGroup = document.getElementById('escuelaGroup');
    
    function toggleEscuelaField() {
        if (tipoSelect && tipoSelect.value === 'escolar') {
            escuelaGroup.style.display = 'block';
            document.getElementById('id_escuela').required = true;
        } else if (escuelaGroup) {
            escuelaGroup.style.display = 'none';
            document.getElementById('id_escuela').required = false;
            document.getElementById('id_escuela').value = '';
        }
    }

    function abrirModal(titulo, data = null) {
        const modalTitulo = document.getElementById('modalTitulo');
        const participanteSection = document.getElementById('participanteSection');
        
        if (titulo === 'crear') {
            modalTitulo.innerHTML = '<i class="fas fa-plus-circle"></i> Nueva Inscripción';
            form.reset();
            document.getElementById('inscripcion_id').value = '';
            document.getElementById('fecha_inscripcion').value = new Date().toISOString().split('T')[0];
            document.getElementById('estado').value = 'activo';
            document.getElementById('tipo_inscripcion').value = 'escolar';
            if (participanteSection) {
                participanteSection.style.display = 'block';
            }
            // Limpiar campos
            document.getElementById('nombres').value = '';
            document.getElementById('apellidos').value = '';
            document.getElementById('edad').value = '';
            document.getElementById('telefono').value = '';
            document.getElementById('correo').value = '';
            document.getElementById('direccion').value = '';
            document.getElementById('sexo').value = '';
            // Trigger para mostrar/ocultar escuela
            const event = new Event('change');
            document.getElementById('tipo_inscripcion').dispatchEvent(event);
        } else if (data) {
            modalTitulo.innerHTML = '<i class="fas fa-edit"></i> Editar Inscripción';
            document.getElementById('inscripcion_id').value = data.id_inscripcion;
            document.getElementById('id_programa').value = data.id_programa;
            document.getElementById('tipo_inscripcion').value = data.tipo_inscripcion;
            document.getElementById('id_escuela').value = data.id_escuela || '';
            document.getElementById('fecha_inscripcion').value = data.fecha_inscripcion || '';
            document.getElementById('estado').value = data.estado;
            if (participanteSection) {
                participanteSection.style.display = 'none';
            }
            const event = new Event('change');
            document.getElementById('tipo_inscripcion').dispatchEvent(event);
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

    if (tipoSelect) {
        tipoSelect.addEventListener('change', toggleEscuelaField);
        toggleEscuelaField();
    }

    if (btnGuardar) {
        btnGuardar.addEventListener('click', async function(e) {
            e.preventDefault();
            
            const id = document.getElementById('inscripcion_id').value;
            const isEdit = id && id !== '';
            
            let data;
            let url;
            let method;
            
            if (isEdit) {
                data = {
                    id_programa: document.getElementById('id_programa').value,
                    tipo_inscripcion: document.getElementById('tipo_inscripcion').value,
                    id_escuela: document.getElementById('id_escuela').value,
                    fecha_inscripcion: document.getElementById('fecha_inscripcion').value,
                    estado: document.getElementById('estado').value,
                    _token: document.querySelector('meta[name="csrf-token"]').content
                };
                url = `/inscripciones/${id}`;
                method = 'PUT';
            } else {
                data = {
                    nombres: document.getElementById('nombres').value,
                    apellidos: document.getElementById('apellidos').value,
                    edad: document.getElementById('edad').value,
                    telefono: document.getElementById('telefono').value,
                    correo: document.getElementById('correo').value,
                    direccion: document.getElementById('direccion').value,
                    sexo: document.getElementById('sexo').value,
                    id_programa: document.getElementById('id_programa').value,
                    tipo_inscripcion: document.getElementById('tipo_inscripcion').value,
                    id_escuela: document.getElementById('id_escuela').value,
                    fecha_inscripcion: document.getElementById('fecha_inscripcion').value,
                    estado: document.getElementById('estado').value,
                    _token: document.querySelector('meta[name="csrf-token"]').content
                };
                url = '/inscripciones';
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
                    showNotification(isEdit ? 'Inscripción actualizada con éxito' : 'Inscripción creada con éxito', 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showNotification(result.message || 'Error al guardar la inscripción', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Error al guardar la inscripción', 'error');
            }
        });
    }

    document.querySelectorAll('.btn-editar').forEach(btn => {
        btn.addEventListener('click', async () => {
            const id = btn.dataset.id;
            const response = await fetch(`/inscripciones/${id}`);
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
            const response = await fetch(`/inscripciones/${eliminarId}`, {
                method: 'DELETE',
                headers: { 
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            const result = await response.json();
            if (result.success) {
                showNotification('Inscripción eliminada con éxito', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showNotification('Error al eliminar la inscripción', 'error');
            }
        });
    }
});