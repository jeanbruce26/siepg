<?php

use Illuminate\Support\Facades\Route;

use App\Http\Livewire\ModuloEvaluacion\Auth\Login as LoginEvaluacion;
use App\Http\Livewire\ModuloEvaluacion\Inscripciones\Index as IndexInscripciones;
use App\Http\Livewire\ModuloEvaluacion\Inscripciones\Inscripciones as InscripcionesEvaluacion;

// Routa de login
Route::get('/login', LoginEvaluacion::class)
    ->name('evaluacion.login');

// Routa de incio de evaluaciones
Route::get('/home', IndexInscripciones::class)
    ->middleware('auth.usuario.evaluador')
    ->name('evaluacion.home');

// Routa de inscripciones
Route::get('/evaluacion/{id_programa_proceso}/lista', InscripcionesEvaluacion::class)
    ->middleware('auth.usuario.evaluador')
    ->name('evaluacion.inscripciones');

//
