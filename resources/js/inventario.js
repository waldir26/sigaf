document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('modalProducto');
    const modalEliminar = document.getElementById('modalEliminar');
    const form = document.getElementById('formProducto');
    const btnNuevo = document.getElementById('btnNuevo');
    const btnGuardar = document.getElementById('btnGuardarProducto');
    const cerrarModal = document.getElementById('cerrarModal');
    const cancelarModal = document.getElementById('cancelarModal');
    const cancelarEliminar = document.getElementById('cancelarEliminar');
    const confirmarEliminar = document.getElementById('confirmarEliminar');
    let eliminarId = null;

    function abrirModal(titulo, data = null) {
        const modalTitulo = document.getElementById('modalTitulo');
        if (titulo === 'crear') {
            modalTitulo.innerHTML = '<i class="fas fa-plus-circle"></i> Nuevo Producto';
            form.reset();
            document.getElementById('producto_id').value = '';
            document.getElementById('estado').value = 'disponible';
            document.getElementById('cantidad').value = 0;
        } else if (data) {
            modalTitulo.innerHTML = '<i class="fas fa-edit"></i> Editar Producto';
            document.getElementById('producto_id').value = data.id_producto;
            document.getElementById('nombre_producto').value = data.nombre_producto;
            document.getElementById('categoria').value = data.categoria || '';
            document.getElementById('cantidad').value = data.cantidad;
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

    if (btnGuardar) {
        btnGuardar.addEventListener('click', async function() {
            const id = document.getElementById('producto_id').value;
            const isEdit = id && id !== '';
            
            const data = {
                nombre_producto: document.getElementById('nombre_producto').value,
                categoria: document.getElementById('categoria').value,
                cantidad: document.getElementById('cantidad').value,
                estado: document.getElementById('estado').value,
                _token: document.querySelector('meta[name="csrf-token"]').content
            };
            
            let url, method;
            
            if (isEdit) {
                url = `/inventario/${id}`;
                method = 'PUT';
            } else {
                url = '/inventario';
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
                    showNotification(isEdit ? 'Producto actualizado con éxito' : 'Producto creado con éxito', 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showNotification('Error al guardar el producto', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Error al guardar el producto', 'error');
            }
        });
    }

    document.querySelectorAll('.btn-editar').forEach(btn => {
        btn.addEventListener('click', async () => {
            const id = btn.dataset.id;
            const response = await fetch(`/inventario/${id}`);
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
            const response = await fetch(`/inventario/${eliminarId}`, {
                method: 'DELETE',
                headers: { 
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            const result = await response.json();
            if (result.success) {
                showNotification('Producto eliminado con éxito', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showNotification('Error al eliminar el producto', 'error');
            }
        });
    }
});