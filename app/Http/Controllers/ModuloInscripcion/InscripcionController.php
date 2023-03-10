<?php

namespace App\Http\Controllers\ModuloInscripcion;

use App\Http\Controllers\Controller;
use App\Models\Admision;
use Illuminate\Http\Request;

class InscripcionController extends Controller
{
    public function auth()
    {
        $admision = Admision::where('estado', 1)->first()->admision;
        $admision = ucwords(strtolower($admision));
        return view('modulo-inscripcion.auth', [
            'admision' => $admision
        ]);
    }
}
