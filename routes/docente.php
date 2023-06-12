<?php

use App\Http\Controllers\ModuloDocente\DocenteController;
use Illuminate\Support\Facades\Route;

// ruta para ir al inicio de la plataforma
Route::get('/', [DocenteController::class, 'inicio'])->middleware(['auth.usuario', 'verificar.usuario.docente'])->name('docente.inicio');

// ruta para ir a la pagina de perfil
Route::get('/perfil', [DocenteController::class, 'perfil'])->middleware(['auth.usuario', 'verificar.usuario.docente'])->name('docente.perfil');
