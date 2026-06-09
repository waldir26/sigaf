@extends('layouts.dashboard')

@section('title', 'Participantes')

@section('styles')
@vite('resources/css/participantes.css')
@endsection

@section('content')
<div>
    <div class="participantes-header">
        <h1><i class="fas fa-users"></i> Participantes</h1>
        <div class="search-box">
            <input type="text" id="searchInput" placeholder="Buscar por ID, nombre, apellido..." value="{{ request('search') }}">
            <button id="btnBuscar"><i class="fas fa-search"></i> Buscar</button>
            <button id="btnLimpiar" style="background: #6c7a8a;"><i class="fas fa-times"></i> Limpiar</button>
        </div>
    </div>

    <!-- FILTROS -->
    <div style="background: white; border-radius: 10px; padding: 15px; margin-bottom: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
        <div style="display: flex; flex-wrap: wrap; gap: 15px; align-items: flex-end;">
            <!-- Filtro por Programa -->
            <div style="flex: 1; min-width: 150px;">
                <label style="display: block; font-size: 12px; color: #6c7a8a; margin-bottom: 5px;">Programa</label>
                <select id="filtroPrograma" class="filtro-select" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 6px;">
                    <option value="">Todos los programas</option>
                    @foreach($programas as $programa)
                        <option value="{{ $programa->id_programa }}" {{ request('programa_id') == $programa->id_programa ? 'selected' : '' }}>
                            {{ $programa->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <!-- Filtro por Tipo -->
            <div style="flex: 1; min-width: 130px;">
                <label style="display: block; font-size: 12px; color: #6c7a8a; margin-bottom: 5px;">Tipo</label>
                <select id="filtroTipo" class="filtro-select" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 6px;">
                    <option value="">Todos los tipos</option>
                    <option value="escolar" {{ request('tipo_inscripcion') == 'escolar' ? 'selected' : '' }}>Escolar</option>
                    <option value="sabatino" {{ request('tipo_inscripcion') == 'sabatino' ? 'selected' : '' }}>Sabatino</option>
                    <option value="externo" {{ request('tipo_inscripcion') == 'externo' ? 'selected' : '' }}>Externo</option>
                </select>
            </div>
            
            <!-- Filtro por Escuela -->
            <div style="flex: 1; min-width: 150px;">
                <label style="display: block; font-size: 12px; color: #6c7a8a; margin-bottom: 5px;">Escuela</label>
                <select id="filtroEscuela" class="filtro-select" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 6px;">
                    <option value="">Todas las escuelas</option>
                    @foreach($escuelas as $escuela)
                        <option value="{{ $escuela->id_escuela }}" {{ request('escuela_id') == $escuela->id_escuela ? 'selected' : '' }}>
                            {{ $escuela->nombre_escuela }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <!-- Ordenar por -->
            <div style="flex: 1; min-width: 150px;">
                <label style="display: block; font-size: 12px; color: #6c7a8a; margin-bottom: 5px;">Ordenar por</label>
                <select id="filtroOrden" class="filtro-select" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 6px;">
                    <option value="id_desc" {{ request('orden') == 'id_desc' ? 'selected' : '' }}>ID (Recientes primero)</option>
                    <option value="id_asc" {{ request('orden') == 'id_asc' ? 'selected' : '' }}>ID (Antiguos primero)</option>
                    <option value="nombre_asc" {{ request('orden') == 'nombre_asc' ? 'selected' : '' }}>Nombre (A-Z)</option>
                    <option value="nombre_desc" {{ request('orden') == 'nombre_desc' ? 'selected' : '' }}>Nombre (Z-A)</option>
                    <option value="apellido_asc" {{ request('orden') == 'apellido_asc' ? 'selected' : '' }}>Apellido (A-Z)</option>
                    <option value="apellido_desc" {{ request('orden') == 'apellido_desc' ? 'selected' : '' }}>Apellido (Z-A)</option>
                </select>
            </div>
            
            <!-- Botón aplicar filtros -->
            <div>
                <button id="btnAplicarFiltros" style="background: #1a2a4f; color: white; padding: 8px 20px; border: none; border-radius: 6px; cursor: pointer;">
                    <i class="fas fa-filter"></i> Aplicar
                </button>
                <button id="btnLimpiarFiltros" style="background: #6c7a8a; color: white; padding: 8px 15px; border: none; border-radius: 6px; cursor: pointer; margin-left: 10px;">
                    <i class="fas fa-times"></i> Limpiar filtros
                </button>
            </div>
        </div>
    </div>

    <div class="participantes-table-container">
        <table class="participantes-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombres</th>
                    <th>Apellidos</th>
                    <th>Edad</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                    <th style="text-align: center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($participantes as $participante)
                <tr data-id="{{ $participante->id_participante }}">
                    <td>{{ $participante->id_participante }}</td>
                    <td><strong>{{ $participante->nombres }}</strong></td>
                    <td>{{ $participante->apellidos }}</td>
                    <td>{{ $participante->edad ?? '-' }}</td>
                    <td>{{ $participante->telefono ?? '-' }}</td>
                    <td>{{ $participante->correo ?? '-' }}</td>
                    <td style="text-align: center">
                        <button class="btn-accion btn-ver" data-id="{{ $participante->id_participante }}" title="Ver inscripciones" style="color: #17a2b8;">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn-accion btn-inscribir" data-id="{{ $participante->id_participante }}" title="Inscribir en otro programa" style="color: #28a745;">
                            <i class="fas fa-plus-circle"></i>
                        </button>
                        <button class="btn-accion btn-editar" data-id="{{ $participante->id_participante }}" title="Editar datos" style="color: #ffc107;">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-accion btn-eliminar" data-id="{{ $participante->id_participante }}" title="Eliminar" style="color: #dc3545;">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 40px;">
                        <i class="fas fa-users" style="font-size: 48px; color: #6c7a8a; margin-bottom: 10px; display: block;"></i>
                        No hay participantes registrados
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="pagination">
        {{ $participantes->appends([
            'search' => request('search'),
            'programa_id' => request('programa_id'),
            'tipo_inscripcion' => request('tipo_inscripcion'),
            'escuela_id' => request('escuela_id'),
            'orden' => request('orden')
        ])->links() }}
    </div>
</div>

<!-- MODALES (igual que antes, se mantienen) -->
<!-- Modal Ver Inscripciones -->
<div id="modalVerInscripciones" class="modal-overlay">
    <div class="modal-container" style="width: 750px; max-width: 90%;">
        <div class="modal-header">
            <h2><i class="fas fa-list"></i> Programas del Participante</h2>
            <button id="cerrarVerModal" class="modal-close">&times;</button>
        </div>
        <div id="inscripcionesList" style="max-height: 400px; overflow-y: auto;"></div>
        <div class="modal-buttons" style="margin-top: 15px;">
            <button type="button" id="cancelarVerModal" class="btn-cancelar">Cerrar</button>
            <button type="button" id="guardarCambiosEstado" class="btn-guardar">Guardar Cambios de Estado</button>
        </div>
    </div>
</div>

<!-- Modal Editar Participante -->
<div id="modalParticipante" class="modal-overlay">
    <div class="modal-container" style="width: 550px; max-width: 90%;">
        <div class="modal-header">
            <h2 id="modalTitulo"><i class="fas fa-edit"></i> Editar Participante</h2>
            <button id="cerrarModal" class="modal-close">&times;</button>
        </div>
        <form id="formParticipante">
            @csrf
            <input type="hidden" id="participante_id" name="participante_id">
            <div class="form-row">
                <div class="form-group">
                    <label>Nombres *</label>
                    <input type="text" id="nombres" name="nombres" required>
                </div>
                <div class="form-group">
                    <label>Apellidos *</label>
                    <input type="text" id="apellidos" name="apellidos" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Edad</label>
                    <input type="number" id="edad" name="edad" min="0" max="120">
                </div>
                <div class="form-group">
                    <label>Teléfono</label>
                    <input type="text" id="telefono" name="telefono">
                </div>
            </div>
            <div class="form-group">
                <label>Correo Electrónico</label>
                <input type="email" id="correo" name="correo">
            </div>
            <div class="form-group">
                <label>Dirección</label>
                <input type="text" id="direccion" name="direccion">
            </div>
            <div class="modal-buttons">
                <button type="button" id="cancelarModal" class="btn-cancelar">Cancelar</button>
                <button type="button" id="btnGuardarParticipante" class="btn-guardar">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Nueva Inscripción -->
<div id="modalNuevaInscripcion" class="modal-overlay">
    <div class="modal-container" style="width: 500px;">
        <div class="modal-header">
            <h2><i class="fas fa-plus-circle"></i> Inscribir en Programa</h2>
            <button id="cerrarNuevaModal" class="modal-close">&times;</button>
        </div>
        <form id="formNuevaInscripcion">
            @csrf
            <input type="hidden" id="nueva_insc_participante_id" name="id_participante">
            <div class="form-group">
                <label>Programa *</label>
                <select id="nueva_insc_programa" name="id_programa" required>
                    <option value="">Seleccionar programa</option>
                    @foreach($programas as $programa)
                        <option value="{{ $programa->id_programa }}">{{ $programa->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Tipo de Inscripción *</label>
                <select id="nueva_insc_tipo" name="tipo_inscripcion" required>
                    <option value="escolar">Escolar</option>
                    <option value="sabatino">Sabatino</option>
                    <option value="externo">Externo</option>
                </select>
            </div>
            <div id="nueva_escuela_group" class="form-group">
                <label>Escuela</label>
                <select id="nueva_insc_escuela" name="id_escuela">
                    <option value="">Seleccionar escuela</option>
                    @foreach($escuelas as $escuela)
                        <option value="{{ $escuela->id_escuela }}">{{ $escuela->nombre_escuela }}</option>
                    @endforeach
                </select>
            </div>
            <div class="modal-buttons">
                <button type="button" id="cancelarNuevaModal" class="btn-cancelar">Cancelar</button>
                <button type="button" id="btnGuardarNuevaInscripcion" class="btn-guardar">Guardar</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Eliminar -->
<div id="modalEliminar" class="modal-overlay">
    <div class="modal-container modal-eliminar" style="width: 380px; text-align: center;">
        <div style="margin-bottom: 15px;">
            <i class="fas fa-exclamation-triangle" style="font-size: 48px; color: #f0ad4e;"></i>
        </div>
        <h3 style="margin-bottom: 10px; font-size: 18px;">¿Eliminar participante?</h3>
        <p style="color: #6c7a8a; margin-bottom: 20px;">Esta acción no se puede deshacer</p>
        <div style="display: flex; gap: 10px; justify-content: center;">
            <button id="cancelarEliminar" class="btn-cancelar" style="padding: 8px 20px;">Cancelar</button>
            <button id="confirmarEliminar" class="btn-eliminar-confirmar" style="background: #dc3545; color: white; padding: 8px 20px; border: none; border-radius: 6px; cursor: pointer;">Eliminar</button>
        </div>
    </div>
</div>

<script>
    const nuevaTipoSelect = document.getElementById('nueva_insc_tipo');
    const nuevaEscuelaGroup = document.getElementById('nueva_escuela_group');
    
    function toggleNuevaEscuelaField() {
        if (nuevaTipoSelect && nuevaTipoSelect.value === 'escolar') {
            nuevaEscuelaGroup.style.display = 'block';
            document.getElementById('nueva_insc_escuela').required = true;
        } else if (nuevaEscuelaGroup) {
            nuevaEscuelaGroup.style.display = 'none';
            if (document.getElementById('nueva_insc_escuela')) {
                document.getElementById('nueva_insc_escuela').required = false;
            }
        }
    }
    
    if (nuevaTipoSelect) {
        nuevaTipoSelect.addEventListener('change', toggleNuevaEscuelaField);
        toggleNuevaEscuelaField();
    }
    
    // Filtros
    const btnAplicarFiltros = document.getElementById('btnAplicarFiltros');
    const btnLimpiarFiltros = document.getElementById('btnLimpiarFiltros');
    const filtroPrograma = document.getElementById('filtroPrograma');
    const filtroTipo = document.getElementById('filtroTipo');
    const filtroEscuela = document.getElementById('filtroEscuela');
    const filtroOrden = document.getElementById('filtroOrden');
    const searchInputFiltro = document.getElementById('searchInput');
    const btnBuscarFiltro = document.getElementById('btnBuscar');
    const btnLimpiarFiltro = document.getElementById('btnLimpiar');
    
    function aplicarFiltros() {
        let url = '/participantes?';
        const params = [];
        
        if (searchInputFiltro && searchInputFiltro.value) {
            params.push(`search=${encodeURIComponent(searchInputFiltro.value)}`);
        }
        if (filtroPrograma && filtroPrograma.value) {
            params.push(`programa_id=${filtroPrograma.value}`);
        }
        if (filtroTipo && filtroTipo.value) {
            params.push(`tipo_inscripcion=${filtroTipo.value}`);
        }
        if (filtroEscuela && filtroEscuela.value) {
            params.push(`escuela_id=${filtroEscuela.value}`);
        }
        if (filtroOrden && filtroOrden.value) {
            params.push(`orden=${filtroOrden.value}`);
        }
        
        window.location.href = url + params.join('&');
    }
    
    function limpiarFiltros() {
        window.location.href = '/participantes';
    }
    
    if (btnAplicarFiltros) btnAplicarFiltros.addEventListener('click', aplicarFiltros);
    if (btnLimpiarFiltros) btnLimpiarFiltros.addEventListener('click', limpiarFiltros);
    if (btnBuscarFiltro) btnBuscarFiltro.addEventListener('click', aplicarFiltros);
    if (btnLimpiarFiltro) btnLimpiarFiltro.addEventListener('click', limpiarFiltros);
    
    if (searchInputFiltro) {
        searchInputFiltro.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') aplicarFiltros();
        });
    }
</script>
@endsection

@section('scripts')
@vite('resources/js/participantes.js')
@endsection