<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProgramaController;
use App\Http\Controllers\EscuelaController;

// Redirigir raíz al login
Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Ruta protegida
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth.custom')->name('dashboard');

// Rutas de programas y escuelas
Route::middleware('auth.custom')->group(function () {
    // Programas
    Route::get('/programas', [ProgramaController::class, 'index'])->name('programas.index');
    Route::post('/programas', [ProgramaController::class, 'store'])->name('programas.store');
    Route::get('/programas/{id}', [ProgramaController::class, 'show'])->name('programas.show');
    Route::put('/programas/{id}', [ProgramaController::class, 'update'])->name('programas.update');
    Route::delete('/programas/{id}', [ProgramaController::class, 'destroy'])->name('programas.destroy');

    // Escuelas Beneficiarias
    Route::get('/escuelas', [EscuelaController::class, 'index'])->name('escuelas.index');
    Route::post('/escuelas', [EscuelaController::class, 'store'])->name('escuelas.store');
    Route::get('/escuelas/{id}', [EscuelaController::class, 'show'])->name('escuelas.show');
    Route::put('/escuelas/{id}', [EscuelaController::class, 'update'])->name('escuelas.update');
    Route::delete('/escuelas/{id}', [EscuelaController::class, 'destroy'])->name('escuelas.destroy');
});