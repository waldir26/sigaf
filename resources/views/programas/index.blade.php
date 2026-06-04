@extends('layouts.dashboard')

@section('title', 'Programas')

@section('styles')
@vite('resources/css/programas.css')
@endsection

@section('content')
<div>
    <div class="programas-header">
        <h1><i class="fas fa-chalkboard"></i> Programas</h1>
        <button id="btnNuevo" class="btn-nuevo">
            <i class="fas fa-plus"></i> Nuevo Programa
        </button>
    </div>

    @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="programas-table-container">
        <table class="programas-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Fecha Inicio</th>
                    <th>Fecha Fin</th>
                    <th>Estado</th>
                    <th style="text-align: center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($programas as $programa)
                <tr data-id="{{ $programa->id_programa }}">
                    <td>{{ $programa->id_programa }}</td>
                    <td><strong>{{ $programa->nombre }}</strong></td>
                    <td>{{ Str::limit($programa->descripcion, 50) }}</td>
                    <td>{{ $programa->fecha_inicio ?? '-' }}</td>
                    <td>{{ $programa->fecha_fin ?? '-' }}</td>
                    <td>
                        <span class="estado-badge estado-{{ $programa->estado }}">
                            {{ ucfirst($programa->estado) }}
                        </span>
                    </td>
                    <td style="text-align: center">
                        <button class="btn-accion btn-editar" data-id="{{ $programa->id_programa }}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-accion btn-eliminar" data-id="{{ $programa->id_programa }}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Crear/Editar -->
<div id="modalPrograma" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <h2 id="modalTitulo"><i class="fas fa-plus-circle"></i> Nuevo Programa</h2>
            <button id="cerrarModal" class="modal-close">&times;</button>
        </div>
        <form id="formPrograma">
            @csrf
            <input type="hidden" id="programa_id" name="programa_id">
            
            <div class="form-group">
                <label>Nombre *</label>
                <input type="text" id="nombre" name="nombre" required>
            </div>
            
            <div class="form-group">
                <label>Descripción</label>
                <textarea id="descripcion" name="descripcion" rows="3"></textarea>
            </div>
            
            <div class="form-group">
                <label>Fecha Inicio</label>
                <input type="date" id="fecha_inicio" name="fecha_inicio">
            </div>
            
            <div class="form-group">
                <label>Fecha Fin</label>
                <input type="date" id="fecha_fin" name="fecha_fin">
            </div>
            
            <div class="form-group">
                <label>Estado *</label>
                <select id="estado" name="estado" required>
                    <option value="activo">Activo</option>
                    <option value="inactivo">Inactivo</option>
                    <option value="finalizado">Finalizado</option>
                </select>
            </div>
            
            <div class="modal-buttons">
                <button type="button" id="cancelarModal" class="btn-cancelar">Cancelar</button>
                <button type="button" id="btnGuardarPrograma" class="btn-guardar">Guardar</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Eliminar -->
<div id="modalEliminar" class="modal-overlay">
    <div class="modal-container modal-eliminar">
        <i class="fas fa-exclamation-triangle"></i>
        <h3>¿Eliminar programa?</h3>
        <p>Esta acción no se puede deshacer</p>
        <input type="hidden" id="eliminar_id">
        <div class="modal-buttons" style="justify-content: center;">
            <button id="cancelarEliminar" class="btn-cancelar">Cancelar</button>
            <button id="confirmarEliminar" class="btn-eliminar-confirmar">Eliminar</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@vite('resources/js/programas.js')
@endsection