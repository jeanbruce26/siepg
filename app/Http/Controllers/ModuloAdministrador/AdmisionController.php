<?php

namespace App\Http\Controllers\ModuloAdministrador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdmisionController extends Controller
{
    public function index()
    {
        return view('modulo-administrador.gestion-admision.admision.index');
    }

    public function links_whatsapp()
    {
        return view('modulo-administrador.gestion-admision.links-whatsapp.index');
    }
}
