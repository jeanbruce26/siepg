<?php

namespace App\Http\Controllers\ModuloAdministrador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InscripcionPagoController extends Controller
{
    public function index()
    {
        return view('modulo-administrador.gestion-admision.inscripcion-pago.index');
    }
}
