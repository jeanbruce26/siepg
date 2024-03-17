<?php

namespace App\Http\Controllers\ModuloAdministrador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CorreoController extends Controller
{
    public function index()
    {
        return view('modulo-administrador.gestion-correo.index');
    }

    public function create()
    {
        return view('modulo-administrador.gestion-correo.create');
    }

    public function upload(Request $request)
    {
        dd($request->all());

        $file = $request->file('upload');

        if ($request->hasFile('upload')) {
            // Crear directorios para guardar los archivos
            $base_path = 'Posgrado/';
            $folders = [
                'files',
                'media',
            ];
            // Asegurar que se creen los directorios con los permisos correctos
            $path = asignarPermisoFolders($base_path, $folders);

            // Nombre del archivo
            $filename = time() . uniqid() . '.' . $file->getClientOriginalExtension();

            // Guardar el archivo
            $file->move(public_path($path), $filename);

            //url
            $url = asset($path . $filename);

            return response()->json([
                'status' => 'success',
                'url' => $url,
            ]);
        }
    }
}
