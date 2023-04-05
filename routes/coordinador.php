<?php

use App\Http\Controllers\ModuloCoordinador\CoordinadorController;
use Illuminate\Support\Facades\Route;

// ruta para ir al inicio de la plataforma
Route::get('/', [CoordinadorController::class, 'inicio'])->middleware(['auth.usuario', 'verificar.usuario.coordinador'])->name('coordinador.inicio');
// ruta para ir a la pagina de perfil
Route::get('/perfil', [CoordinadorController::class, 'perfil'])->middleware(['auth.usuario', 'verificar.usuario.coordinador'])->name('coordinador.perfil');
// // ruta para ir a la pagina de pagos
Route::get('/{id}/programas', [CoordinadorController::class, 'programas'])->middleware(['auth.usuario', 'verificar.usuario.coordinador'])->name('coordinador.programas');
