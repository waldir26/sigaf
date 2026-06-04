<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProgramaController;
use App\Http\Controllers\EscuelaController;
use App\Http\Controllers\ParticipanteController;
use App\Http\Controllers\InscripcionController;

// Redirigir raíz al login
Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Ruta protegida
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth.custom')->name('dashboard');

// Rutas de programas, escuelas, participantes e inscripciones
Route::middleware('auth.custom')->group(function () {
    // ========== PROGRAMAS ==========
    Route::get('/programas', [ProgramaController::class, 'index'])->name('programas.index');
    Route::post('/programas', [ProgramaController::class, 'store'])->name('programas.store');
    Route::get('/programas/{id}', [ProgramaController::class, 'show'])->name('programas.show');
    Route::put('/programas/{id}', [ProgramaController::class, 'update'])->name('programas.update');
    Route::delete('/programas/{id}', [ProgramaController::class, 'destroy'])->name('programas.destroy');

    // ========== ESCUELAS BENEFICIARIAS ==========
    Route::get('/escuelas', [EscuelaController::class, 'index'])->name('escuelas.index');
    Route::post('/escuelas', [EscuelaController::class, 'store'])->name('escuelas.store');
    Route::get('/escuelas/{id}', [EscuelaController::class, 'show'])->name('escuelas.show');
    Route::put('/escuelas/{id}', [EscuelaController::class, 'update'])->name('escuelas.update');
    Route::delete('/escuelas/{id}', [EscuelaController::class, 'destroy'])->name('escuelas.destroy');

    // ========== PARTICIPANTES ==========
    Route::get('/participantes', [ParticipanteController::class, 'index'])->name('participantes.index');
    Route::post('/participantes', [ParticipanteController::class, 'store'])->name('participantes.store');
    Route::get('/participantes/{id}', [ParticipanteController::class, 'show'])->name('participantes.show');
    Route::put('/participantes/{id}', [ParticipanteController::class, 'update'])->name('participantes.update');
    Route::delete('/participantes/{id}', [ParticipanteController::class, 'destroy'])->name('participantes.destroy');
    
    // ========== NUEVA INSCRIPCIÓN DESDE PARTICIPANTE ==========
    Route::post('/participantes/inscripcion', [ParticipanteController::class, 'addInscripcion'])->name('participantes.addInscripcion');

    // ========== INSCRIPCIONES ==========
    Route::get('/inscripciones', [InscripcionController::class, 'index'])->name('inscripciones.index');
    Route::post('/inscripciones', [InscripcionController::class, 'store'])->name('inscripciones.store');
    Route::get('/inscripciones/{id}', [InscripcionController::class, 'show'])->name('inscripciones.show');
    Route::put('/inscripciones/{id}', [InscripcionController::class, 'update'])->name('inscripciones.update');
    Route::delete('/inscripciones/{id}', [InscripcionController::class, 'destroy'])->name('inscripciones.destroy');
});