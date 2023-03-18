<?php

use App\Http\Controllers\ModuloAdministrador\DashboardController;
use Illuminate\Support\Facades\Route;

// Routa para o dashboard do administrador y otros roles
Route::get('/login', [DashboardController::class, 'auth'])->middleware(['auth.usuario.redirect.sesion'])->name('login');
