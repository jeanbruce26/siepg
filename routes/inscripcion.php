<?php

use App\Http\Controllers\ModuloInscripcion\InscripcionController;
use Illuminate\Support\Facades\Route;

// ruta para la vista inicial de la inscripcion y login
Route::get('/', [InscripcionController::class, 'auth'])
    ->name('inscripcion.auth');
// ruta para empezar el registro de la inscripcion
Route::get('/registro', [InscripcionController::class, 'registro'])
    ->name('inscripcion.registro');
// ruta para guardar el registro de la inscripcion, generar ficha y enviar email
Route::get('/{id}/ficha-inscripcion', [InscripcionController::class, 'ficha_inscripcion_email'])
    ->name('inscripcion.pdf-email');
// ruta para ir a la vista de gracias
Route::get('/{id}/gracias', [InscripcionController::class, 'gracias'])
    ->name('inscripcion.gracias');
