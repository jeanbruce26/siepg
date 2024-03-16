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
}