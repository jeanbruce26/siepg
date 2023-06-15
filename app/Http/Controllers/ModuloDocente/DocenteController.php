<?php

namespace App\Http\Controllers\ModuloDocente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DocenteController extends Controller
{
    public function inicio()
    {
        return view('modulo-docente.inicio.index');
    }

    public function perfil()
    {
        $id_tipo_trabajador = 1;
        return view('modulo-docente.perfil.index', [
            'id_tipo_trabajador' => $id_tipo_trabajador
        ]);
    }
}
