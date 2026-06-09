<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProgramaController;
use App\Http\Controllers\EscuelaController;
use App\Http\Controllers\ParticipanteController;
use App\Http\Controllers\InscripcionController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\DonacionController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\VentaController;


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

    // ========== INVENTARIO ==========
    Route::get('/inventario', [InventarioController::class, 'index'])->name('inventario.index');
    Route::post('/inventario', [InventarioController::class, 'store'])->name('inventario.store');
    Route::get('/inventario/{id}', [InventarioController::class, 'show'])->name('inventario.show');
    Route::put('/inventario/{id}', [InventarioController::class, 'update'])->name('inventario.update');
    Route::delete('/inventario/{id}', [InventarioController::class, 'destroy'])->name('inventario.destroy');

    // ========== DONACIONES ==========
    Route::get('/donaciones', [DonacionController::class, 'index'])->name('donaciones.index');
    Route::post('/donaciones', [DonacionController::class, 'store'])->name('donaciones.store');
    Route::get('/donaciones/{id}', [DonacionController::class, 'show'])->name('donaciones.show');
    Route::put('/donaciones/{id}', [DonacionController::class, 'update'])->name('donaciones.update');
    Route::delete('/donaciones/{id}', [DonacionController::class, 'destroy'])->name('donaciones.destroy');
    Route::get('/donaciones/{id}/pdf', [DonacionController::class, 'exportPdf'])->name('donaciones.pdf');
    Route::get('/donaciones/exportar/reporte', [DonacionController::class, 'exportReporte'])->name('donaciones.reporte');

    // ========== DONANTES ==========
    Route::post('/donantes', [DonacionController::class, 'storeDonante'])->name('donantes.store');

    //subir douemnto escanedo y sellado
    Route::post('/donaciones/{id}/subir-sellado', [DonacionController::class, 'subirDocumentoSellado'])->name('donaciones.subirSellado');

    // ========== SERVICIOS Y ACTIVIDADES ==========
    Route::get('/servicios', [ServicioController::class, 'index'])->name('servicios.index');
    Route::post('/servicios', [ServicioController::class, 'store'])->name('servicios.store');
    Route::get('/servicios/{id}', [ServicioController::class, 'show'])->name('servicios.show');
    Route::put('/servicios/{id}', [ServicioController::class, 'update'])->name('servicios.update');
    Route::delete('/servicios/{id}', [ServicioController::class, 'destroy'])->name('servicios.destroy');
    Route::get('/servicios/{id}/pdf', [ServicioController::class, 'exportPdf'])->name('servicios.pdf');
    Route::get('/servicios/exportar/reporte', [ServicioController::class, 'exportReporte'])->name('servicios.reporte');

    // ========== VENTAS DE BIENES ==========
    Route::get('/ventas', [VentaController::class, 'index'])->name('ventas.index');
    Route::post('/ventas', [VentaController::class, 'store'])->name('ventas.store');
    Route::get('/ventas/{id}', [VentaController::class, 'show'])->name('ventas.show');
    Route::put('/ventas/{id}', [VentaController::class, 'update'])->name('ventas.update');
    Route::delete('/ventas/{id}', [VentaController::class, 'destroy'])->name('ventas.destroy');
    Route::get('/ventas/{id}/pdf', [VentaController::class, 'exportPdf'])->name('ventas.pdf');
    Route::get('/ventas/exportar/reporte', [VentaController::class, 'exportReporte'])->name('ventas.reporte');
});
