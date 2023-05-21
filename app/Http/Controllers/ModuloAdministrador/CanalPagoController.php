<?php

namespace App\Http\Controllers\ModuloAdministrador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CanalPagoController extends Controller
{
    public function index()
    {
        return view('modulo-administrador.gestion-pagos.canal-pago.index');
    }
}
