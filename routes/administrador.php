<?php

use App\Http\Controllers\ModuloAdministrador\DashboardController;
use App\Http\Controllers\ModuloAdministrador\TrabajadorController;
use App\Http\Controllers\ModuloAdministrador\UsuarioTrabajadorController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'dashboard'])->name('administrador.dashboard');
Route::get('/usuario', [UsuarioTrabajadorController::class, 'index'])->name('administrador.usuario');
Route::get('/trabajador', [TrabajadorController::class, 'index'])->name('administrador.trabajador');
