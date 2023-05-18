<?php

use App\Http\Controllers\ModuloAdministrador\AdmisionController;
use App\Http\Controllers\ModuloAdministrador\AdmitidoController;
use App\Http\Controllers\ModuloAdministrador\CanalPagoController;
use App\Http\Controllers\ModuloAdministrador\ConceptoPagoController;
use App\Http\Controllers\ModuloAdministrador\DashboardController;
use App\Http\Controllers\ModuloAdministrador\ExpedienteController;
use App\Http\Controllers\ModuloAdministrador\InscripcionController;
use App\Http\Controllers\ModuloAdministrador\InscripcionPagoController;
use App\Http\Controllers\ModuloAdministrador\PagoController;
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

//Estudiantes
//Ruta para ir a la vista de Estudiantes
Route::get('/estudiante', [PersonaController::class, 'index'])->middleware(['auth.usuario'])->name('administrador.estudiante'); // No hay controlador

//Gestion Admision
//Ruta para ir a la vista de Admision en la Gestion Admision
Route::get('/admision', [AdmisionController::class, 'index'])->middleware(['auth.usuario'])->name('administrador.admision');
//Ruta para ir a la vista de Inscripción en la Gestion Admision
Route::get('/inscripcion', [InscripcionController::class, 'index'])->middleware(['auth.usuario'])->name('administrador.inscripcion');
//Inscripcion Pago en la Gestion Admision
Route::get('/inscripcion-pago', [InscripcionPagoController::class, 'index'])->middleware(['auth.usuario'])->name('administrador.inscripcion-pago');
//Ruta para Admitidos en la Gestion Admision
Route::get('/admitidos', [AdmitidoController::class, 'index'])->middleware(['auth.usuario'])->name('administrador.admitidos');

//Gestion de Pagos
//Ruta para ir a la vista de Canal de Pago en la Gestion de Pagos
Route::get('/canal-pago', [CanalPagoController ::class, 'index'])->middleware(['auth.usuario'])->name('administrador.canal-pago'); // No hay controlador
//Ruta para ir a la vista de Concepto de Pago en la Gestion de Pagos
Route::get('/concepto-pago', [ConceptoPagoController::class, 'index'])->middleware(['auth.usuario'])->name('administrador.concepto-pago'); // No hay controlador
//Ruta para ir a la vista de Pago en la Gestion de Pagos
Route::get('/pago', [PagoController::class, 'index'])->middleware(['auth.usuario'])->name('administrador.pago');

//Configuracion
//Ruta para ir a la vista de Programa en Configuracion
Route::get('/programa', [ProgramaController::class, 'index'])->middleware(['auth.usuario'])->name('administrador.programa');
//Ruta para ir a la vista de Plan en Configuracion
Route::get('/plan', [PlanController::class, 'index'])->middleware(['auth.usuario'])->name('administrador.plan');
//Ruta para ir a la vista de Sede en Configuracion
Route::get('/sede', [SedeController::class, 'index'])->middleware(['auth.usuario'])->name('administrador.sede');
//Ruta para ir a la vista de Expediente en Configuracion
Route::get('/expediente', [ExpedienteController::class, 'index'])->middleware(['auth.usuario'])->name('administrador.expediente'); // No hay controlador