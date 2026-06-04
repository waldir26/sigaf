@extends('layouts.dashboard')

@section('title', 'Participantes')

@section('styles')
@vite('resources/css/participantes.css')
@endsection

@section('content')
<div>
    <div class="participantes-header">
        <h1><i class="fas fa-users"></i> Participantes</h1>
        <button id="btnNuevo" class="btn-nuevo">
            <i class="fas fa-plus"></i> Nuevo Participante
        </button>
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
                        <button class="btn-accion btn-editar" data-id="{{ $participante->id_participante }}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-accion btn-eliminar" data-id="{{ $participante->id_participante }}">
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
<div id="modalParticipante" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <h2 id="modalTitulo"><i class="fas fa-plus-circle"></i> Nuevo Participante</h2>
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
                <button type="button" id="btnGuardarParticipante" class="btn-guardar">Guardar</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Eliminar -->
<div id="modalEliminar" class="modal-overlay">
    <div class="modal-container modal-eliminar">
        <i class="fas fa-exclamation-triangle"></i>
        <h3>¿Eliminar participante?</h3>
        <p>Esta acción no se puede deshacer</p>
        <div class="modal-buttons" style="justify-content: center;">
            <button id="cancelarEliminar" class="btn-cancelar">Cancelar</button>
            <button id="confirmarEliminar" class="btn-eliminar-confirmar">Eliminar</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@vite('resources/js/participantes.js')
@endsection