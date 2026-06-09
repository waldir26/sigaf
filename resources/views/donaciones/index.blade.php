@extends('layouts.dashboard')

@section('title', 'Donaciones')

@section('styles')
@vite('resources/css/donaciones.css')
@endsection

@section('content')
<div>
    <div class="donaciones-header">
        <h1><i class="fas fa-hand-holding-heart"></i> Donaciones</h1>
        <div class="search-box">
            <input type="text" id="searchInput" placeholder="Buscar por donante, monto..." value="{{ request('search') }}">
            <button id="btnBuscar"><i class="fas fa-search"></i> Buscar</button>
            <button id="btnLimpiar" style="background: #6c7a8a;"><i class="fas fa-times"></i> Limpiar</button>
        </div>
    </div>

    <div class="resumen-cards">
        <div class="card">
            <h3><i class="fas fa-chart-line"></i> Total Donaciones Monetarias</h3>
            <div class="valor">${{ number_format($totalMonetario, 2, '.', ',') }}</div>
        </div>
        <div class="card">
            <h3><i class="fas fa-gift"></i> Total Donaciones</h3>
            <div class="valor">{{ $donaciones->total() }}</div>
        </div>
    </div>

    <div class="filtros-container">
        <div class="filtros-row">
            <div class="filtro-group">
                <label>Tipo</label>
                <select id="filtroTipo" class="filtro-select">
                    <option value="">Todos</option>
                    <option value="monetaria" {{ request('tipo') == 'monetaria' ? 'selected' : '' }}>Monetaria</option>
                    <option value="especie" {{ request('tipo') == 'especie' ? 'selected' : '' }}>En especie</option>
                </select>
            </div>
            <div class="filtro-group">
                <label>Donante</label>
                <select id="filtroDonante" class="filtro-select">
                    <option value="">Todos</option>
                    @foreach($donantes as $donante)
                        <option value="{{ $donante->id_donante }}" {{ request('donante_id') == $donante->id_donante ? 'selected' : '' }}>
                            {{ $donante->nombre }}
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

    <div style="margin-bottom: 15px; text-align: right;">
        <button id="btnNuevo" class="btn-nuevo">
            <i class="fas fa-plus"></i> Nueva Donación
        </button>
    </div>

    <div class="donaciones-table-container">
        <table class="donaciones-table">
            <thead>
    <tr>
        <th>ID</th>
        <th>Donante</th>
        <th>Tipo</th>
        <th>Monto</th>
        <th>Fecha</th>
        <th>Estado Sello</th>
        <th style="text-align: center">Acciones</th>
    </tr>
        </thead>
        <tbody>
            @forelse($donaciones as $donacion)
            <tr data-id="{{ $donacion->id_donacion }}">
                <td>{{ $donacion->id_donacion }}</td>
                <td><strong>{{ $donacion->donante->nombre ?? 'N/A' }}</strong></td>
                <td>
                    <span class="tipo-badge tipo-{{ $donacion->tipo_donacion }}">
                        {{ $donacion->tipo_donacion == 'monetaria' ? 'Monetaria' : 'En especie' }}
                    </span>
                </td>
                <td>
                    @if($donacion->tipo_donacion == 'monetaria')
                        ${{ number_format($donacion->monto, 2, '.', ',') }}
                    @else
                        -
                    @endif
                </td>
                <td>{{ $donacion->fecha }}</td>
                <td>
                    @if($donacion->estado_sellado == 'sellado')
                        <span style="background: #d4edda; color: #155724; padding: 4px 12px; border-radius: 20px; font-size: 12px;">
                            <i class="fas fa-check-circle"></i> Sellado
                        </span>
                    @else
                        <span style="background: #fff3cd; color: #856404; padding: 4px 12px; border-radius: 20px; font-size: 12px;">
                            <i class="fas fa-clock"></i> Pendiente
                        </span>
                    @endif
                </td>
                <td style="text-align: center">
                    <button class="btn-accion btn-pdf" data-id="{{ $donacion->id_donacion }}" title="Exportar PDF" style="color: #dc3545;">
                        <i class="fas fa-file-pdf"></i>
                    </button>
                    @if($donacion->estado_sellado == 'sellado')
                        <a href="{{ asset($donacion->documento_sellado) }}" target="_blank" class="btn-accion" style="color: #17a2b8;" title="Ver sellado">
                            <i class="fas fa-stamp"></i>
                        </a>
                    @else
                        <button class="btn-accion btn-subir-sellado" data-id="{{ $donacion->id_donacion }}" title="Subir documento sellado" style="color: #28a745;">
                            <i class="fas fa-upload"></i>
                        </button>
                    @endif
                    <button class="btn-accion btn-editar" data-id="{{ $donacion->id_donacion }}" title="Editar">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn-accion btn-eliminar" data-id="{{ $donacion->id_donacion }}" title="Eliminar">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center; padding: 40px;">No hay donaciones registradas</td>
            </tr>
            @endforelse
        </tbody>
        </table>
    </div>
    
    <div class="pagination">
        {{ $donaciones->appends([
            'search' => request('search'),
            'tipo' => request('tipo'),
            'donante_id' => request('donante_id'),
            'fecha_desde' => request('fecha_desde'),
            'fecha_hasta' => request('fecha_hasta'),
            'orden' => request('orden')
        ])->links() }}
    </div>
</div>

<!-- Modal Donación -->
<div id="modalDonacion" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <h2 id="modalTitulo"><i class="fas fa-plus-circle"></i> Nueva Donación</h2>
            <button id="cerrarModal" class="modal-close">&times;</button>
        </div>
        <form id="formDonacion">
            @csrf
            <input type="hidden" id="donacion_id" name="donacion_id">
            
            <div class="form-group">
                <label>Donante *</label>
                <select id="id_donante" name="id_donante" required style="width: 70%; display: inline-block;">
                    <option value="">Seleccionar donante</option>
                    @foreach($donantes as $donante)
                        <option value="{{ $donante->id_donante }}">{{ $donante->nombre }}</option>
                    @endforeach
                </select>
                <button type="button" id="btnNuevoDonante" style="width: 28%; padding: 8px; background: #28a745; color: white; border: none; border-radius: 6px; cursor: pointer;">
                    <i class="fas fa-plus"></i> Nuevo
                </button>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Tipo *</label>
                    <select id="tipo_donacion" name="tipo_donacion" required>
                        <option value="monetaria">Monetaria</option>
                        <option value="especie">En especie</option>
                    </select>
                </div>
                <div class="form-group" id="montoGroup">
                    <label>Monto *</label>
                    <input type="number" id="monto" name="monto" step="0.01" min="0" placeholder="0.00">
                </div>
            </div>
            
            <div class="form-group">
                <label>Descripción</label>
                <textarea id="descripcion" name="descripcion" rows="2" placeholder="Descripción de la donación..."></textarea>
            </div>
            
            <div class="form-group">
                <label>Fecha *</label>
                <input type="date" id="fecha" name="fecha" required>
            </div>
            
            <div class="modal-buttons">
                <button type="button" id="cancelarModal" class="btn-cancelar">Cancelar</button>
                <button type="button" id="btnGuardarDonacion" class="btn-guardar">Guardar</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Donante -->
<div id="modalDonante" class="modal-overlay">
    <div class="modal-container" style="width: 450px;">
        <div class="modal-header">
            <h2><i class="fas fa-user-plus"></i> Nuevo Donante</h2>
            <button id="cerrarDonanteModal" class="modal-close">&times;</button>
        </div>
        <form id="formDonante">
            @csrf
            <div class="form-group">
                <label>Nombre *</label>
                <input type="text" id="donante_nombre" name="nombre" required>
            </div>
            <div class="form-group">
                <label>Teléfono</label>
                <input type="text" id="donante_telefono" name="telefono">
            </div>
            <div class="form-group">
                <label>Correo</label>
                <input type="email" id="donante_correo" name="correo">
            </div>
            <div class="form-group">
                <label>Dirección</label>
                <input type="text" id="donante_direccion" name="direccion">
            </div>
            <div class="modal-buttons">
                <button type="button" id="cancelarDonanteModal" class="btn-cancelar">Cancelar</button>
                <button type="button" id="btnGuardarDonante" class="btn-guardar">Guardar Donante</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Eliminar -->
<div id="modalEliminar" class="modal-overlay">
    <div class="modal-container modal-eliminar" style="width: 380px; text-align: center;">
        <i class="fas fa-exclamation-triangle" style="font-size: 48px; color: #f0ad4e; margin-bottom: 15px;"></i>
        <h3 style="margin-bottom: 10px;">¿Eliminar donación?</h3>
        <p style="color: #6c7a8a; margin-bottom: 20px;">Esta acción no se puede deshacer</p>
        <div style="display: flex; gap: 10px; justify-content: center;">
            <button id="cancelarEliminar" class="btn-cancelar">Cancelar</button>
            <button id="confirmarEliminar" class="btn-eliminar-confirmar" style="background: #dc3545; color: white; padding: 8px 20px; border: none; border-radius: 6px; cursor: pointer;">Eliminar</button>
        </div>
    </div>
</div>

<!-- Modal Subir Documento Sellado -->
<div id="modalSubirSellado" class="modal-overlay">
    <div class="modal-container" style="width: 450px;">
        <div class="modal-header">
            <h2><i class="fas fa-stamp"></i> Subir Documento Sellado</h2>
            <button id="cerrarSelladoModal" class="modal-close">&times;</button>
        </div>
        <form id="formSubirSellado" enctype="multipart/form-data">
            @csrf
            <input type="hidden" id="donacion_sellado_id" name="donacion_id">
            
            <div class="form-group">
                <label>Documento Sellado (PDF, JPG, PNG) *</label>
                <input type="file" id="documento_sellado" name="documento_sellado" accept=".pdf,.jpg,.jpeg,.png" required>
                <small style="font-size: 10px; color: #6c7a8a;">Máximo 5MB. Suba el comprobante impreso y sellado.</small>
            </div>
            
            <div class="modal-buttons">
                <button type="button" id="cancelarSelladoModal" class="btn-cancelar">Cancelar</button>
                <button type="button" id="btnGuardarSellado" class="btn-guardar">Subir</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
@vite('resources/js/donaciones.js')
@endsection