<?php

namespace App\Http\Controllers\ModuloPlataforma;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PlataformaController extends Controller
{
    public function login()
    {
        return view('modulo-plataforma.auth.login');
    }

    public function inicio()
    {
        return view('modulo-plataforma.inicio.index');
    }

    public function perfil()
    {
        return view('modulo-plataforma.perfil.index');
    }
}
