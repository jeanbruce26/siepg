<?php

use App\Http\Controllers\ModuloContable\AreaContableController;
use Illuminate\Support\Facades\Route;

// ruta para ir al inicio de la plataforma
Route::get('/', [AreaContableController::class, 'inicio'])->middleware(['auth.usuario', 'verificar.usuario.contable'])->name('contable.inicio');
// ruta para ir a la pagina de pagos
Route::get('/pagos', [AreaContableController::class, 'pago'])->middleware(['auth.usuario', 'verificar.usuario.contable'])->name('contable.pago');
