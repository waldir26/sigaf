document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('modalGasto');
    const modalEliminar = document.getElementById('modalEliminar');
    const btnNuevo = document.getElementById('btnNuevo');
    const btnNuevoEmpty = document.getElementById('btnNuevoEmpty');
    const btnGuardar = document.getElementById('btnGuardarGasto');
    const cerrarModal = document.getElementById('cerrarModal');
    const cancelarModal = document.getElementById('cancelarModal');
    const cancelarEliminar = document.getElementById('cancelarEliminar');
    const confirmarEliminar = document.getElementById('confirmarEliminar');
    let eliminarId = null;

    // Filtros
    const btnAplicarFiltros = document.getElementById('btnAplicarFiltros');
    const btnLimpiarFiltros = document.getElementById('btnLimpiarFiltros');
    const btnExportarReporte = document.getElementById('btnExportarReporte');
    const filtroCategoria = document.getElementById('filtroCategoria');
    const filtroFechaDesde = document.getElementById('filtroFechaDesde');
    const filtroFechaHasta = document.getElementById('filtroFechaHasta');
    const filtroOrden = document.getElementById('filtroOrden');
    const searchInput = document.getElementById('searchInput');
    const btnBuscar = document.getElementById('btnBuscar');
    const btnLimpiar = document.getElementById('btnLimpiar');

    function aplicarFiltros() {
        let url = '/gastos?';
        const params = [];
        if (searchInput && searchInput.value) params.push(`search=${encodeURIComponent(searchInput.value)}`);
        if (filtroCategoria && filtroCategoria.value) params.push(`categoria=${filtroCategoria.value}`);
        if (filtroFechaDesde && filtroFechaDesde.value) params.push(`fecha_desde=${filtroFechaDesde.value}`);
        if (filtroFechaHasta && filtroFechaHasta.value) params.push(`fecha_hasta=${filtroFechaHasta.value}`);
        if (filtroOrden && filtroOrden.value) params.push(`orden=${filtroOrden.value}`);
        window.location.href = url + params.join('&');
    }

    function limpiarFiltros() {
        window.location.href = '/gastos';
    }

    function exportarReporte() {
        let url = '/gastos/exportar/reporte?';
        const params = [];
        if (filtroCategoria && filtroCategoria.value) params.push(`categoria=${filtroCategoria.value}`);
        if (filtroFechaDesde && filtroFechaDesde.value) params.push(`fecha_desde=${filtroFechaDesde.value}`);
        if (filtroFechaHasta && filtroFechaHasta.value) params.push(`fecha_hasta=${filtroFechaHasta.value}`);
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
            modalTitulo.innerHTML = '<i class="fas fa-plus-circle"></i> Nuevo Gasto';
            document.getElementById('formGasto').reset();
            document.getElementById('gasto_id').value = '';
            document.getElementById('fecha').value = new Date().toISOString().split('T')[0];
        } else if (data) {
            modalTitulo.innerHTML = '<i class="fas fa-edit"></i> Editar Gasto';
            document.getElementById('gasto_id').value = data.id_gasto;
            document.getElementById('categoria').value = data.categoria;
            document.getElementById('descripcion').value = data.descripcion || '';
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

    // Guardar Gasto
    // Guardar Gasto (Crear o Editar)
if (btnGuardar) {
    btnGuardar.addEventListener('click', function() {
        const id = document.getElementById('gasto_id').value;
        const isEdit = id && id !== '';
        
        const categoria = document.getElementById('categoria').value;
        const descripcion = document.getElementById('descripcion').value;
        const fecha = document.getElementById('fecha').value;
        const monto = document.getElementById('monto').value;
        
        if (!categoria) { showNotification('La categoría es obligatoria', 'error'); return; }
        if (!fecha) { showNotification('La fecha es obligatoria', 'error'); return; }
        if (!monto || monto <= 0) { showNotification('El monto debe ser mayor a 0', 'error'); return; }
        
        let url = isEdit ? `/gastos/${id}` : '/gastos';
        let method = isEdit ? 'PUT' : 'POST';
        
        fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                categoria: categoria,
                descripcion: descripcion,
                fecha: fecha,
                monto: parseFloat(monto)
            })
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                showNotification(isEdit ? 'Gasto actualizado con éxito' : 'Gasto registrado con éxito', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showNotification(result.message || 'Error al guardar', 'error');
            }
        })
        .catch(error => {
            showNotification('Error de conexión', 'error');
        });
    });
}

    // Editar, Eliminar, PDF
    document.body.addEventListener('click', async function(e) {
        const btnEditar = e.target.closest('.btn-editar');
        const btnEliminar = e.target.closest('.btn-eliminar');
        const btnPdf = e.target.closest('.btn-pdf');
        
        if (btnEditar) {
            const id = btnEditar.dataset.id;
            const response = await fetch(`/gastos/${id}`);
            const data = await response.json();
            abrirModal('editar', data);
        }
        
        if (btnEliminar) {
            eliminarId = btnEliminar.dataset.id;
            modalEliminar.style.display = 'flex';
        }
        
        if (btnPdf) {
            const id = btnPdf.dataset.id;
            window.open(`/gastos/${id}/pdf`, '_blank');
        }
    });

    if (confirmarEliminar) {
        confirmarEliminar.addEventListener('click', async () => {
            const response = await fetch(`/gastos/${eliminarId}`, {
                method: 'DELETE',
                headers: { 
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            const result = await response.json();
            if (result.success) {
                showNotification('Gasto eliminado con éxito', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showNotification('Error al eliminar', 'error');
            }
        });
    }
});