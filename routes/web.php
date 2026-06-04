<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProgramaController;

// Redirigir raíz al login
Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Ruta protegida
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth.custom')->name('dashboard');

// Rutas de programas (sin resource)
Route::middleware('auth.custom')->group(function () {
    Route::get('/programas', [ProgramaController::class, 'index'])->name('programas.index');
    Route::post('/programas', [ProgramaController::class, 'store'])->name('programas.store');
    Route::get('/programas/{id}', [ProgramaController::class, 'show'])->name('programas.show');
    Route::put('/programas/{id}', [ProgramaController::class, 'update'])->name('programas.update');
    Route::delete('/programas/{id}', [ProgramaController::class, 'destroy'])->name('programas.destroy');
});