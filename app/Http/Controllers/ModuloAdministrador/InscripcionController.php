<?php

namespace App\Http\Controllers\ModuloAdministrador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InscripcionController extends Controller
{
    public function index()
    {
        return view('modulo-administrador.inscripcion.inscripcion.index');
    }
}
