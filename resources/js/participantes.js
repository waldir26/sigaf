document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('modalParticipante');
    const modalVer = document.getElementById('modalVerInscripciones');
    const modalNueva = document.getElementById('modalNuevaInscripcion');
    const modalEliminar = document.getElementById('modalEliminar');
    const form = document.getElementById('formParticipante');
    const btnGuardar = document.getElementById('btnGuardarParticipante');
    const btnGuardarNueva = document.getElementById('btnGuardarNuevaInscripcion');
    const cerrarModal = document.getElementById('cerrarModal');
    const cerrarVerModal = document.getElementById('cerrarVerModal');
    const cerrarNuevaModal = document.getElementById('cerrarNuevaModal');
    const cancelarModal = document.getElementById('cancelarModal');
    const cancelarNuevaModal = document.getElementById('cancelarNuevaModal');
    const cancelarEliminar = document.getElementById('cancelarEliminar');
    const confirmarEliminar = document.getElementById('confirmarEliminar');
    const btnBuscar = document.getElementById('btnBuscar');
    const btnLimpiar = document.getElementById('btnLimpiar');
    const searchInput = document.getElementById('searchInput');
    let eliminarId = null;
    let currentParticipanteId = null;

    // Búsqueda
    if (btnBuscar) {
        btnBuscar.addEventListener('click', () => {
            const search = searchInput.value;
            window.location.href = `/participantes?search=${encodeURIComponent(search)}`;
        });
    }
    
    if (btnLimpiar) {
        btnLimpiar.addEventListener('click', () => {
            window.location.href = '/participantes';
        });
    }
    
    if (searchInput) {
        searchInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                const search = searchInput.value;
                window.location.href = `/participantes?search=${encodeURIComponent(search)}`;
            }
        });
    }

    // Abrir modal editar
    function abrirModalEditar(data) {
        document.getElementById('modalTitulo').innerHTML = '<i class="fas fa-edit"></i> Editar Participante';
        document.getElementById('participante_id').value = data.id_participante;
        document.getElementById('nombres').value = data.nombres;
        document.getElementById('apellidos').value = data.apellidos;
        document.getElementById('edad').value = data.edad || '';
        document.getElementById('telefono').value = data.telefono || '';
        document.getElementById('correo').value = data.correo || '';
        document.getElementById('direccion').value = data.direccion || '';
        modal.style.display = 'flex';
    }

    // Abrir modal ver inscripciones
    async function abrirModalVer(id) {
        currentParticipanteId = id;
        const response = await fetch(`/participantes/${id}`);
        const data = await response.json();
        
        let html = '';
        if (data.inscripciones.length === 0) {
            html = '<p style="text-align: center; padding: 20px;">No está inscrito en ningún programa</p>';
        } else {
            html = '<table style="width: 100%; border-collapse: collapse;">';
            html += '<thead><tr style="background: #1a2a4f; color: white;"><th style="padding: 8px;">Programa</th><th style="padding: 8px;">Tipo</th><th style="padding: 8px;">Escuela</th><th style="padding: 8px;">Estado</th></tr></thead><tbody>';
            data.inscripciones.forEach(ins => {
                html += `<tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 8px;">${ins.programa?.nombre || 'N/A'}</td>
                    <td style="padding: 8px;"><span class="tipo-badge tipo-${ins.tipo_inscripcion}">${ins.tipo_inscripcion}</span></td>
                    <td style="padding: 8px;">${ins.escuela?.nombre_escuela || '-'}</td>
                    <td style="padding: 8px;">${ins.estado}</td>
                </tr>`;
            });
            html += '</tbody></table>';
        }
        document.getElementById('inscripcionesList').innerHTML = html;
        modalVer.style.display = 'flex';
    }

    // Abrir modal nueva inscripción
    function abrirModalNuevaInscripcion(id) {
        currentParticipanteId = id;
        document.getElementById('nueva_insc_participante_id').value = id;
        document.getElementById('formNuevaInscripcion').reset();
        document.getElementById('nueva_insc_tipo').value = 'escolar';
        // Trigger para mostrar escuela
        const event = new Event('change');
        document.getElementById('nueva_insc_tipo').dispatchEvent(event);
        modalNueva.style.display = 'flex';
    }

    function cerrarModales() {
        modal.style.display = 'none';
        modalVer.style.display = 'none';
        modalNueva.style.display = 'none';
        modalEliminar.style.display = 'none';
    }

    document.querySelectorAll('.btn-editar').forEach(btn => {
        btn.addEventListener('click', async () => {
            const id = btn.dataset.id;
            const response = await fetch(`/participantes/${id}`);
            const data = await response.json();
            abrirModalEditar(data.participante);
        });
    });

    document.querySelectorAll('.btn-ver').forEach(btn => {
        btn.addEventListener('click', async () => {
            const id = btn.dataset.id;
            await abrirModalVer(id);
        });
    });

    document.querySelectorAll('.btn-inscribir').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            abrirModalNuevaInscripcion(id);
        });
    });

    document.querySelectorAll('.btn-eliminar').forEach(btn => {
        btn.addEventListener('click', () => {
            eliminarId = btn.dataset.id;
            modalEliminar.style.display = 'flex';
        });
    });

    // Guardar edición
    if (btnGuardar) {
        btnGuardar.addEventListener('click', async function() {
            const id = document.getElementById('participante_id').value;
            
            const data = {
                nombres: document.getElementById('nombres').value,
                apellidos: document.getElementById('apellidos').value,
                edad: document.getElementById('edad').value,
                telefono: document.getElementById('telefono').value,
                correo: document.getElementById('correo').value,
                direccion: document.getElementById('direccion').value,
                _token: document.querySelector('meta[name="csrf-token"]').content
            };
            
            try {
                const response = await fetch(`/participantes/${id}`, {
                    method: 'PUT',
                    headers: { 
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(data)
                });
                const result = await response.json();
                if (result.success) {
                    showNotification('Participante actualizado con éxito', 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showNotification('Error al actualizar', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Error al guardar', 'error');
            }
        });
    }

    // Guardar nueva inscripción
    if (btnGuardarNueva) {
        btnGuardarNueva.addEventListener('click', async function() {
            const data = {
                id_participante: document.getElementById('nueva_insc_participante_id').value,
                id_programa: document.getElementById('nueva_insc_programa').value,
                tipo_inscripcion: document.getElementById('nueva_insc_tipo').value,
                id_escuela: document.getElementById('nueva_insc_escuela').value,
                _token: document.querySelector('meta[name="csrf-token"]').content
            };
            
            try {
                const response = await fetch('/participantes/inscripcion', {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(data)
                });
                const result = await response.json();
                if (result.success) {
                    showNotification('Inscripción agregada con éxito', 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showNotification(result.message || 'Error al inscribir', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Error al guardar la inscripción', 'error');
            }
        });
    }

    // Cerrar modales
    if (cerrarModal) cerrarModal.addEventListener('click', cerrarModales);
    if (cerrarVerModal) cerrarVerModal.addEventListener('click', cerrarModales);
    if (cerrarNuevaModal) cerrarNuevaModal.addEventListener('click', cerrarModales);
    if (cancelarModal) cancelarModal.addEventListener('click', cerrarModales);
    if (cancelarNuevaModal) cancelarNuevaModal.addEventListener('click', cerrarModales);
    if (cancelarEliminar) cancelarEliminar.addEventListener('click', cerrarModales);

    // Eliminar
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
                showNotification('Participante eliminado con éxito', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showNotification(result.message || 'Error al eliminar', 'error');
            }
        });
    }
});