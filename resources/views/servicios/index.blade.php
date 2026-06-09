@extends('layouts.dashboard')

@section('title', 'Servicios y Actividades')

@section('styles')
@vite('resources/css/servicios.css')
@endsection

@section('content')
<div>
    <div class="servicios-header">
        <h1><i class="fas fa-concierge-bell"></i> Servicios y Actividades</h1>
        <div class="search-box">
            <input type="text" id="searchInput" placeholder="Buscar por servicio, responsable..." value="{{ request('search') }}">
            <button id="btnBuscar"><i class="fas fa-search"></i> Buscar</button>
            <button id="btnLimpiar" style="background: #6c7a8a;"><i class="fas fa-times"></i> Limpiar</button>
        </div>
    </div>

    <div class="resumen-card">
        <h3><i class="fas fa-chart-line"></i> Total Ingresos por Servicios</h3>
        <div class="valor">${{ number_format($totalIngresos, 2, '.', ',') }}</div>
    </div>

    <div class="filtros-container">
        <div class="filtros-row">
            <div class="filtro-group">
                <label>Tipo de Servicio</label>
                <select id="filtroTipo" class="filtro-select">
                    <option value="">Todos</option>
                    @foreach($tipos as $tipo)
                        <option value="{{ $tipo }}" {{ request('tipo') == $tipo ? 'selected' : '' }}>{{ $tipo }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filtro-group">
                <label>Responsable</label>
                <input type="text" id="filtroResponsable" class="filtro-input" placeholder="Responsable" value="{{ request('responsable') }}">
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
        <i class="fas fa-plus"></i> Nuevo Servicio
    </button>

    <div style="clear: both;"></div>

    <div class="servicios-table-container">
        <table class="servicios-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Servicio</th>
                    <th>Descripción</th>
                    <th>Responsable</th>
                    <th>Fecha</th>
                    <th>Monto</th>
                    <th style="text-align: center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($servicios as $servicio)
                <tr data-id="{{ $servicio->id_servicio }}">
                    <td>{{ $servicio->id_servicio }}</td>
                    <td><strong>{{ $servicio->tipo_servicio }}</strong></td>
                    <td>{{ \Illuminate\Support\Str::limit($servicio->descripcion, 40) ?? '-' }}</td>
                    <td>{{ $servicio->responsable ?? '-' }}</td>
                    <td>{{ $servicio->fecha }}</td>
                    <td>${{ number_format($servicio->monto, 2, '.', ',') }}</td>
                    <td style="text-align: center">
                        <button class="btn-accion btn-pdf" data-id="{{ $servicio->id_servicio }}" title="Exportar PDF" style="color: #dc3545;">
                            <i class="fas fa-file-pdf"></i>
                        </button>
                        <button class="btn-accion btn-editar" data-id="{{ $servicio->id_servicio }}" title="Editar">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-accion btn-eliminar" data-id="{{ $servicio->id_servicio }}" title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 40px;">
                        <i class="fas fa-concierge-bell" style="font-size: 48px; color: #6c7a8a; margin-bottom: 10px; display: block;"></i>
                        No hay servicios registrados
                        <div style="margin-top: 10px;">
                            <button id="btnNuevoEmpty" class="btn-nuevo" style="padding: 8px 20px; float: none;">
                                <i class="fas fa-plus"></i> Registrar primer servicio
                            </button>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="pagination">
        {{ $servicios->appends([
            'search' => request('search'),
            'tipo' => request('tipo'),
            'responsable' => request('responsable'),
            'fecha_desde' => request('fecha_desde'),
            'fecha_hasta' => request('fecha_hasta'),
            'orden' => request('orden')
        ])->links() }}
    </div>
</div>

<!-- Modal Servicio -->
<div id="modalServicio" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <h2 id="modalTitulo"><i class="fas fa-plus-circle"></i> Nuevo Servicio</h2>
            <button id="cerrarModal" class="modal-close">&times;</button>
        </div>
        <form id="formServicio">
            @csrf
            <input type="hidden" id="servicio_id" name="servicio_id">
            
            <div class="form-group">
                <label>Tipo de Servicio *</label>
                <input type="text" id="tipo_servicio" name="tipo_servicio" placeholder="Ej: Uso de piscina, Alquiler de cancha, Evento empresarial" required>
            </div>
            
            <div class="form-group">
                <label>Descripción</label>
                <textarea id="descripcion" name="descripcion" rows="3" placeholder="Detalles del servicio..."></textarea>
            </div>
            
            <div class="form-group">
                <label>Responsable</label>
                <input type="text" id="responsable" name="responsable" placeholder="Quién recibió el pago">
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
                <button type="button" id="btnGuardarServicio" class="btn-guardar">Guardar</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Eliminar -->
<div id="modalEliminar" class="modal-overlay">
    <div class="modal-container modal-eliminar" style="width: 380px; text-align: center;">
        <i class="fas fa-exclamation-triangle" style="font-size: 48px; color: #f0ad4e; margin-bottom: 15px;"></i>
        <h3 style="margin-bottom: 10px;">¿Eliminar servicio?</h3>
        <p style="color: #6c7a8a; margin-bottom: 20px;">Esta acción no se puede deshacer</p>
        <div style="display: flex; gap: 10px; justify-content: center;">
            <button id="cancelarEliminar" class="btn-cancelar">Cancelar</button>
            <button id="confirmarEliminar" class="btn-eliminar-confirmar" style="background: #dc3545; color: white; padding: 8px 20px; border: none; border-radius: 6px; cursor: pointer;">Eliminar</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@vite('resources/js/servicios.js')
@endsection