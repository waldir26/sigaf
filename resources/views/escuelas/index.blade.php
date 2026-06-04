@extends('layouts.dashboard')

@section('title', 'Escuelas Beneficiarias')

@section('styles')
@vite('resources/css/escuelas.css')
@endsection

@section('content')
<div>
    <div class="escuelas-header">
        <h1><i class="fas fa-school"></i> Escuelas Beneficiarias</h1>
        <button id="btnNuevo" class="btn-nuevo">
            <i class="fas fa-plus"></i> Nueva Escuela
        </button>
    </div>

    <div class="escuelas-table-container">
        <table class="escuelas-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre Escuela</th>
                    <th>Director</th>
                    <th>Municipio</th>
                    <th>Estudiantes</th>
                    <th>Programa</th>
                    <th style="text-align: center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($escuelas as $escuela)
                <tr data-id="{{ $escuela->id_escuela }}">
                    <td>{{ $escuela->id_escuela }}</td>
                    <td><strong>{{ $escuela->nombre_escuela }}</strong></td>
                    <td>{{ $escuela->director ?? '-' }}</td>
                    <td>{{ $escuela->municipio ?? '-' }}</td>
                    <td>{{ $escuela->cantidad_estudiantes ?? 0 }}</td>
                    <td>{{ $escuela->programa->nombre ?? 'Sin programa' }}</td>
                    <td style="text-align: center">
                        <button class="btn-accion btn-editar" data-id="{{ $escuela->id_escuela }}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-accion btn-eliminar" data-id="{{ $escuela->id_escuela }}">
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
<div id="modalEscuela" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <h2 id="modalTitulo"><i class="fas fa-plus-circle"></i> Nueva Escuela</h2>
            <button id="cerrarModal" class="modal-close">&times;</button>
        </div>
        <form id="formEscuela">
            @csrf
            <input type="hidden" id="escuela_id" name="escuela_id">
            
            <div class="form-group">
                <label>Nombre Escuela *</label>
                <input type="text" id="nombre_escuela" name="nombre_escuela" required>
            </div>
            
            <div class="form-group">
                <label>Director</label>
                <input type="text" id="director" name="director">
            </div>
            
            <div class="form-group">
                <label>Municipio</label>
                <input type="text" id="municipio" name="municipio">
            </div>
            
            <div class="form-group">
                <label>Cantidad Estudiantes</label>
                <input type="number" id="cantidad_estudiantes" name="cantidad_estudiantes" min="0">
            </div>
            
            <div class="form-group">
                <label>Programa</label>
                <select id="id_programa" name="id_programa">
                    <option value="">Seleccionar programa</option>
                    @foreach($programas as $programa)
                        <option value="{{ $programa->id_programa }}">{{ $programa->nombre }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="modal-buttons">
                <button type="button" id="cancelarModal" class="btn-cancelar">Cancelar</button>
                <button type="button" id="btnGuardarEscuela" class="btn-guardar">Guardar</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Eliminar -->
<div id="modalEliminar" class="modal-overlay">
    <div class="modal-container modal-eliminar">
        <i class="fas fa-exclamation-triangle"></i>
        <h3>¿Eliminar escuela?</h3>
        <p>Esta acción no se puede deshacer</p>
        <div class="modal-buttons" style="justify-content: center;">
            <button id="cancelarEliminar" class="btn-cancelar">Cancelar</button>
            <button id="confirmarEliminar" class="btn-eliminar-confirmar">Eliminar</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@vite('resources/js/escuelas.js')
@endsection