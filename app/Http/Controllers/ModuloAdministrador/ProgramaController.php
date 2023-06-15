<?php

namespace App\Http\Controllers\ModuloAdministrador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProgramaController extends Controller
{
    public function index()
    {
        return view('modulo-administrador.configuracion.programa.index');
    }

    public function plan_proceso($id)
    {
        $id_programa = $id;
        return view('modulo-administrador.configuracion.programa.gestion-plan-proceso', [
            'id_programa' => $id_programa
        ]);
    }
}
