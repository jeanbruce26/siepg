<?php

use App\Http\Controllers\ModuloAdministrador\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'dashboard'])->middleware(['auth.usuario'])->name('administrador.dashboard');
