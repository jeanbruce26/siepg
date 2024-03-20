<?php

use App\Http\Controllers\ModuloAdministrador\AdmisionController;
use App\Http\Controllers\ModuloAdministrador\AdmitidoController;
use App\Http\Controllers\ModuloAdministrador\CanalPagoController;
use App\Http\Controllers\ModuloAdministrador\ConceptoPagoController;
use App\Http\Controllers\ModuloAdministrador\CorreoController;
use App\Http\Controllers\ModuloAdministrador\DashboardController;
use App\Http\Controllers\ModuloAdministrador\EstudianteController;
use App\Http\Controllers\ModuloAdministrador\ExpedienteController;
use App\Http\Controllers\ModuloAdministrador\InscripcionController;
use App\Http\Controllers\ModuloAdministrador\InscripcionPagoController;
use App\Http\Controllers\ModuloAdministrador\PagoController;
use App\Http\Controllers\ModuloAdministrador\PlanController;
use App\Http\Controllers\ModuloAdministrador\ProgramaController;
use App\Http\Controllers\ModuloAdministrador\SedeController;
use App\Http\Controllers\ModuloAdministrador\TipoSeguimientoController;
use App\Http\Controllers\ModuloAdministrador\TrabajadorController;
use App\Http\Controllers\ModuloAdministrador\UsuarioTrabajadorController;
use App\Models\Inscripcion;
use App\Models\Persona;
use App\Models\UsuarioEstudiante;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

//Vista del Dashboard. El inicio la parte administrativa del sistema
Route::get('/', [DashboardController::class, 'dashboard'])->middleware(['auth.usuario', 'verificar.usuario.administrador'])->name('administrador.dashboard');

//Gestion de Usuarios
//Ruta para ir a la vista de Usuario en la Gestion de Usuarios
Route::get('/perfil', [DashboardController::class, 'perfil'])->middleware(['auth.usuario', 'verificar.usuario.administrador'])->name('administrador.perfil');
//Ruta para ir a la vista de Usuario en la Gestion de Usuarios
Route::get('/usuario', [UsuarioTrabajadorController::class, 'index'])->middleware(['auth.usuario', 'verificar.usuario.administrador'])->name('administrador.usuario');
//Ruta para ir a la vista de Trabajador en la Gestion de Usuarios
Route::get('/trabajador', [TrabajadorController::class, 'index'])->middleware(['auth.usuario', 'verificar.usuario.administrador'])->name('administrador.trabajador');

//Estudiantes
//Ruta para ir a la vista de Estudiantes
Route::get('/estudiante', [EstudianteController::class, 'index'])->middleware(['auth.usuario', 'verificar.usuario.administrador'])->name('administrador.estudiante'); // No hay controlador

//Gestion Admision
//Ruta para ir a la vista de Admision en la Gestion Admision
Route::get('/admision', [AdmisionController::class, 'index'])->middleware(['auth.usuario', 'verificar.usuario.administrador'])->name('administrador.admision');
//Ruta para ir a la vista de Inscripción en la Gestion Admision
Route::get('/inscripcion', [InscripcionController::class, 'index'])->middleware(['auth.usuario', 'verificar.usuario.administrador'])->name('administrador.inscripcion');
//Inscripcion Pago en la Gestion Admision
Route::get('/inscripcion-pago', [InscripcionPagoController::class, 'index'])->middleware(['auth.usuario', 'verificar.usuario.administrador'])->name('administrador.inscripcion-pago');
//Ruta para Admitidos en la Gestion Admision
Route::get('/admitidos', [AdmitidoController::class, 'index'])->middleware(['auth.usuario', 'verificar.usuario.administrador'])->name('administrador.admitidos');
//Ruta para la Gestionde Links de WhatsApp en la Gestion Admision
Route::get('/links-whatsapp', [AdmisionController::class, 'links_whatsapp'])->middleware(['auth.usuario', 'verificar.usuario.administrador'])->name('administrador.links-whatsapp');

//Gestion de Pagos
//Ruta para ir a la vista de Canal de Pago en la Gestion de Pagos
Route::get('/canal-pago', [CanalPagoController::class, 'index'])->middleware(['auth.usuario', 'verificar.usuario.administrador'])->name('administrador.canal-pago'); // No hay controlador
//Ruta para ir a la vista de Concepto de Pago en la Gestion de Pagos
Route::get('/concepto-pago', [ConceptoPagoController::class, 'index'])->middleware(['auth.usuario', 'verificar.usuario.administrador'])->name('administrador.concepto-pago'); // No hay controlador
//Ruta para ir a la vista de Pago en la Gestion de Pagos
Route::get('/pago', [PagoController::class, 'index'])->middleware(['auth.usuario', 'verificar.usuario.administrador'])->name('administrador.pago');

//Configuracion
//Ruta para ir a la vista de Programa en Configuracion
Route::get('/programa', [ProgramaController::class, 'index'])->middleware(['auth.usuario', 'verificar.usuario.administrador'])->name('administrador.programa');
//Ruta para ir a la vista de Gestion de Plan y Proceso de Programa en Configuracion | Esta ruta de la vista está dentro de la vista de programa
Route::get('/programa/{id}/gestion-plan-proceso', [ProgramaController::class, 'plan_proceso'])->middleware(['auth.usuario', 'verificar.usuario.administrador'])->name('administrador.programa.gestion-plan-proceso');
//Ruta para ir a la vista de Plan en Configuracion
Route::get('/plan', [PlanController::class, 'index'])->middleware(['auth.usuario', 'verificar.usuario.administrador'])->name('administrador.plan');
//Ruta para ir a la vista de Sede en Configuracion
Route::get('/sede', [SedeController::class, 'index'])->middleware(['auth.usuario', 'verificar.usuario.administrador'])->name('administrador.sede');
//Ruta para ir a la vista de Expediente en Configuracion
Route::get('/expediente', [ExpedienteController::class, 'index'])->middleware(['auth.usuario', 'verificar.usuario.administrador'])->name('administrador.expediente');
//Ruta para ir a la vista de Gestion de Admision de Expediente en Configuracion | Esta ruta de la vista está dentro de la vista de expediente
Route::get('/expediente/{id}/gestion-admision', [ExpedienteController::class, 'admision'])->middleware(['auth.usuario', 'verificar.usuario.administrador'])->name('administrador.expediente.gestion-admision');
//Ruta para ir a la vista de Gestion de Vistas para Evaluación de Expediente en Configuracion | Esta ruta de la vista está dentro de la vista de expediente
Route::get('/expediente/{id}/gestion-vistas-evaluacion', [ExpedienteController::class, 'evaluacion'])->middleware(['auth.usuario', 'verificar.usuario.administrador'])->name('administrador.expediente.gestion-vistas-evaluacion');
//Ruta para ir a la vista de Gestion de Tipo de Seguimiento de Expediente en Configuracion | Esta ruta de la vista está dentro de la vista de expediente
Route::get('/expediente/{id}/gestion-tipo-seguimiento', [ExpedienteController::class, 'seguimiento'])->middleware(['auth.usuario', 'verificar.usuario.administrador'])->name('administrador.expediente.gestion-tipo-seguimiento');
//Ruta para ir a la vista de Tipo de Seguimiento en Configuracion
Route::get('/tipo-seguimiento', [TipoSeguimientoController::class, 'index'])->middleware(['auth.usuario', 'verificar.usuario.administrador'])->name('administrador.tipo-seguimiento');

// Ruta para generar fichas de inscripcion de quienes no se les genero y enviarlas por correo
Route::get('/generar-fichas-inscripcion', [InscripcionController::class, 'generarFichasInscripcion'])->middleware(['auth.usuario', 'verificar.usuario.administrador'])->name('administrador.generar-fichas-inscripcion');

// Ruta para el modulo de gestion de correos
Route::get('/gestion-correo', [CorreoController::class, 'index'])->middleware(['auth.usuario', 'verificar.usuario.administrador'])->name('administrador.gestion-correo');
Route::get('/gestion-correo/crear', [CorreoController::class, 'create'])->middleware(['auth.usuario', 'verificar.usuario.administrador'])->name('administrador.gestion-correo.crear');
Route::post('gestion-correo/upload', [CorreoController::class, 'upload'])->middleware(['auth.usuario', 'verificar.usuario.administrador'])->name('ckeditor-upload-file');

// cambiar todos los correos de los usuarios a minusculas
Route::get('/cambiar-correos', function () {
    $usuarios = UsuarioEstudiante::all();
    foreach ($usuarios as $usuario) {
        $persona = Persona::where('id_persona', $usuario->id_persona)->first();
        $usuario->usuario_estudiante = mb_strtolower($persona->correo, 'UTF-8');
        $usuario->save();
    }
    return response()->json([
        'message' => 'Correos cambiados'
    ]);
})->middleware(['auth.usuario', 'verificar.usuario.administrador'])->name('administrador.cambiar-correos');

// verificar si hay expedientes pendientes por verificar
Route::get('/verificar-expedientes-pendientes', function () {
    $inscripciones = Inscripcion::all();
    foreach ($inscripciones as $inscripcion) {
        // verificar si tiene expedientes pendientes por verificar, si tienes expedientes pendientes por verificar, el estado es 0
        // si el estado es 1, significa que todos los expedientes han sido verificados y si el estado es 2, significa que hay expedientes observados
        $estado = verEstadoExpediente($inscripcion->id_inscripcion);

        // cambiar el estado de la verificacion de expedientes
        $inscripcion->verificar_expedientes = $estado;
        $inscripcion->save();
    }
    return response()->json([
        'message' => 'Expedientes verificados'
    ]);
})->middleware(['auth.usuario', 'verificar.usuario.administrador'])->name('administrador.verificar-expedientes-pendientes');

// verificar si la persona tiene usuario estudiante creado y si tiene modificar su contraseña a su numero de documento
Route::get('/verificar-usuario-estudiante', function () {
    $personas = Persona::all();
    foreach ($personas as $persona) {
        $usuario = UsuarioEstudiante::where('id_persona', $persona->id_persona)->first();
        if (!$usuario) {
            $usuario = new UsuarioEstudiante();
            $usuario->usuario_estudiante = mb_strtolower($persona->correo, 'UTF-8');
            $usuario->usuario_estudiante_password = Hash::make($persona->numero_documento);
            $usuario->usuario_estudiante_creacion = date('Y-m-d H:i:s');
            $usuario->usuario_estudiante_estado = 1;
            $usuario->id_persona = $persona->id_persona;
            $usuario->usuario_estudiante_perfil_url = null;
            $usuario->save();
        }
    }
    return response()->json([
        'message' => 'Usuarios estudiante verificados'
    ]);
})->middleware(['auth.usuario', 'verificar.usuario.administrador'])->name('administrador.verificar-usuario-estudiante');

//
