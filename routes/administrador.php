<?php

use App\Http\Controllers\ModuloAdministrador\DashboardController;
use App\Http\Controllers\ModuloAdministrador\TrabajadorController;
use App\Http\Controllers\ModuloAdministrador\UsuarioTrabajadorController;
use Illuminate\Support\Facades\Route;

//Vista del Dashboard. El inicio la parte administrativa del sistema
Route::get('/', [DashboardController::class, 'dashboard'])->middleware(['auth.usuario'])->name('administrador.dashboard');
//Ruta para ir a la vista de Usuario en la Gestion de Usuarios
Route::get('/perfil', [DashboardController::class, 'perfil'])->middleware(['auth.usuario'])->name('administrador.perfil');
//Ruta para ir a la vista de Usuario en la Gestion de Usuarios
Route::get('/usuario', [UsuarioTrabajadorController::class, 'index'])->middleware(['auth.usuario'])->name('administrador.usuario');
//Ruta para ir a la vista de Trabajador en la Gestion de Usuarios
Route::get('/trabajador', [TrabajadorController::class, 'index'])->middleware(['auth.usuario'])->name('administrador.trabajador');

