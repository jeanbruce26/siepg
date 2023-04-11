<?php

use App\Http\Controllers\ModuloAdministrador\DashboardController;
use App\Http\Controllers\ModuloAdministrador\SedeController;
use App\Http\Controllers\ModuloAdministrador\TrabajadorController;
use App\Http\Controllers\ModuloAdministrador\UsuarioTrabajadorController;
use Illuminate\Support\Facades\Route;

//Vista del Dashboard. El inicio la parte administrativa del sistema
Route::get('/', [DashboardController::class, 'dashboard'])->middleware(['auth.usuario'])->name('administrador.dashboard');
//Gestion de Usuarios
//Ruta para ir a la vista de Usuario en la Gestion de Usuarios
Route::get('/usuario', [UsuarioTrabajadorController::class, 'index'])->name('administrador.usuario');
//Ruta para ir a la vista de Trabajador en la Gestion de Usuarios
Route::get('/trabajador', [TrabajadorController::class, 'index'])->name('administrador.trabajador');
//Gestion Curricular
//Ruta para ir a la vista de Sede en la Gestion Curricular
Route::get('/sede', [SedeController::class, 'index'])->name('administrador.sede');

