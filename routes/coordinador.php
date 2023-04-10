<?php

use App\Http\Controllers\ModuloCoordinador\CoordinadorController;
use Illuminate\Support\Facades\Route;

// ruta para ir al inicio de la plataforma
Route::get('/', [CoordinadorController::class, 'inicio'])->middleware(['auth.usuario', 'verificar.usuario.coordinador'])->name('coordinador.inicio');

// ruta para ir a la pagina de perfil
Route::get('/perfil', [CoordinadorController::class, 'perfil'])->middleware(['auth.usuario', 'verificar.usuario.coordinador'])->name('coordinador.perfil');

// ruta para ir a la pagina de los programas de la modalidad
Route::get('/{id}/programas', [CoordinadorController::class, 'programas'])->middleware(['auth.usuario', 'verificar.usuario.coordinador'])->name('coordinador.programas');

// ruta para ir a la pagina de las evaluaciones del programa de la modalidad
Route::get('/programas/{id}/proceso/{id_admision}/evaluaciones', [CoordinadorController::class, 'evaluaciones'])->middleware(['auth.usuario', 'verificar.usuario.coordinador'])->name('coordinador.evaluaciones');

// ruta para ir a la pagina de las inscripciones del programa de la modalidad
Route::get('/programas/{id}/proceso/{id_admision}/inscripciones', [CoordinadorController::class, 'inscripciones'])->middleware(['auth.usuario', 'verificar.usuario.coordinador'])->name('coordinador.inscripciones');

// ruta para ir a la pagina de evalaucion de expediente
Route::get('/programas/{id}/proceso/{id_admision}/evaluaciones/{id_evaluacion}/expediente', [CoordinadorController::class, 'evaluacion_expediente'])->middleware(['auth.usuario', 'verificar.usuario.coordinador'])->name('coordinador.evaluacion-expediente');

// ruta para ir a la pagina de evalaucion de investigacion
Route::get('/programas/{id}/proceso/{id_admision}/evaluaciones/{id_evaluacion}/investigacion', [CoordinadorController::class, 'evaluacion_investigacion'])->middleware(['auth.usuario', 'verificar.usuario.coordinador'])->name('coordinador.evaluacion-investigacion');

// ruta para ir a la pagina de evalaucion de entrevista
Route::get('/programas/{id}/proceso/{id_admision}/evaluaciones/{id_evaluacion}/entrevista', [CoordinadorController::class, 'evaluacion_entrevista'])->middleware(['auth.usuario', 'verificar.usuario.coordinador'])->name('coordinador.evaluacion-entrevista');
