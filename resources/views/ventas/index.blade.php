@extends('layouts.dashboard')

@section('title', 'Ventas de Bienes')

@section('styles')
@vite('resources/css/ventas.css')
@endsection

@section('content')
<div>
    <div class="ventas-header">
        <h1><i class="fas fa-tags"></i> Ventas de Bienes</h1>
        <div class="search-box">
            <input type="text" id="searchInput" placeholder="Buscar por artículo..." value="{{ request('search') }}">
            <button id="btnBuscar"><i class="fas fa-search"></i> Buscar</button>
            <button id="btnLimpiar" style="background: #6c7a8a;"><i class="fas fa-times"></i> Limpiar</button>
        </div>
    </div>

    <div class="resumen-card">
        <h3><i class="fas fa-chart-line"></i> Total Ingresos por Ventas</h3>
        <div class="valor">${{ number_format($totalIngresos, 2, '.', ',') }}</div>
    </div>

    <div class="filtros-container">
        <div class="filtros-row">
            <div class="filtro-group">
                <label>Artículo</label>
                <select id="filtroArticulo" class="filtro-select">
                    <option value="">Todos los artículos</option>
                    @foreach($articulosUnicos as $articulo)
                    <option value="{{ $articulo }}" {{ request('articulo') == $articulo ? 'selected' : '' }}>
                        {{ $articulo }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="filtro-group">
                <label>Fecha desde</label>
                <input type="date" id="filtroFechaDesde" class="filtro-input" value="{{ request('fecha_desde') }}">
            </div>
            <div class="filtro-group">
                <label>Fecha hasta</label>
                <input type="date" id="filtroFechaHasta" class="filtro-input" value="{{ request('fecha_hasta') }}">
            </div>
            <div class="filtro-group">
                <label>Ordenar</label>
                <select id="filtroOrden" class="filtro-select">
                    <option value="fecha_desc" {{ request('orden') == 'fecha_desc' ? 'selected' : '' }}>Fecha (reciente)</option>
                    <option value="fecha_asc" {{ request('orden') == 'fecha_asc' ? 'selected' : '' }}>Fecha (antiguo)</option>
                    <option value="monto_desc" {{ request('orden') == 'monto_desc' ? 'selected' : '' }}>Monto (mayor)</option>
                    <option value="monto_asc" {{ request('orden') == 'monto_asc' ? 'selected' : '' }}>Monto (menor)</option>
                </select>
            </div>
            <div>
                <button id="btnAplicarFiltros" class="btn-filtro"><i class="fas fa-filter"></i> Aplicar</button>
                <button id="btnLimpiarFiltros" class="btn-limpiar"><i class="fas fa-times"></i> Limpiar</button>
                <button id="btnExportarReporte" class="btn-exportar"><i class="fas fa-file-pdf"></i> Exportar Reporte</button>
            </div>
        </div>
    </div>

    <button id="btnNuevo" class="btn-nuevo">
        <i class="fas fa-plus"></i> Nueva Venta
    </button>

    <div style="clear: both;"></div>

    <div class="ventas-table-container">
        <table class="ventas-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Artículo</th>
                    <th>Fecha</th>
                    <th>Monto</th>
                    <th style="text-align: center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ventas as $venta)
                <tr data-id="{{ $venta->id_venta }}">
                    <td>{{ $venta->id_venta }}</td>
                    <td><strong>{{ $venta->articulo }}</strong></td>
                    <td>{{ $venta->fecha }}</td>
                    <td>${{ number_format($venta->monto, 2, '.', ',') }}</td>
                    <td style="text-align: center">
                        <button class="btn-accion btn-pdf" data-id="{{ $venta->id_venta }}" title="Exportar PDF" style="color: #dc3545;">
                            <i class="fas fa-file-pdf"></i>
                        </button>
                        <button class="btn-accion btn-editar" data-id="{{ $venta->id_venta }}" title="Editar">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-accion btn-eliminar" data-id="{{ $venta->id_venta }}" title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 40px;">
                        <i class="fas fa-tags" style="font-size: 48px; color: #6c7a8a; margin-bottom: 10px; display: block;"></i>
                        No hay ventas registradas
                        <div style="margin-top: 10px;">
                            <button id="btnNuevoEmpty" class="btn-nuevo" style="padding: 8px 20px; float: none;">
                                <i class="fas fa-plus"></i> Registrar primera venta
                            </button>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination">
        {{ $ventas->appends([
            'search' => request('search'),
            'articulo' => request('articulo'),
            'fecha_desde' => request('fecha_desde'),
            'fecha_hasta' => request('fecha_hasta'),
            'orden' => request('orden')
        ])->links() }}
    </div>
</div>

<!-- Modal Venta -->
<div id="modalVenta" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <h2 id="modalTitulo"><i class="fas fa-plus-circle"></i> Nueva Venta</h2>
            <button id="cerrarModal" class="modal-close">&times;</button>
        </div>
        <form id="formVenta">
            @csrf
            <input type="hidden" id="venta_id" name="venta_id">

            <div class="form-group">
                <label>Artículo *</label>
                <input type="text" id="articulo" name="articulo" placeholder="Ej: Sillón ejecutivo, Escritorio, Ropero" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Fecha *</label>
                    <input type="date" id="fecha" name="fecha" required>
                </div>
                <div class="form-group">
                    <label>Monto *</label>
                    <input type="number" id="monto" name="monto" step="0.01" min="0" placeholder="0.00" required>
                </div>
            </div>

            <div class="modal-buttons">
                <button type="button" id="cancelarModal" class="btn-cancelar">Cancelar</button>
                <button type="button" id="btnGuardarVenta" class="btn-guardar">Guardar</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Eliminar -->
<div id="modalEliminar" class="modal-overlay">
    <div class="modal-container modal-eliminar" style="width: 380px; text-align: center;">
        <i class="fas fa-exclamation-triangle" style="font-size: 48px; color: #f0ad4e; margin-bottom: 15px;"></i>
        <h3 style="margin-bottom: 10px;">¿Eliminar venta?</h3>
        <p style="color: #6c7a8a; margin-bottom: 20px;">Esta acción no se puede deshacer</p>
        <div style="display: flex; gap: 10px; justify-content: center;">
            <button id="cancelarEliminar" class="btn-cancelar">Cancelar</button>
            <button id="confirmarEliminar" class="btn-eliminar-confirmar" style="background: #dc3545; color: white; padding: 8px 20px; border: none; border-radius: 6px; cursor: pointer;">Eliminar</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@vite('resources/js/ventas.js')
@endsection