<?php

use App\Http\Controllers\ModuloDocente\DocenteController;
use Illuminate\Support\Facades\Route;

// ruta para ir al inicio de la plataforma
Route::get('/', [DocenteController::class, 'inicio'])->middleware(['auth.usuario', 'verificar.usuario.docente'])->name('docente.inicio');

// ruta para ir a la pagina de perfil
Route::get('/perfil', [DocenteController::class, 'perfil'])->middleware(['auth.usuario', 'verificar.usuario.docente'])->name('docente.perfil');

// ruta para ir a la pagina de matriculados
Route::get('/{id_docente_curso}/matriculados', [DocenteController::class, 'matriculados'])->middleware(['auth.usuario', 'verificar.usuario.docente'])->name('docente.matriculados');

// ruta para generar el acta de evaluacion
Route::get('/acta-evaluacion/{id_docente_curso}/ficha', [DocenteController::class, 'acta_evaluacion'])->middleware(['auth.usuario', 'verificar.usuario.docente'])->name('docente.acta_evaluacion');
