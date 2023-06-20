<?php

use App\Http\Controllers\ModuloPlataforma\PlataformaController;
use Illuminate\Support\Facades\Route;

// ruta para el login
Route::get('/login', [PlataformaController::class, 'login'])->middleware(['auth.plataforma.redirect.sesion'])->name('plataforma.login');

// ruta para ir al inicio de la plataforma
Route::get('/', [PlataformaController::class, 'inicio'])->middleware(['auth.plataforma'])->name('plataforma.inicio');

// ruta para ir al proceso admision de la plataforma
Route::get('/admision', [PlataformaController::class, 'admision'])->middleware(['auth.plataforma'])->name('plataforma.admision');

// ruta para ir al perfil del usuario
Route::get('/perfil', [PlataformaController::class, 'perfil'])->middleware(['auth.plataforma'])->name('plataforma.perfil');

// ruta para ir a los expedientes
Route::get('/expedientes', [PlataformaController::class, 'expediente'])->middleware(['auth.plataforma'])->name('plataforma.expediente');

// ruta para ir a los pagos de los estudiantes
Route::get('/pagos', [PlataformaController::class, 'pago'])->middleware(['auth.plataforma'])->name('plataforma.pago');

// ruta para ir a los pagos de los estudiantes
Route::get('/estado-cuenta', [PlataformaController::class, 'estado_cuenta'])->middleware(['auth.plataforma'])->name('plataforma.estado-cuenta');

// ruta para ir ver la constancia de ingreso
Route::get('/constancia-ingreso', [PlataformaController::class, 'constancia'])->middleware(['auth.plataforma'])->name('plataforma.constancia');

// ruta para ir ver las matriculas
Route::get('/matriculas', [PlataformaController::class, 'matriculas'])->middleware(['auth.plataforma'])->name('plataforma.matriculas');
