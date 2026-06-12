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

    const modal = document.getElementById('modalVenta');
    const modalEliminar = document.getElementById('modalEliminar');
    const form = document.getElementById('formVenta');
    const btnNuevo = document.getElementById('btnNuevo');
    const btnNuevoEmpty = document.getElementById('btnNuevoEmpty');
    const btnGuardar = document.getElementById('btnGuardarVenta');
    const cerrarModal = document.getElementById('cerrarModal');
    const cancelarModal = document.getElementById('cancelarModal');
    const cancelarEliminar = document.getElementById('cancelarEliminar');
    const confirmarEliminar = document.getElementById('confirmarEliminar');
    let eliminarId = null;

    // Filtros
    const btnAplicarFiltros = document.getElementById('btnAplicarFiltros');
    const btnLimpiarFiltros = document.getElementById('btnLimpiarFiltros');
    const btnExportarReporte = document.getElementById('btnExportarReporte');
    const filtroArticulo = document.getElementById('filtroArticulo');
    const filtroFechaDesde = document.getElementById('filtroFechaDesde');
    const filtroFechaHasta = document.getElementById('filtroFechaHasta');
    const filtroOrden = document.getElementById('filtroOrden');
    const searchInput = document.getElementById('searchInput');
    const btnBuscar = document.getElementById('btnBuscar');
    const btnLimpiar = document.getElementById('btnLimpiar');

    function aplicarFiltros() {
        let url = '/ventas?';
        const params = [];
        if (searchInput && searchInput.value) params.push(`search=${encodeURIComponent(searchInput.value)}`);
        if (filtroArticulo && filtroArticulo.value) params.push(`articulo=${encodeURIComponent(filtroArticulo.value)}`);
        if (filtroFechaDesde && filtroFechaDesde.value) params.push(`fecha_desde=${filtroFechaDesde.value}`);
        if (filtroFechaHasta && filtroFechaHasta.value) params.push(`fecha_hasta=${filtroFechaHasta.value}`);
        if (filtroOrden && filtroOrden.value) params.push(`orden=${filtroOrden.value}`);
        window.location.href = url + params.join('&');
    }

    function limpiarFiltros() {
        window.location.href = '/ventas';
    }

    function exportarReporte() {
        let url = '/ventas/exportar/reporte?';
        const params = [];
        if (filtroArticulo && filtroArticulo.value) params.push(`articulo=${encodeURIComponent(filtroArticulo.value)}`);
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
            modalTitulo.innerHTML = '<i class="fas fa-plus-circle"></i> Nueva Venta';
            form.reset();
            document.getElementById('venta_id').value = '';
            document.getElementById('fecha').value = new Date().toISOString().split('T')[0];
        } else if (data) {
            modalTitulo.innerHTML = '<i class="fas fa-edit"></i> Editar Venta';
            document.getElementById('venta_id').value = data.id_venta;
            document.getElementById('articulo').value = data.articulo;
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
        btnGuardar.addEventListener('click', async function () {
            const id = document.getElementById('venta_id').value;
            const isEdit = id && id !== '';

            const data = {
                articulo: document.getElementById('articulo').value,
                fecha: document.getElementById('fecha').value,
                monto: document.getElementById('monto').value,
                _token: document.querySelector('meta[name="csrf-token"]').content
            };

            let url, method;
            if (isEdit) {
                url = `/ventas/${id}`;
                method = 'PUT';
            } else {
                url = '/ventas';
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
                    showNotification(isEdit ? 'Venta actualizada con éxito' : 'Venta registrada con éxito', 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showNotification('Error al guardar la venta', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Error al guardar la venta', 'error');
            }
        });
    }

    document.body.addEventListener('click', async function (e) {
        const btnEditar = e.target.closest('.btn-editar');
        const btnEliminar = e.target.closest('.btn-eliminar');
        const btnPdf = e.target.closest('.btn-pdf');

        if (btnEditar) {
            const id = btnEditar.dataset.id;
            const response = await fetch(`/ventas/${id}`);
            const data = await response.json();
            abrirModal('editar', data);
        }

        if (btnEliminar) {
            eliminarId = btnEliminar.dataset.id;
            modalEliminar.style.display = 'flex';
        }

        if (btnPdf) {
            const id = btnPdf.dataset.id;
            window.open(`/ventas/${id}/pdf`, '_blank');
        }
    });

    if (confirmarEliminar) {
        confirmarEliminar.addEventListener('click', async () => {
            const response = await fetch(`/ventas/${eliminarId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            const result = await response.json();
            if (result.success) {
                showNotification('Venta eliminada con éxito', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showNotification('Error al eliminar la venta', 'error');
            }
        });
    }
});