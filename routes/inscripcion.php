<?php

use App\Http\Controllers\ModuloInscripcion\InscripcionController;
use Illuminate\Support\Facades\Route;

Route::get('/', [InscripcionController::class, 'auth'])->middleware('auth.inscripcion.redirect.sesion')->name('inscripcion.auth');
Route::get('/registro', [InscripcionController::class, 'registro'])->middleware('auth.inscripcion')->name('inscripcion.registro');
Route::get('/{id}/ficha-inscripcion', [InscripcionController::class, 'ficha_inscripcion_email'])->name('inscripcion.pdf-email');
Route::get('/{id}/gracias', [InscripcionController::class, 'gracias'])->name('inscripcion.gracias');
Route::post('/logout', [InscripcionController::class, 'logout'])->name('inscripcion.logout');
