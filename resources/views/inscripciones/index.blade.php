@extends('layouts.dashboard')

@section('title', 'Inscripciones')

@section('styles')
@vite('resources/css/inscripciones.css')
@endsection

@section('content')
<div>
    <div class="inscripciones-header">
        <h1><i class="fas fa-pen-alt"></i> Inscripciones</h1>
        <button id="btnNuevo" class="btn-nuevo">
            <i class="fas fa-plus"></i> Nueva Inscripción
        </button>
    </div>

    <div class="inscripciones-table-container">
        <table class="inscripciones-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Participante</th>
                    <th>Programa</th>
                    <th>Tipo</th>
                    <th>Escuela</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th style="text-align: center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($inscripciones as $inscripcion)
                <tr data-id="{{ $inscripcion->id_inscripcion }}">
                    <td>{{ $inscripcion->id_inscripcion }}</td>
                    <td><strong>{{ $inscripcion->participante->nombres ?? 'N/A' }} {{ $inscripcion->participante->apellidos ?? '' }}</strong></td>
                    <td>{{ $inscripcion->programa->nombre ?? 'N/A' }}</td>
                    <td>
                        <span class="tipo-badge tipo-{{ $inscripcion->tipo_inscripcion }}">
                            {{ ucfirst($inscripcion->tipo_inscripcion) }}
                        </span>
                    </td>
                    <td>{{ $inscripcion->escuela->nombre_escuela ?? '-' }}</td>
                    <td>{{ $inscripcion->fecha_inscripcion ?? '-' }}</td>
                    <td>
                        <span class="estado-badge estado-{{ $inscripcion->estado }}">
                            {{ ucfirst($inscripcion->estado) }}
                        </span>
                    </td>
                    <td style="text-align: center">
                        <button class="btn-accion btn-editar" data-id="{{ $inscripcion->id_inscripcion }}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-accion btn-eliminar" data-id="{{ $inscripcion->id_inscripcion }}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div id="modalInscripcion" class="modal-overlay">
    <div class="modal-container" style="width: 550px; max-width: 90%;">
        <div class="modal-header">
            <h2 id="modalTitulo" style="font-size: 18px;"><i class="fas fa-plus-circle"></i> Nueva Inscripción</h2>
            <button id="cerrarModal" class="modal-close">&times;</button>
        </div>
        <form id="formInscripcion">
            @csrf
            <input type="hidden" id="inscripcion_id" name="inscripcion_id">

            <!-- Datos del Participante -->
            <div id="participanteSection" style="background: #f5f7fa; padding: 12px; border-radius: 8px; margin-bottom: 15px;">
                <h3 style="color: #1a2a4f; margin-bottom: 10px; font-size: 13px; font-weight: 600;">
                    <i class="fas fa-user-plus"></i> Datos del Participante
                </h3>
                <div style="display: flex; gap: 10px; margin-bottom: 8px;">
                    <div style="flex: 1;">
                        <label style="font-size: 11px; display: block; margin-bottom: 3px; color: #343a40;">Nombres *</label>
                        <input type="text" id="nombres" name="nombres" placeholder="Juan Carlos" style="width: 100%; padding: 6px 8px; border: 1px solid #ddd; border-radius: 6px; font-size: 12px;">
                    </div>
                    <div style="flex: 1;">
                        <label style="font-size: 11px; display: block; margin-bottom: 3px; color: #343a40;">Apellidos *</label>
                        <input type="text" id="apellidos" name="apellidos" placeholder="Pérez Gómez" style="width: 100%; padding: 6px 8px; border: 1px solid #ddd; border-radius: 6px; font-size: 12px;">
                    </div>
                </div>
                <div style="display: flex; gap: 10px; margin-bottom: 8px;">
                    <div style="flex: 1;">
                        <label style="font-size: 11px; display: block; margin-bottom: 3px; color: #343a40;">Edad</label>
                        <input type="number" id="edad" name="edad" placeholder="Edad" style="width: 100%; padding: 6px 8px; border: 1px solid #ddd; border-radius: 6px; font-size: 12px;">
                    </div>
                    <div style="flex: 1;">
                        <label style="font-size: 11px; display: block; margin-bottom: 3px; color: #343a40;">Teléfono</label>
                        <input type="text" id="telefono" name="telefono" placeholder="Teléfono" style="width: 100%; padding: 6px 8px; border: 1px solid #ddd; border-radius: 6px; font-size: 12px;">
                    </div>
                </div>
                <div>
                    <label style="font-size: 11px; display: block; margin-bottom: 3px; color: #343a40;">Correo</label>
                    <input type="email" id="correo" name="correo" placeholder="correo@ejemplo.com" style="width: 100%; padding: 6px 8px; border: 1px solid #ddd; border-radius: 6px; font-size: 12px;">
                </div>
                <div style="margin-top: 8px;">
                    <label style="font-size: 11px; display: block; margin-bottom: 3px; color: #343a40;">Dirección</label>
                    <input type="text" id="direccion" name="direccion" placeholder="Dirección" style="width: 100%; padding: 6px 8px; border: 1px solid #ddd; border-radius: 6px; font-size: 12px;">
                </div>
                <div style="margin-top: 8px;">
                    <label style="font-size: 11px; display: block; margin-bottom: 3px; color: #343a40;">Sexo</label>
                    <select id="sexo" name="sexo" style="width: 100%; padding: 6px 8px; border: 1px solid #ddd; border-radius: 6px; font-size: 12px;">
                        <option value="">Seleccionar</option>
                        <option value="M">Masculino</option>
                        <option value="F">Femenino</option>
                    </select>
                </div>
            </div>

            <!-- Datos de Inscripción -->
            <h3 style="color: #1a2a4f; margin-bottom: 10px; font-size: 13px; font-weight: 600;">
                <i class="fas fa-pen-alt"></i> Datos de Inscripción
            </h3>

            <div style="display: flex; gap: 10px; margin-bottom: 10px;">
                <div style="flex: 1;">
                    <label style="font-size: 11px; display: block; margin-bottom: 3px; color: #343a40;">Programa *</label>
                    <select id="id_programa" name="id_programa" required style="width: 100%; padding: 6px 8px; border: 1px solid #ddd; border-radius: 6px; font-size: 12px;">
                        <option value="">Seleccionar</option>
                        @foreach($programas as $programa)
                        <option value="{{ $programa->id_programa }}">{{ $programa->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="flex: 1;">
                    <label style="font-size: 11px; display: block; margin-bottom: 3px; color: #343a40;">Tipo *</label>
                    <select id="tipo_inscripcion" name="tipo_inscripcion" required style="width: 100%; padding: 6px 8px; border: 1px solid #ddd; border-radius: 6px; font-size: 12px;">
                        <option value="escolar">Escolar</option>
                        <option value="sabatino">Sabatino</option>
                        <option value="externo">Externo</option>
                    </select>
                </div>
            </div>

            <div id="escuelaGroup" style="margin-bottom: 10px;">
                <label style="font-size: 11px; display: block; margin-bottom: 3px; color: #343a40;">Escuela</label>
                <select id="id_escuela" name="id_escuela" style="width: 100%; padding: 6px 8px; border: 1px solid #ddd; border-radius: 6px; font-size: 12px;">
                    <option value="">Seleccionar escuela</option>
                    @foreach($escuelas as $escuela)
                    <option value="{{ $escuela->id_escuela }}">{{ $escuela->nombre_escuela }}</option>
                    @endforeach
                </select>
            </div>

            <div style="display: flex; gap: 10px; margin-bottom: 15px;">
                <div style="flex: 1;">
                    <label style="font-size: 11px; display: block; margin-bottom: 3px; color: #343a40;">Fecha</label>
                    <input type="date" id="fecha_inscripcion" name="fecha_inscripcion" style="width: 100%; padding: 6px 8px; border: 1px solid #ddd; border-radius: 6px; font-size: 12px;">
                </div>
                <div style="flex: 1;">
                    <label style="font-size: 11px; display: block; margin-bottom: 3px; color: #343a40;">Estado *</label>
                    <select id="estado" name="estado" required style="width: 100%; padding: 6px 8px; border: 1px solid #ddd; border-radius: 6px; font-size: 12px;">
                        <option value="activo">Activo</option>
                        <option value="finalizado">Finalizado</option>
                        <option value="cancelado">Cancelado</option>
                    </select>
                </div>
            </div>

            <div style="display: flex; gap: 10px; justify-content: flex-end;">
                <button type="button" id="cancelarModal" style="background: #6c7a8a; color: white; padding: 6px 15px; border: none; border-radius: 6px; cursor: pointer; font-size: 12px;">Cancelar</button>
                <button type="button" id="btnGuardarInscripcion" style="background: #1a2a4f; color: white; padding: 6px 15px; border: none; border-radius: 6px; cursor: pointer; font-size: 12px;">Guardar</button>
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
        <h3 style="margin-bottom: 10px; font-size: 18px;">¿Eliminar inscripción?</h3>
        <p style="color: #6c7a8a; margin-bottom: 20px; font-size: 14px;">Esta acción no se puede deshacer</p>
        <div style="display: flex; gap: 10px; justify-content: center;">
            <button id="cancelarEliminar" style="background: #6c7a8a; color: white; padding: 8px 20px; border: none; border-radius: 6px; cursor: pointer; font-size: 14px;">Cancelar</button>
            <button id="confirmarEliminar" style="background: #dc3545; color: white; padding: 8px 20px; border: none; border-radius: 6px; cursor: pointer; font-size: 14px;">Eliminar</button>
        </div>
    </div>
</div>

@endsection

@section('scripts')
@vite('resources/js/inscripciones.js')
@endsection