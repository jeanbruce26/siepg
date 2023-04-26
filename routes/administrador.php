<?php

use App\Http\Controllers\ModuloAdministrador\AdmisionController;
use App\Http\Controllers\ModuloAdministrador\AdmitidoController;
use App\Http\Controllers\ModuloAdministrador\DashboardController;
use App\Http\Controllers\ModuloAdministrador\PlanController;
use App\Http\Controllers\ModuloAdministrador\ProgramaController;
use App\Http\Controllers\ModuloAdministrador\SedeController;
use App\Http\Controllers\ModuloAdministrador\TrabajadorController;
use App\Http\Controllers\ModuloAdministrador\UsuarioTrabajadorController;
use Illuminate\Support\Facades\Route;

//Vista del Dashboard. El inicio la parte administrativa del sistema
Route::get('/', [DashboardController::class, 'dashboard'])->middleware(['auth.usuario'])->name('administrador.dashboard');

//Gestion de Usuarios
//Ruta para ir a la vista de Usuario en la Gestion de Usuarios
Route::get('/perfil', [DashboardController::class, 'perfil'])->middleware(['auth.usuario'])->name('administrador.perfil');
//Ruta para ir a la vista de Usuario en la Gestion de Usuarios
Route::get('/usuario', [UsuarioTrabajadorController::class, 'index'])->middleware(['auth.usuario'])->name('administrador.usuario');
//Ruta para ir a la vista de Trabajador en la Gestion de Usuarios
Route::get('/trabajador', [TrabajadorController::class, 'index'])->middleware(['auth.usuario'])->name('administrador.trabajador');

//Gestion Curricular
//Ruta para ir a la vista de Sede en la Gestion Curricular
Route::get('/sede', [SedeController::class, 'index'])->middleware(['auth.usuario'])->name('administrador.sede');
//Ruta para ir a la vista de Plan en la Gestion Curricular
Route::get('/plan', [PlanController::class, 'index'])->middleware(['auth.usuario'])->name('administrador.plan');
//Ruta para ir a la vista de Programa en la Gestion Curricular
Route::get('/programa', [ProgramaController::class, 'index'])->middleware(['auth.usuario'])->name('administrador.programa');
//Ruta para ir a la vista de Admision en la Gestion Curricular
Route::get('/admision', [AdmisionController::class, 'index'])->middleware(['auth.usuario'])->name('administrador.admision');

//Ruta para Admitidos
Route::get('/admitido', [AdmitidoController::class, 'index'])->middleware(['auth.usuario'])->name('administrador.admitido');
