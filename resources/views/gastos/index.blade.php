@extends('layouts.dashboard')

@section('title', 'Gastos')

@section('styles')
@vite('resources/css/gastos.css')
@endsection

@section('content')
<div>
    <div class="gastos-header">
        <h1><i class="fas fa-money-bill-wave"></i> Gastos</h1>
        <div class="search-box">
            <input type="text" id="searchInput" placeholder="Buscar por categoría o descripción..." value="{{ request('search') }}">
            <button id="btnBuscar"><i class="fas fa-search"></i> Buscar</button>
            <button id="btnLimpiar" style="background: #6c7a8a;"><i class="fas fa-times"></i> Limpiar</button>
        </div>
    </div>

    <div class="resumen-card">
        <h3><i class="fas fa-chart-line"></i> Total Gastos</h3>
        <div class="valor">${{ number_format($totalGastos, 2, '.', ',') }}</div>
    </div>

    <div class="filtros-container">
        <div class="filtros-row">
            <div class="filtro-group">
                <label>Categoría</label>
                <select id="filtroCategoria" class="filtro-select">
                    <option value="">Todas las categorías</option>
                    @foreach($categorias as $cat)
                    <option value="{{ $cat }}" {{ request('categoria') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
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
        <i class="fas fa-plus"></i> Nuevo Gasto
    </button>

    <div style="clear: both;"></div>

    <div class="gastos-table-container">
        <table class="gastos-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Categoría</th>
                    <th>Descripción</th>
                    <th>Fecha</th>
                    <th>Monto</th>
                    <th style="text-align: center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($gastos as $gasto)
                <tr data-id="{{ $gasto->id_gasto }}">
                    <td>{{ $gasto->id_gasto }}</td>
                    <td>
                        <span class="categoria-badge">{{ $gasto->categoria }}</span>
                    </td>
                    <td>{{ \Illuminate\Support\Str::limit($gasto->descripcion, 50) ?? '-' }}</td>
                    <td>{{ $gasto->fecha }}</td>
                    <td>${{ number_format($gasto->monto, 2, '.', ',') }}</td>
                    <td style="text-align: center">
                        <button class="btn-accion btn-pdf" data-id="{{ $gasto->id_gasto }}" title="Exportar PDF" style="color: #dc3545;">
                            <i class="fas fa-file-pdf"></i>
                        </button>
                        <button class="btn-accion btn-editar" data-id="{{ $gasto->id_gasto }}" title="Editar">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-accion btn-eliminar" data-id="{{ $gasto->id_gasto }}" title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 40px;">
                        <i class="fas fa-money-bill-wave" style="font-size: 48px; color: #6c7a8a; margin-bottom: 10px; display: block;"></i>
                        No hay gastos registrados
                        <div style="margin-top: 10px;">
                            <button id="btnNuevoEmpty" class="btn-nuevo" style="padding: 8px 20px; float: none;">
                                <i class="fas fa-plus"></i> Registrar primer gasto
                            </button>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination">
        {{ $gastos->appends([
            'search' => request('search'),
            'categoria' => request('categoria'),
            'fecha_desde' => request('fecha_desde'),
            'fecha_hasta' => request('fecha_hasta'),
            'orden' => request('orden')
        ])->links() }}
    </div>
</div>

<!-- Modal Gasto -->
<div id="modalGasto" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <h2 id="modalTitulo"><i class="fas fa-plus-circle"></i> Nuevo Gasto</h2>
            <button id="cerrarModal" class="modal-close">&times;</button>
        </div>
        <form id="formGasto">
            @csrf
            <input type="hidden" id="gasto_id" name="gasto_id">

            <div class="form-group">
                <label>Categoría *</label>
                <input type="text" id="categoria" name="categoria" placeholder="Ej: Compra de llantas, Reparación, Combustible" required>
            </div>

            <div class="form-group">
                <label>Descripción</label>
                <textarea id="descripcion" name="descripcion" rows="3" placeholder="Detalle del gasto..."></textarea>
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
                <button type="button" id="btnGuardarGasto" class="btn-guardar">Guardar</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Eliminar -->
<div id="modalEliminar" class="modal-overlay">
    <div class="modal-container modal-eliminar" style="width: 380px; text-align: center;">
        <i class="fas fa-exclamation-triangle" style="font-size: 48px; color: #f0ad4e; margin-bottom: 15px;"></i>
        <h3 style="margin-bottom: 10px;">¿Eliminar gasto?</h3>
        <p style="color: #6c7a8a; margin-bottom: 20px;">Esta acción no se puede deshacer</p>
        <div style="display: flex; gap: 10px; justify-content: center;">
            <button id="cancelarEliminar" class="btn-cancelar">Cancelar</button>
            <button id="confirmarEliminar" class="btn-eliminar-confirmar" style="background: #dc3545; color: white; padding: 8px 20px; border: none; border-radius: 6px; cursor: pointer;">Eliminar</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@vite('resources/js/gastos.js')
@endsection