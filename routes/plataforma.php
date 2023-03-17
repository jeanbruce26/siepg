<?php

use App\Http\Controllers\ModuloPlataforma\PlataformaController;
use Illuminate\Support\Facades\Route;

// ruta para el login
Route::get('/login', [PlataformaController::class, 'login'])->middleware(['auth.plataforma.redirect.sesion'])->name('plataforma.login');
// ruta para ir al inicio de la plataforma
Route::get('/', [PlataformaController::class, 'inicio'])->middleware(['auth.plataforma'])->name('plataforma.inicio');
// ruta para ir al perfil del usuario
Route::get('/perfil', [PlataformaController::class, 'perfil'])->middleware(['auth.plataforma'])->name('plataforma.perfil');
// ruta para ir a los expedientes
Route::get('/expedientes', [PlataformaController::class, 'expediente'])->middleware(['auth.plataforma'])->name('plataforma.expediente');
