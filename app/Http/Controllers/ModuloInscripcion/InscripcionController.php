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
        $admision_year = Admision::where('estado', 1)->first()->admision_year;
        $admision = ucwords(strtolower($admision));
        return view('modulo-inscripcion.auth', [
            'admision' => $admision,
            'admision_year' => $admision_year
        ]);
    }

    public function registro()
    {
        return view('modulo-inscripcion.registro');
    }

    public function logout()
    {
        auth('inscripcion')->logout();
        return redirect()->route('inscripcion.auth');
    }
}
