<?php

use App\Http\Controllers\ModuloInscripcion\InscripcionController;
use Illuminate\Support\Facades\Route;


Route::get('/', [InscripcionController::class, 'auth'])->name('inscripcion.auth');
