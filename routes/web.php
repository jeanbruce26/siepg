<?php

use App\Http\Controllers\ModuloAdministrador\DashboardController;
use App\Http\Controllers\ModuloInscripcion\InscripcionController;
use Illuminate\Support\Facades\Route;

// Routa para o dashboard do administrador y otros roles
Route::get('/login', [DashboardController::class, 'auth'])->middleware(['auth.usuario.redirect.sesion'])->name('login');
// ruta para ir a la vista de registro de alumnos
Route::get('/posgrado/registro', [InscripcionController::class, 'registro_alumnos'])->name('posgrado.registro');
// ruta para ir a la vista de gracias al final del registro de alumnos
Route::get('/posgrado/{id}/gracias', [InscripcionController::class, 'gracias_registro'])->name('posgrado.gracias');