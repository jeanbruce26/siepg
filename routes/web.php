<?php

use App\Http\Controllers\ModuloAdministrador\DashboardController;
use App\Http\Controllers\ModuloInscripcion\InscripcionController;
use Illuminate\Support\Facades\Route;

// Routa para o dashboard do administrador y otros roles
Route::get('/login', [DashboardController::class, 'auth'])->middleware(['auth.usuario.redirect.sesion'])->name('login');
// Ruta para ir a la vista de registro de alumnos
Route::get('/posgrado/registro', [InscripcionController::class, 'registro_alumnos'])->name('posgrado.registro');
// Ruta para ir a la vista de gracias al final del registro de alumnos
Route::get('/posgrado/{id}/gracias', [InscripcionController::class, 'gracias_registro'])->name('posgrado.gracias');
// Ruta para para enviar email de las credenciales
Route::get('/posgrado/{id}/credenciales', [InscripcionController::class, 'credenciales_email'])->name('posgrado.credenciales-email');

//
