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
}
