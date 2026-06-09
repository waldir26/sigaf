@extends('layouts.dashboard')

@section('title', 'Inventario')

@section('styles')
@vite('resources/css/inventario.css')
@endsection

@section('content')
<div>
    <div class="inventario-header">
        <h1><i class="fas fa-boxes"></i> Inventario</h1>
        <div class="search-box">
            <input type="text" id="searchInput" placeholder="Buscar por nombre o categoría..." value="{{ request('search') }}">
            <button id="btnBuscar"><i class="fas fa-search"></i> Buscar</button>
            <button id="btnLimpiar" style="background: #6c7a8a;"><i class="fas fa-times"></i> Limpiar</button>
        </div>
    </div>

    <!-- Filtros -->
    <div class="filtros-container">
        <div class="filtros-row">
            <div class="filtro-group">
                <label>Categoría</label>
                <select id="filtroCategoria" class="filtro-select">
                    <option value="">Todas las categorías</option>
                    @foreach($categorias as $categoria)
                        <option value="{{ $categoria }}" {{ request('categoria') == $categoria ? 'selected' : '' }}>
                            {{ $categoria }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="filtro-group">
                <label>Estado</label>
                <select id="filtroEstado" class="filtro-select">
                    <option value="">Todos los estados</option>
                    <option value="disponible" {{ request('estado') == 'disponible' ? 'selected' : '' }}>Disponible</option>
                    <option value="agotado" {{ request('estado') == 'agotado' ? 'selected' : '' }}>Agotado</option>
                    <option value="dado_baja" {{ request('estado') == 'dado_baja' ? 'selected' : '' }}>Dado de baja</option>
                </select>
            </div>
            <div class="filtro-group">
                <label>Ordenar por</label>
                <select id="filtroOrden" class="filtro-select">
                    <option value="id_desc" {{ request('orden') == 'id_desc' ? 'selected' : '' }}>ID (Recientes primero)</option>
                    <option value="id_asc" {{ request('orden') == 'id_asc' ? 'selected' : '' }}>ID (Antiguos primero)</option>
                    <option value="nombre_asc" {{ request('orden') == 'nombre_asc' ? 'selected' : '' }}>Nombre (A-Z)</option>
                    <option value="nombre_desc" {{ request('orden') == 'nombre_desc' ? 'selected' : '' }}>Nombre (Z-A)</option>
                    <option value="cantidad_asc" {{ request('orden') == 'cantidad_asc' ? 'selected' : '' }}>Cantidad (menor a mayor)</option>
                    <option value="cantidad_desc" {{ request('orden') == 'cantidad_desc' ? 'selected' : '' }}>Cantidad (mayor a menor)</option>
                </select>
            </div>
            <div>
                <button id="btnAplicarFiltros" class="btn-filtro"><i class="fas fa-filter"></i> Aplicar</button>
                <button id="btnLimpiarFiltros" class="btn-limpiar"><i class="fas fa-times"></i> Limpiar</button>
            </div>
        </div>
    </div>

    <div style="margin-bottom: 15px; text-align: right;">
        <button id="btnNuevo" class="btn-nuevo">
            <i class="fas fa-plus"></i> Nuevo Producto
        </button>
    </div>

    <div class="inventario-table-container">
        <table class="inventario-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Producto</th>
                    <th>Categoría</th>
                    <th>Cantidad</th>
                    <th>Estado</th>
                    <th style="text-align: center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($productos as $producto)
                <tr data-id="{{ $producto->id_producto }}">
                    <td>{{ $producto->id_producto }}</td>
                    <td><strong>{{ $producto->nombre_producto }}</strong></td>
                    <td>{{ $producto->categoria ?? '-' }}</td>
                    <td>{{ $producto->cantidad }}</td>
                    <td>
                        <span class="estado-badge estado-{{ $producto->estado }}">
                            @if($producto->estado == 'disponible') Disponible
                            @elseif($producto->estado == 'agotado') Agotado
                            @else Dado de baja
                            @endif
                        </span>
                    </td>
                    <td style="text-align: center">
                        <button class="btn-accion btn-editar" data-id="{{ $producto->id_producto }}" title="Editar">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-accion btn-eliminar" data-id="{{ $producto->id_producto }}" title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 40px;">
                        <i class="fas fa-boxes" style="font-size: 48px; color: #6c7a8a; margin-bottom: 10px; display: block;"></i>
                        No hay productos registrados
                        <div style="margin-top: 10px;">
                            <button id="btnNuevoEmpty" class="btn-nuevo" style="padding: 8px 20px;">
                                <i class="fas fa-plus"></i> Agregar primer producto
                            </button>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="pagination">
        {{ $productos->appends([
            'search' => request('search'),
            'categoria' => request('categoria'),
            'estado' => request('estado'),
            'orden' => request('orden')
        ])->links() }}
    </div>
</div>

<!-- Modal Crear/Editar -->
<div id="modalProducto" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <h2 id="modalTitulo"><i class="fas fa-plus-circle"></i> Nuevo Producto</h2>
            <button id="cerrarModal" class="modal-close">&times;</button>
        </div>
        <form id="formProducto">
            @csrf
            <input type="hidden" id="producto_id" name="producto_id">
            
            <div class="form-group">
                <label>Nombre del Producto *</label>
                <input type="text" id="nombre_producto" name="nombre_producto" required>
            </div>
            
            <div class="form-group">
                <label>Categoría</label>
                <input type="text" id="categoria" name="categoria" placeholder="Ej: Electrónica, Mobiliario, Deportes">
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Cantidad *</label>
                    <input type="number" id="cantidad" name="cantidad" min="0" required>
                </div>
                <div class="form-group">
                    <label>Estado *</label>
                    <select id="estado" name="estado" required>
                        <option value="disponible">Disponible</option>
                        <option value="agotado">Agotado</option>
                        <option value="dado_baja">Dado de baja</option>
                    </select>
                </div>
            </div>
            
            <div class="modal-buttons">
                <button type="button" id="cancelarModal" class="btn-cancelar">Cancelar</button>
                <button type="button" id="btnGuardarProducto" class="btn-guardar">Guardar</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Eliminar -->
<div id="modalEliminar" class="modal-overlay">
    <div class="modal-container modal-eliminar" style="width: 380px;">
        <i class="fas fa-exclamation-triangle"></i>
        <h3 style="margin-bottom: 10px; margin-top: 10px;">¿Eliminar producto?</h3>
        <p style="color: #6c7a8a; margin-bottom: 20px;">Esta acción no se puede deshacer</p>
        <div class="modal-buttons" style="justify-content: center;">
            <button id="cancelarEliminar" class="btn-cancelar">Cancelar</button>
            <button id="confirmarEliminar" class="btn-eliminar-confirmar" style="background: #dc3545; color: white; padding: 8px 20px; border: none; border-radius: 6px; cursor: pointer;">Eliminar</button>
        </div>
    </div>
</div>

<script>
    // Filtros
    const btnAplicarFiltros = document.getElementById('btnAplicarFiltros');
    const btnLimpiarFiltros = document.getElementById('btnLimpiarFiltros');
    const filtroCategoria = document.getElementById('filtroCategoria');
    const filtroEstado = document.getElementById('filtroEstado');
    const filtroOrden = document.getElementById('filtroOrden');
    const searchInput = document.getElementById('searchInput');
    const btnBuscar = document.getElementById('btnBuscar');
    const btnLimpiar = document.getElementById('btnLimpiar');
    
    function aplicarFiltros() {
        let url = '/inventario?';
        const params = [];
        if (searchInput && searchInput.value) params.push(`search=${encodeURIComponent(searchInput.value)}`);
        if (filtroCategoria && filtroCategoria.value) params.push(`categoria=${filtroCategoria.value}`);
        if (filtroEstado && filtroEstado.value) params.push(`estado=${filtroEstado.value}`);
        if (filtroOrden && filtroOrden.value) params.push(`orden=${filtroOrden.value}`);
        window.location.href = url + params.join('&');
    }
    
    function limpiarFiltros() {
        window.location.href = '/inventario';
    }
    
    if (btnAplicarFiltros) btnAplicarFiltros.addEventListener('click', aplicarFiltros);
    if (btnLimpiarFiltros) btnLimpiarFiltros.addEventListener('click', limpiarFiltros);
    if (btnBuscar) btnBuscar.addEventListener('click', aplicarFiltros);
    if (btnLimpiar) btnLimpiar.addEventListener('click', limpiarFiltros);
    if (searchInput) {
        searchInput.addEventListener('keypress', (e) => { if (e.key === 'Enter') aplicarFiltros(); });
    }
    
    // Botón nuevo producto
    const btnNuevo = document.getElementById('btnNuevo');
    const btnNuevoEmpty = document.getElementById('btnNuevoEmpty');
    if (btnNuevoEmpty) btnNuevoEmpty.addEventListener('click', () => document.getElementById('btnNuevo').click());
</script>
@endsection

@section('scripts')
@vite('resources/js/inventario.js')
@endsection