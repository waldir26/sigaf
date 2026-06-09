document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('modalDonacion');
    const modalDonante = document.getElementById('modalDonante');
    const modalEliminar = document.getElementById('modalEliminar');
    const form = document.getElementById('formDonacion');
    const formDonante = document.getElementById('formDonante');
    const btnNuevo = document.getElementById('btnNuevo');
    const btnNuevoEmpty = document.getElementById('btnNuevoEmpty');
    const btnGuardar = document.getElementById('btnGuardarDonacion');
    const btnGuardarDonante = document.getElementById('btnGuardarDonante');
    const btnNuevoDonante = document.getElementById('btnNuevoDonante');
    const cerrarModal = document.getElementById('cerrarModal');
    const cerrarDonanteModal = document.getElementById('cerrarDonanteModal');
    const cancelarModal = document.getElementById('cancelarModal');
    const cancelarDonanteModal = document.getElementById('cancelarDonanteModal');
    const cancelarEliminar = document.getElementById('cancelarEliminar');
    const confirmarEliminar = document.getElementById('confirmarEliminar');
    let eliminarId = null;

    const tipoSelect = document.getElementById('tipo_donacion');
    const montoGroup = document.getElementById('montoGroup');
    
    function toggleMontoField() {
        if (tipoSelect && tipoSelect.value === 'monetaria') {
            montoGroup.style.display = 'block';
            document.getElementById('monto').required = true;
        } else if (montoGroup) {
            montoGroup.style.display = 'none';
            document.getElementById('monto').required = false;
            document.getElementById('monto').value = '';
        }
    }
    
    if (tipoSelect) {
        tipoSelect.addEventListener('change', toggleMontoField);
        toggleMontoField();
    }

    // Filtros
    const btnAplicarFiltros = document.getElementById('btnAplicarFiltros');
    const btnLimpiarFiltros = document.getElementById('btnLimpiarFiltros');
    const btnExportarReporte = document.getElementById('btnExportarReporte');
    const filtroTipo = document.getElementById('filtroTipo');
    const filtroDonante = document.getElementById('filtroDonante');
    const filtroFechaDesde = document.getElementById('filtroFechaDesde');
    const filtroFechaHasta = document.getElementById('filtroFechaHasta');
    const filtroOrden = document.getElementById('filtroOrden');
    const searchInput = document.getElementById('searchInput');
    const btnBuscar = document.getElementById('btnBuscar');
    const btnLimpiar = document.getElementById('btnLimpiar');
    
    function aplicarFiltros() {
        let url = '/donaciones?';
        const params = [];
        if (searchInput && searchInput.value) params.push(`search=${encodeURIComponent(searchInput.value)}`);
        if (filtroTipo && filtroTipo.value) params.push(`tipo=${filtroTipo.value}`);
        if (filtroDonante && filtroDonante.value) params.push(`donante_id=${filtroDonante.value}`);
        if (filtroFechaDesde && filtroFechaDesde.value) params.push(`fecha_desde=${filtroFechaDesde.value}`);
        if (filtroFechaHasta && filtroFechaHasta.value) params.push(`fecha_hasta=${filtroFechaHasta.value}`);
        if (filtroOrden && filtroOrden.value) params.push(`orden=${filtroOrden.value}`);
        window.location.href = url + params.join('&');
    }
    
    function limpiarFiltros() {
        window.location.href = '/donaciones';
    }
    
    function exportarReporte() {
        let url = '/donaciones/exportar/reporte?';
        const params = [];
        if (filtroTipo && filtroTipo.value) params.push(`tipo=${filtroTipo.value}`);
        if (filtroDonante && filtroDonante.value) params.push(`donante_id=${filtroDonante.value}`);
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
            modalTitulo.innerHTML = '<i class="fas fa-plus-circle"></i> Nueva Donación';
            form.reset();
            document.getElementById('donacion_id').value = '';
            document.getElementById('fecha').value = new Date().toISOString().split('T')[0];
            document.getElementById('tipo_donacion').value = 'monetaria';
            toggleMontoField();
        } else if (data) {
            modalTitulo.innerHTML = '<i class="fas fa-edit"></i> Editar Donación';
            document.getElementById('donacion_id').value = data.id_donacion;
            document.getElementById('id_donante').value = data.id_donante;
            document.getElementById('tipo_donacion').value = data.tipo_donacion;
            document.getElementById('monto').value = data.monto || '';
            document.getElementById('descripcion').value = data.descripcion || '';
            document.getElementById('fecha').value = data.fecha;
            toggleMontoField();
        }
        modal.style.display = 'flex';
    }

    function abrirModalDonante() {
        formDonante.reset();
        modalDonante.style.display = 'flex';
    }

    function cerrarModales() {
        modal.style.display = 'none';
        modalDonante.style.display = 'none';
        modalEliminar.style.display = 'none';
        if (modalSellado) modalSellado.style.display = 'none';
    }

    if (btnNuevo) btnNuevo.addEventListener('click', () => abrirModal('crear'));
    if (btnNuevoEmpty) btnNuevoEmpty.addEventListener('click', () => abrirModal('crear'));
    if (btnNuevoDonante) btnNuevoDonante.addEventListener('click', abrirModalDonante);
    
    if (cerrarModal) cerrarModal.addEventListener('click', cerrarModales);
    if (cerrarDonanteModal) cerrarDonanteModal.addEventListener('click', cerrarModales);
    if (cancelarModal) cancelarModal.addEventListener('click', cerrarModales);
    if (cancelarDonanteModal) cancelarDonanteModal.addEventListener('click', cerrarModales);
    if (cancelarEliminar) cancelarEliminar.addEventListener('click', cerrarModales);

    if (btnGuardar) {
        btnGuardar.addEventListener('click', async function() {
            const id = document.getElementById('donacion_id').value;
            const isEdit = id && id !== '';
            
            const data = {
                id_donante: document.getElementById('id_donante').value,
                tipo_donacion: document.getElementById('tipo_donacion').value,
                monto: document.getElementById('monto').value,
                descripcion: document.getElementById('descripcion').value,
                fecha: document.getElementById('fecha').value,
                _token: document.querySelector('meta[name="csrf-token"]').content
            };
            
            let url, method;
            if (isEdit) {
                url = `/donaciones/${id}`;
                method = 'PUT';
            } else {
                url = '/donaciones';
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
                    showNotification(isEdit ? 'Donación actualizada con éxito' : 'Donación creada con éxito', 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showNotification('Error al guardar la donación', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Error al guardar la donación', 'error');
            }
        });
    }

    if (btnGuardarDonante) {
        btnGuardarDonante.addEventListener('click', async function() {
            const data = {
                nombre: document.getElementById('donante_nombre').value,
                telefono: document.getElementById('donante_telefono').value,
                correo: document.getElementById('donante_correo').value,
                direccion: document.getElementById('donante_direccion').value,
                _token: document.querySelector('meta[name="csrf-token"]').content
            };
            
            try {
                const response = await fetch('/donantes', {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(data)
                });
                const result = await response.json();
                if (result.success) {
                    showNotification('Donante creado con éxito', 'success');
                    
                    const donanteSelect = document.getElementById('id_donante');
                    const newOption = document.createElement('option');
                    newOption.value = result.donante.id_donante;
                    newOption.textContent = result.donante.nombre;
                    donanteSelect.appendChild(newOption);
                    donanteSelect.value = result.donante.id_donante;
                    
                    document.getElementById('formDonante').reset();
                    modalDonante.style.display = 'none';
                } else {
                    showNotification('Error al crear donante', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Error al crear donante', 'error');
            }
        });
    }

    // Eventos para botones dinámicos (editar, eliminar, pdf)
    document.body.addEventListener('click', async function(e) {
        const btnEditar = e.target.closest('.btn-editar');
        const btnEliminar = e.target.closest('.btn-eliminar');
        const btnPdf = e.target.closest('.btn-pdf');
        
        if (btnEditar) {
            const id = btnEditar.dataset.id;
            const response = await fetch(`/donaciones/${id}`);
            const data = await response.json();
            abrirModal('editar', data);
        }
        
        if (btnEliminar) {
            eliminarId = btnEliminar.dataset.id;
            modalEliminar.style.display = 'flex';
        }
        
        if (btnPdf) {
            const id = btnPdf.dataset.id;
            window.open(`/donaciones/${id}/pdf`, '_blank');
        }
    });

    if (confirmarEliminar) {
        confirmarEliminar.addEventListener('click', async () => {
            const response = await fetch(`/donaciones/${eliminarId}`, {
                method: 'DELETE',
                headers: { 
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            const result = await response.json();
            if (result.success) {
                showNotification('Donación eliminada con éxito', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showNotification('Error al eliminar la donación', 'error');
            }
        });
    }

    // Subir documento sellado - con delegación de eventos
    const modalSellado = document.getElementById('modalSubirSellado');
    const cerrarSelladoModal = document.getElementById('cerrarSelladoModal');
    const cancelarSelladoModal = document.getElementById('cancelarSelladoModal');
    const btnGuardarSellado = document.getElementById('btnGuardarSellado');

    // Delegación de eventos para botón subir sellado
    document.body.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-subir-sellado');
        if (btn) {
            const id = btn.dataset.id;
            document.getElementById('donacion_sellado_id').value = id;
            document.getElementById('formSubirSellado').reset();
            if (modalSellado) modalSellado.style.display = 'flex';
        }
    });

    if (cerrarSelladoModal) {
        cerrarSelladoModal.addEventListener('click', () => {
            if (modalSellado) modalSellado.style.display = 'none';
        });
    }
    if (cancelarSelladoModal) {
        cancelarSelladoModal.addEventListener('click', () => {
            if (modalSellado) modalSellado.style.display = 'none';
        });
    }

    if (btnGuardarSellado) {
        btnGuardarSellado.addEventListener('click', async function() {
            const id = document.getElementById('donacion_sellado_id').value;
            const fileInput = document.getElementById('documento_sellado');
            
            if (!fileInput.files[0]) {
                showNotification('Seleccione un archivo', 'warning');
                return;
            }
            
            const formData = new FormData();
            formData.append('documento_sellado', fileInput.files[0]);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
            
            try {
                const response = await fetch(`/donaciones/${id}/subir-sellado`, {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();
                if (result.success) {
                    showNotification('Documento sellado subido con éxito', 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showNotification(result.message || 'Error al subir el documento', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Error al subir el documento', 'error');
            }
        });
    }
});