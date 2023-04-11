<?php

namespace App\Http\Controllers\ModuloAdministrador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SedeController extends Controller
{
    public function index()
    {
        return view('modulo-administrador.gestion-curricular.sede.index');
    }
}
