<?php

namespace App\Http\Controllers\ModuloInscripcion;

use App\Http\Controllers\Controller;
use App\Models\Admision;
use App\Models\Inscripcion;
use App\Models\InscripcionPago;
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
        $pago_id = auth('inscripcion')->user()->pago_id;
        $inscripcion_pago = InscripcionPago::where('pago_id', $pago_id)->first();
        $id_inscripcion = $inscripcion_pago->inscripcion_id;
        return view('modulo-inscripcion.registro', [
            'id_inscripcion' => $id_inscripcion
        ]);
    }

    public function gracias($id_inscripcion)
    {
        $inscripcion = Inscripcion::find($id_inscripcion);
        if (!$inscripcion) {
            abort(404);
        }
        return view('modulo-inscripcion.gracias', [
            'id_inscripcion' => $id_inscripcion
        ]);
    }

    public function logout()
    {
        auth('inscripcion')->logout();
        return redirect()->route('inscripcion.auth');
    }
}
