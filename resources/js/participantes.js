document.addEventListener('DOMContentLoaded', function () {

    // Función de notificación
    function showNotification(message, type = 'info') {
        const existingToast = document.querySelector('.toast-notification');
        if (existingToast) existingToast.remove();

        const toast = document.createElement('div');
        toast.className = `toast-notification ${type}`;

        let icon = '';
        switch (type) {
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

    const modal = document.getElementById('modalParticipante');
    const modalVer = document.getElementById('modalVerInscripciones');
    const modalNueva = document.getElementById('modalNuevaInscripcion');
    const modalEliminar = document.getElementById('modalEliminar');
    const form = document.getElementById('formParticipante');
    const btnGuardar = document.getElementById('btnGuardarParticipante');
    const btnGuardarNueva = document.getElementById('btnGuardarNuevaInscripcion');
    const btnGuardarCambiosEstado = document.getElementById('guardarCambiosEstado');
    const cerrarModal = document.getElementById('cerrarModal');
    const cerrarVerModal = document.getElementById('cerrarVerModal');
    const cancelarVerModal = document.getElementById('cancelarVerModal');
    const cerrarNuevaModal = document.getElementById('cerrarNuevaModal');
    const cancelarModal = document.getElementById('cancelarModal');
    const cancelarNuevaModal = document.getElementById('cancelarNuevaModal');
    const cancelarEliminar = document.getElementById('cancelarEliminar');
    const confirmarEliminar = document.getElementById('confirmarEliminar');
    let eliminarId = null;
    let currentParticipanteId = null;
    let cambiosEstado = {};

    // ========== FILTROS ==========
    const btnAplicarFiltros = document.getElementById('btnAplicarFiltros');
    const btnLimpiarFiltros = document.getElementById('btnLimpiarFiltros');
    const filtroPrograma = document.getElementById('filtroPrograma');
    const filtroTipo = document.getElementById('filtroTipo');
    const filtroEscuela = document.getElementById('filtroEscuela');
    const filtroSexo = document.getElementById('filtroSexo');
    const filtroOrden = document.getElementById('filtroOrden');
    const searchInput = document.getElementById('searchInput');
    const btnBuscar = document.getElementById('btnBuscar');
    const btnLimpiar = document.getElementById('btnLimpiar');

    function aplicarFiltros() {
        let url = '/participantes?';
        const params = [];
        if (searchInput && searchInput.value) params.push(`search=${encodeURIComponent(searchInput.value)}`);
        if (filtroPrograma && filtroPrograma.value) params.push(`programa_id=${filtroPrograma.value}`);
        if (filtroTipo && filtroTipo.value) params.push(`tipo_inscripcion=${filtroTipo.value}`);
        if (filtroEscuela && filtroEscuela.value) params.push(`escuela_id=${filtroEscuela.value}`);
        if (filtroSexo && filtroSexo.value) params.push(`sexo=${filtroSexo.value}`);
        if (filtroOrden && filtroOrden.value) params.push(`orden=${filtroOrden.value}`);
        window.location.href = url + params.join('&');
    }

    function limpiarFiltros() {
        window.location.href = '/participantes';
    }

    if (btnAplicarFiltros) btnAplicarFiltros.addEventListener('click', aplicarFiltros);
    if (btnLimpiarFiltros) btnLimpiarFiltros.addEventListener('click', limpiarFiltros);
    if (btnBuscar) btnBuscar.addEventListener('click', aplicarFiltros);
    if (btnLimpiar) btnLimpiar.addEventListener('click', limpiarFiltros);
    if (searchInput) {
        searchInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') aplicarFiltros();
        });
    }

    // ========== FUNCIONES DE INSCRIPCIONES ==========
    async function cargarInscripcionesVer(participanteId) {
        const response = await fetch(`/participantes/${participanteId}`);
        const data = await response.json();
        cambiosEstado = {};

        let html = '';
        if (data.inscripciones.length === 0) {
            html = '<p style="text-align: center; padding: 20px; color: #6c7a8a;">No está inscrito en ningún programa</p>';
        } else {
            html = '<table style="width: 100%; border-collapse: collapse;">';
            html += '<thead><tr style="background: #1a2a4f; color: white;">';
            html += '<th style="padding: 10px; text-align: left;">Programa</th>';
            html += '<th style="padding: 10px; text-align: left;">Tipo</th>';
            html += '<th style="padding: 10px; text-align: left;">Escuela</th>';
            html += '<th style="padding: 10px; text-align: left;">Fecha</th>';
            html += '<th style="padding: 10px; text-align: left;">Estado</th>';
            html += '</thead><tbody>';

            data.inscripciones.forEach(ins => {
                html += `<tr style="border-bottom: 1px solid #eee;" data-programa="${ins.programa?.nombre || 'N/A'}" data-tipo="${ins.tipo_inscripcion}">
                    <td style="padding: 10px;">${ins.programa?.nombre || 'N/A'}</td>
                    <td style="padding: 10px;"><span class="tipo-badge tipo-${ins.tipo_inscripcion}">${ins.tipo_inscripcion}</span></td>
                    <td style="padding: 10px;">${ins.escuela?.nombre_escuela || '-'}</td>
                    <td style="padding: 10px;">${ins.fecha_inscripcion || '-'}</td>
                    <td style="padding: 10px;">
                        <select class="estado-select" data-id="${ins.id_inscripcion}" data-estado-original="${ins.estado}" style="padding: 5px 8px; border-radius: 4px; border: 1px solid #ddd;">
                            <option value="activo" ${ins.estado === 'activo' ? 'selected' : ''}>Activo</option>
                            <option value="finalizado" ${ins.estado === 'finalizado' ? 'selected' : ''}>Finalizado</option>
                            <option value="cancelado" ${ins.estado === 'cancelado' ? 'selected' : ''}>Cancelado</option>
                        </select>
                    </td>
                </tr>`;
            });
            html += '</tbody></table>';
        }
        document.getElementById('inscripcionesList').innerHTML = html;

        document.querySelectorAll('.estado-select').forEach(select => {
            select.addEventListener('change', (e) => {
                const id = e.target.dataset.id;
                const nuevoEstado = e.target.value;
                const estadoOriginal = e.target.dataset.estadoOriginal;
                if (nuevoEstado !== estadoOriginal) {
                    cambiosEstado[id] = nuevoEstado;
                } else {
                    delete cambiosEstado[id];
                }
            });
        });
    }

    async function guardarCambiosEstado() {
        const ids = Object.keys(cambiosEstado);
        if (ids.length === 0) {
            showNotification('No hay cambios para guardar', 'info');
            return;
        }

        let successCount = 0;
        let errorCount = 0;

        for (const id of ids) {
            const nuevoEstado = cambiosEstado[id];

            try {
                const response = await fetch(`/inscripciones/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        estado: nuevoEstado
                    })
                });
                const result = await response.json();
                if (result.success) {
                    successCount++;
                    const select = document.querySelector(`.estado-select[data-id="${id}"]`);
                    if (select) select.dataset.estadoOriginal = nuevoEstado;
                } else {
                    errorCount++;
                }
            } catch (error) {
                errorCount++;
            }
        }

        if (errorCount === 0 && successCount > 0) {
            showNotification(`${successCount} estado(s) actualizado(s) con éxito`, 'success');
            await cargarInscripcionesVer(currentParticipanteId);
            cambiosEstado = {};
        } else if (errorCount > 0 && successCount > 0) {
            showNotification(`${successCount} actualizados, ${errorCount} errores`, 'warning');
        } else if (errorCount > 0) {
            showNotification(`Error al actualizar ${errorCount} estado(s)`, 'error');
        }
    }

    // ========== MODALES ==========
    async function abrirModalVer(id) {
        currentParticipanteId = id;
        await cargarInscripcionesVer(id);
        modalVer.style.display = 'flex';
    }

    async function abrirModalEditar(id) {
        const response = await fetch(`/participantes/${id}`);
        const data = await response.json();
        const participante = data.participante;

        document.getElementById('participante_id').value = participante.id_participante;
        document.getElementById('nombres').value = participante.nombres;
        document.getElementById('apellidos').value = participante.apellidos;
        document.getElementById('edad').value = participante.edad || '';
        document.getElementById('telefono').value = participante.telefono || '';
        document.getElementById('correo').value = participante.correo || '';
        document.getElementById('direccion').value = participante.direccion || '';
        document.getElementById('sexo').value = participante.sexo || '';

        modal.style.display = 'flex';
    }

    function abrirModalNuevaInscripcion(id) {
        currentParticipanteId = id;
        document.getElementById('nueva_insc_participante_id').value = id;
        document.getElementById('formNuevaInscripcion').reset();
        document.getElementById('nueva_insc_tipo').value = 'escolar';
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

    // ========== EVENTOS DE BOTONES ==========
    document.querySelectorAll('.btn-ver').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            abrirModalVer(id);
        });
    });

    document.querySelectorAll('.btn-editar').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            abrirModalEditar(id);
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

    if (cerrarModal) cerrarModal.addEventListener('click', cerrarModales);
    if (cerrarVerModal) cerrarVerModal.addEventListener('click', cerrarModales);
    if (cancelarVerModal) cancelarVerModal.addEventListener('click', cerrarModales);
    if (cerrarNuevaModal) cerrarNuevaModal.addEventListener('click', cerrarModales);
    if (cancelarModal) cancelarModal.addEventListener('click', cerrarModales);
    if (cancelarNuevaModal) cancelarNuevaModal.addEventListener('click', cerrarModales);
    if (cancelarEliminar) cancelarEliminar.addEventListener('click', cerrarModales);

    if (btnGuardarCambiosEstado) {
        btnGuardarCambiosEstado.addEventListener('click', guardarCambiosEstado);
    }

    // ========== GUARDAR EDICIÓN DE PARTICIPANTE ==========
    if (btnGuardar) {
        btnGuardar.addEventListener('click', async function () {
            const id = document.getElementById('participante_id').value;

            const data = {
                nombres: document.getElementById('nombres').value,
                apellidos: document.getElementById('apellidos').value,
                edad: document.getElementById('edad').value,
                telefono: document.getElementById('telefono').value,
                correo: document.getElementById('correo').value,
                direccion: document.getElementById('direccion').value,
                sexo: document.getElementById('sexo').value,
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
                showNotification('Error al guardar', 'error');
            }
        });
    }

    // ========== GUARDAR NUEVA INSCRIPCIÓN ==========
    if (btnGuardarNueva) {
        btnGuardarNueva.addEventListener('click', async function () {
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
                    cerrarModales();
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showNotification(result.message || 'Error al inscribir', 'error');
                }
            } catch (error) {
                showNotification('Error al guardar la inscripción', 'error');
            }
        });
    }

    // ========== ELIMINAR PARTICIPANTE ==========
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