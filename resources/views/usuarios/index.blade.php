@extends('layouts.dashboard')

@section('title', 'Usuarios')

@section('styles')
@vite('resources/css/usuarios.css')
@endsection

@section('content')
<div>
    <div class="usuarios-header">
        <h1><i class="fas fa-users-cog"></i> Usuarios</h1>
        <div class="search-box">
            <input type="text" id="searchInput" placeholder="Buscar por nombre, usuario, correo..." value="{{ request('search') }}">
            <button id="btnBuscar"><i class="fas fa-search"></i> Buscar</button>
            <button id="btnLimpiar" style="background: #6c7a8a;"><i class="fas fa-times"></i> Limpiar</button>
        </div>
    </div>

    <div style="margin-bottom: 15px; text-align: right;">
        <button id="btnNuevo" class="btn-nuevo">
            <i class="fas fa-plus"></i> Nuevo Usuario
        </button>
    </div>

    <div class="usuarios-table-container">
        <table class="usuarios-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Usuario</th>
                    <th>Correo</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th style="text-align: center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($usuarios as $usuario)
                <tr data-id="{{ $usuario->id_usuario }}">
                    <td>{{ $usuario->id_usuario }}</td>
                    <td><strong>{{ $usuario->nombre }} {{ $usuario->apellido }}</strong></td>
                    <td>{{ $usuario->usuario }}</td>
                    <td>{{ $usuario->correo }}</td>
                    <td>
                        <span class="rol-badge rol-{{ $usuario->rol }}">
                            {{ ucfirst($usuario->rol) }}
                        </span>
                    </td>
                    <td>
                        <span class="estado-badge estado-{{ $usuario->estado }}">
                            {{ ucfirst($usuario->estado) }}
                        </span>
                    </td>
                    <td style="text-align: center">
                        @if($usuario->id_usuario != session('usuario')->id_usuario)
                        <button class="btn-accion btn-editar" data-id="{{ $usuario->id_usuario }}" title="Editar">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-accion btn-eliminar" data-id="{{ $usuario->id_usuario }}" title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </button>
                        @else
                        <span style="color: #6c7a8a; font-size: 12px;">(Tú)</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 40px;">
                        <i class="fas fa-users-cog" style="font-size: 48px; color: #6c7a8a; margin-bottom: 10px; display: block;"></i>
                        No hay usuarios registrados
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination">
        {{ $usuarios->appends(['search' => request('search')])->links() }}
    </div>
</div>

<!-- Modal Usuario -->
<div id="modalUsuario" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <h2 id="modalTitulo"><i class="fas fa-plus-circle"></i> Nuevo Usuario</h2>
            <button id="cerrarModal" class="modal-close">&times;</button>
        </div>
        <form id="formUsuario">
            @csrf
            <input type="hidden" id="usuario_id" name="usuario_id">

            <!-- Foto de perfil -->
            <div class="form-group" style="text-align: center;">
                <div class="foto-container">
                    <div class="foto-preview" id="fotoPreview">
                        <img id="fotoPreviewImg" src="" style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; display: none;">
                        <div id="fotoPreviewDefault" style="width: 100px; height: 100px; border-radius: 50%; background: #1a2a4f; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-user" style="font-size: 40px; color: white;"></i>
                        </div>
                    </div>
                    <div class="foto-overlay" id="fotoOverlay">
                        <i class="fas fa-camera"></i>
                    </div>
                </div>
                <input type="file" id="fotoInput" name="foto" accept="image/jpeg,image/png,image/jpg" style="display: none;">
                <small style="font-size: 11px; color: #6c7a8a; display: block; margin-top: 5px;">JPG, PNG (max 1MB)</small>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Nombre *</label>
                    <input type="text" id="nombre" name="nombre" required>
                </div>
                <div class="form-group">
                    <label>Apellido *</label>
                    <input type="text" id="apellido" name="apellido" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Usuario *</label>
                    <input type="text" id="usuario" name="usuario" required>
                </div>
                <div class="form-group">
                    <label>Correo *</label>
                    <input type="email" id="correo" name="correo" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group" id="passwordGroup">
                    <label id="passwordLabel">Contraseña *</label>
                    <div class="password-wrapper">
                        <input type="password" id="contrasena" name="contrasena">
                        <i class="fas fa-eye toggle-password" data-target="contrasena"></i>
                    </div>
                </div>
                <div class="form-group">
                    <label>Rol *</label>
                    <select id="rol" name="rol" required>
                        <option value="empleado">Empleado</option>
                        <option value="admin">Administrador</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Estado *</label>
                <select id="estado" name="estado" required>
                    <option value="activo">Activo</option>
                    <option value="inactivo">Inactivo</option>
                </select>
            </div>

            <div class="modal-buttons">
                <button type="button" id="cancelarModal" class="btn-cancelar">Cancelar</button>
                <button type="button" id="btnGuardarUsuario" class="btn-guardar">Guardar</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Eliminar -->
<div id="modalEliminar" class="modal-overlay">
    <div class="modal-container modal-eliminar" style="width: 380px; text-align: center;">
        <i class="fas fa-exclamation-triangle" style="font-size: 48px; color: #f0ad4e; margin-bottom: 15px;"></i>
        <h3 style="margin-bottom: 10px;">¿Eliminar usuario?</h3>
        <p style="color: #6c7a8a; margin-bottom: 20px;">Esta acción no se puede deshacer</p>
        <div style="display: flex; gap: 10px; justify-content: center;">
            <button id="cancelarEliminar" class="btn-cancelar">Cancelar</button>
            <button id="confirmarEliminar" class="btn-eliminar-confirmar" style="background: #dc3545; color: white; padding: 8px 20px; border: none; border-radius: 6px; cursor: pointer;">Eliminar</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@vite('resources/js/usuarios.js')
@endsection