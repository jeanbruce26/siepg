<?php

namespace App\Http\Controllers\ModuloAdministrador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ExpedienteController extends Controller
{
    public function index()
    {
        return view('modulo-administrador.configuracion.expediente.index');
    }

    public function admision($id)
    {
        $id_expediente = $id;
        return view('modulo-administrador.configuracion.expediente.gestion-admision', [
            'id_expediente' => $id_expediente
        ]);
    }

    public function evaluacion($id)
    {
        $id_expediente = $id;
        return view('modulo-administrador.configuracion.expediente.gestion-vistas-evaluacion', [
            'id_expediente' => $id_expediente
        ]);
    }
}
