document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('modalServicio');
    const modalEliminar = document.getElementById('modalEliminar');
    const form = document.getElementById('formServicio');
    const btnNuevo = document.getElementById('btnNuevo');
    const btnNuevoEmpty = document.getElementById('btnNuevoEmpty');
    const btnGuardar = document.getElementById('btnGuardarServicio');
    const cerrarModal = document.getElementById('cerrarModal');
    const cancelarModal = document.getElementById('cancelarModal');
    const cancelarEliminar = document.getElementById('cancelarEliminar');
    const confirmarEliminar = document.getElementById('confirmarEliminar');
    let eliminarId = null;

    // Filtros
    const btnAplicarFiltros = document.getElementById('btnAplicarFiltros');
    const btnLimpiarFiltros = document.getElementById('btnLimpiarFiltros');
    const btnExportarReporte = document.getElementById('btnExportarReporte');
    const filtroTipo = document.getElementById('filtroTipo');
    const filtroResponsable = document.getElementById('filtroResponsable');
    const filtroFechaDesde = document.getElementById('filtroFechaDesde');
    const filtroFechaHasta = document.getElementById('filtroFechaHasta');
    const filtroOrden = document.getElementById('filtroOrden');
    const searchInput = document.getElementById('searchInput');
    const btnBuscar = document.getElementById('btnBuscar');
    const btnLimpiar = document.getElementById('btnLimpiar');

    function aplicarFiltros() {
        let url = '/servicios?';
        const params = [];
        if (searchInput && searchInput.value) params.push(`search=${encodeURIComponent(searchInput.value)}`);
        if (filtroTipo && filtroTipo.value) params.push(`tipo=${filtroTipo.value}`);
        if (filtroResponsable && filtroResponsable.value) params.push(`responsable=${encodeURIComponent(filtroResponsable.value)}`);
        if (filtroFechaDesde && filtroFechaDesde.value) params.push(`fecha_desde=${filtroFechaDesde.value}`);
        if (filtroFechaHasta && filtroFechaHasta.value) params.push(`fecha_hasta=${filtroFechaHasta.value}`);
        if (filtroOrden && filtroOrden.value) params.push(`orden=${filtroOrden.value}`);
        window.location.href = url + params.join('&');
    }

    function limpiarFiltros() {
        window.location.href = '/servicios';
    }

        function exportarReporte() {
        let url = '/servicios/exportar/reporte?';
        const params = [];
        if (filtroTipo && filtroTipo.value) params.push(`tipo=${filtroTipo.value}`);
        if (filtroResponsable && filtroResponsable.value) params.push(`responsable=${encodeURIComponent(filtroResponsable.value)}`);
        if (filtroFechaDesde && filtroFechaDesde.value) params.push(`fecha_desde=${filtroFechaDesde.value}`);
        if (filtroFechaHasta && filtroFechaHasta.value) params.push(`fecha_hasta=${filtroFechaHasta.value}`);
        
        // Abrir en nueva pestaña
        window.open(url + params.join('&'), '_blank');
    }

    if (btnAplicarFiltros) btnAplicarFiltros.addEventListener('click', aplicarFiltros);
    if (btnLimpiarFiltros) btnLimpiarFiltros.addEventListener('click', limpiarFiltros);
    if (btnExportarReporte) btnExportarReporte.addEventListener('click', exportarReporte);
    if (btnBuscar) btnBuscar.addEventListener('click', aplicarFiltros);
    if (btnLimpiar) btnLimpiar.addEventListener('click', limpiarFiltros);
    if (searchInput) {
        searchInput.addEventListener('keypress', (e) => { if (e.key === 'Enter') aplicarFiltros(); });
    }

    function abrirModal(titulo, data = null) {
        const modalTitulo = document.getElementById('modalTitulo');
        if (titulo === 'crear') {
            modalTitulo.innerHTML = '<i class="fas fa-plus-circle"></i> Nuevo Servicio';
            form.reset();
            document.getElementById('servicio_id').value = '';
            document.getElementById('fecha').value = new Date().toISOString().split('T')[0];
        } else if (data) {
            modalTitulo.innerHTML = '<i class="fas fa-edit"></i> Editar Servicio';
            document.getElementById('servicio_id').value = data.id_servicio;
            document.getElementById('tipo_servicio').value = data.tipo_servicio;
            document.getElementById('descripcion').value = data.descripcion || '';
            document.getElementById('responsable').value = data.responsable || '';
            document.getElementById('fecha').value = data.fecha;
            document.getElementById('monto').value = data.monto;
        }
        modal.style.display = 'flex';
    }

    function cerrarModales() {
        modal.style.display = 'none';
        modalEliminar.style.display = 'none';
    }

    if (btnNuevo) btnNuevo.addEventListener('click', () => abrirModal('crear'));
    if (btnNuevoEmpty) btnNuevoEmpty.addEventListener('click', () => abrirModal('crear'));
    if (cerrarModal) cerrarModal.addEventListener('click', cerrarModales);
    if (cancelarModal) cancelarModal.addEventListener('click', cerrarModales);
    if (cancelarEliminar) cancelarEliminar.addEventListener('click', cerrarModales);

    if (btnGuardar) {
        btnGuardar.addEventListener('click', async function() {
            const id = document.getElementById('servicio_id').value;
            const isEdit = id && id !== '';
            
            const data = {
                tipo_servicio: document.getElementById('tipo_servicio').value,
                descripcion: document.getElementById('descripcion').value,
                responsable: document.getElementById('responsable').value,
                fecha: document.getElementById('fecha').value,
                monto: document.getElementById('monto').value,
                _token: document.querySelector('meta[name="csrf-token"]').content
            };
            
            let url, method;
            if (isEdit) {
                url = `/servicios/${id}`;
                method = 'PUT';
            } else {
                url = '/servicios';
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
                    showNotification(isEdit ? 'Servicio actualizado con éxito' : 'Servicio creado con éxito', 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showNotification('Error al guardar el servicio', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Error al guardar el servicio', 'error');
            }
        });
    }

    // Eventos para botones dinámicos
    document.body.addEventListener('click', async function(e) {
        const btnEditar = e.target.closest('.btn-editar');
        const btnEliminar = e.target.closest('.btn-eliminar');
        const btnPdf = e.target.closest('.btn-pdf');
        
        if (btnEditar) {
            const id = btnEditar.dataset.id;
            const response = await fetch(`/servicios/${id}`);
            const data = await response.json();
            abrirModal('editar', data);
        }
        
        if (btnEliminar) {
            eliminarId = btnEliminar.dataset.id;
            modalEliminar.style.display = 'flex';
        }
        
        if (btnPdf) {
            const id = btnPdf.dataset.id;
            window.open(`/servicios/${id}/pdf`, '_blank');
        }
    });

    if (confirmarEliminar) {
        confirmarEliminar.addEventListener('click', async () => {
            const response = await fetch(`/servicios/${eliminarId}`, {
                method: 'DELETE',
                headers: { 
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            const result = await response.json();
            if (result.success) {
                showNotification('Servicio eliminado con éxito', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showNotification('Error al eliminar el servicio', 'error');
            }
        });
    }
});