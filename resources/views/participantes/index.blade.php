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
                @foreach($participantes as $participante)
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
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="pagination">
        {{ $participantes->appends(['search' => request('search')])->links() }}
    </div>
</div>

<!-- Modal Ver Inscripciones (con cambio de estado) -->
<div id="modalVerInscripciones" class="modal-overlay">
    <div class="modal-container" style="width: 750px; max-width: 90%;">
        <div class="modal-header">
            <h2><i class="fas fa-list"></i> Programas del Participante</h2>
            <button id="cerrarVerModal" class="modal-close">&times;</button>
        </div>
        <div id="inscripcionesList" style="max-height: 400px; overflow-y: auto;">
            <!-- Las inscripciones se cargan aquí -->
        </div>
        <div class="modal-buttons" style="margin-top: 15px;">
            <button type="button" id="cancelarVerModal" class="btn-cancelar">Cerrar</button>
            <button type="button" id="guardarCambiosEstado" class="btn-guardar">Guardar Cambios de Estado</button>
        </div>
    </div>
</div>

<!-- Modal Editar Participante (solo datos personales) -->
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
    <div class="modal-container modal-eliminar" style="width: 400px;">
        <i class="fas fa-exclamation-triangle" style="font-size: 40px; color: #f0ad4e; margin-bottom: 10px;"></i>
        <h3 style="margin-bottom: 8px;">¿Eliminar participante?</h3>
        <p id="eliminarMensaje" style="color: #6c7a8a; margin-bottom: 15px;">Esta acción no se puede deshacer</p>
        <div style="display: flex; gap: 10px; justify-content: center;">
            <button id="cancelarEliminar" class="btn-cancelar">Cancelar</button>
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
</script>
@endsection

@section('scripts')
@vite('resources/js/participantes.js')
@endsection