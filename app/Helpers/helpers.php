<?php

use App\Models\ExpedienteAdmision;
use App\Models\ExpedienteInscripcion;

function convertirFechaHora($fechaHora)
{
    // formato de fecha y hora: 12:00 pm - 12/12/2012
    return date('d/m/Y h:i a', strtotime($fechaHora));
}

function getIdTrasladoExterno()
{
    return 8; // id de traslado externo
}

function getIdConceptoPagoInscripcion()
{
    return [
        1, // id de concepto de pago de inscripcion
        8 // id de concepto de pago de inscripcion para traslado externo
    ];
}

function asignarPermisoFolders($base_path, $folders)
{
    $path = $base_path;
    foreach ($folders as $folder) {
        $path .= $folder . '/';
        // Asegurar que se creen los directorios con los permisos correctos
        $parent_directory = dirname($path);
        if (!file_exists($parent_directory)) {
            mkdir($parent_directory, 0777, true); // Establecer permisos en el directorio padre
        }
        if (!file_exists($path)) {
            mkdir($path, 0777, true); // 0777 establece todos los permisos para el directorio
            // Cambiar el modo de permisos después de crear los directorios
            chmod($path, 0777);
        }
    }
    return $path;
}

function registrarExpedientes($admision, $numero_documento, $expediente, $key, $inscripcion)
{
    $expediente_model = ExpedienteAdmision::where('expediente_admision_estado', 1)->where('id_expediente_admision', $key)->first();

    // Crear directorios para guardar los archivos
    $base_path = 'Posgrado/';
    $folders = [
        $admision,
        $numero_documento,
        'Expedientes'
    ];

    // Asegurar que se creen los directorios con los permisos correctos
    $path = asignarPermisoFolders($base_path, $folders);

    // Nombre del archivo
    $filename = $expediente_model->expediente->expediente_nombre_file . ".pdf";
    $nombre_db = $path . $filename;

    // Guardar el archivo
    $expediente->storeAs($path, $filename, 'files_publico');

    // Asignar todos los permisos al archivo
    chmod($nombre_db, 0777);

    // Registrar datos del expediente de inscripcion
    $expediente_inscripcion = new ExpedienteInscripcion();
    $expediente_inscripcion->expediente_inscripcion_url = $nombre_db;
    $expediente_inscripcion->expediente_inscripcion_estado = 1;
    $expediente_inscripcion->expediente_inscripcion_verificacion = 0;
    $expediente_inscripcion->expediente_inscripcion_fecha = now();
    $expediente_inscripcion->id_expediente_admision = $key;
    $expediente_inscripcion->id_inscripcion = $inscripcion->id_inscripcion;
    $expediente_inscripcion->save();
}

//
