<?php

namespace App\Http\Controllers\ModuloContable;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AreaContableController extends Controller
{
    public function inicio()
    {
        return view('modulo-area-contable.inicio.index');
    }

    public function pago()
    {
        return view('modulo-area-contable.pago.index');
    }

    public function perfil()
    {
        $id_tipo_trabajador = 3;
        return view('modulo-area-contable.perfil.index', [
            'id_tipo_trabajador' => $id_tipo_trabajador
        ]);
    }
}
