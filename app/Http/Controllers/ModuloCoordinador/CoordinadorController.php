<?php

namespace App\Http\Controllers\ModuloCoordinador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CoordinadorController extends Controller
{
    public function inicio()
    {
        return view('modulo-coordinador.inicio.index');
    }

    public function programas($id)
    {
        $id_modalidad = $id;
        return view('modulo-coordinador.inicio.programas', [
            'id_modalidad' => $id_modalidad
        ]);
    }

    public function inscripciones($id , $id_admision)
    {
        $id_programa = $id;
        $id_admision = $id_admision;
        return view('modulo-coordinador.inicio.inscripciones', [
            'id_programa' => $id_programa,
            'id_admision' => $id_admision
        ]);
    }

    public function perfil()
    {
        $id_tipo_trabajador = 2;
        return view('modulo-coordinador.perfil.index', [
            'id_tipo_trabajador' => $id_tipo_trabajador
        ]);
    }
}
