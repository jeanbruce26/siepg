<?php

namespace App\Http\Controllers\ModuloPlataforma;

use App\Http\Controllers\Controller;
use App\Models\Admitido;
use App\Models\Ciclo;
use App\Models\Matricula;
use App\Models\Persona;
use App\Models\Plan;
use App\Models\ProgramaProceso;
use Barryvdh\DomPDF\Facade\Pdf;
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

    public function admision()
    {
        return view('modulo-plataforma.admision.index');
    }

    public function perfil()
    {
        return view('modulo-plataforma.perfil.index');
    }

    public function expediente()
    {
        return view('modulo-plataforma.expedientes.index');
    }

    public function pago()
    {
        return view('modulo-plataforma.pagos.index');
    }

    public function estado_cuenta()
    {
        return view('modulo-plataforma.estado-cuenta.index');
    }

    public function constancia()
    {
        return view('modulo-plataforma.constancia-ingreso.index');
    }

    public function matriculas()
    {
        return view('modulo-plataforma.matriculas.index');
    }

    public function record_academico()
    {
        return view('modulo-plataforma.record-academico.index');
    }

    public function record_academico_ficha($id_admitido)
    {
        $persona = Persona::where('id_persona', auth('plataforma')->user()->id_persona)->first(); // persona del usuario logueado
        $admitido_logueado = Admitido::where('id_persona', $persona->id_persona)->orderBy('id_admitido', 'desc')->first(); // admitido del usuario logueado
        $admitido = Admitido::where('id_admitido', $id_admitido)->first(); // usuario logueado
        if ($admitido == null) {
            abort(403, 'No se encontro el registro del admitido');
        }
        if ($admitido_logueado->id_admitido != $admitido->id_admitido) {
            abort(403, 'No se encontro el registro del admitido');
        }
        $programa = ProgramaProceso::join('programa_plan', 'programa_plan.id_programa_plan', 'programa_proceso.id_programa_plan')
            ->join('programa', 'programa.id_programa', 'programa_plan.id_programa')
            ->where('programa_proceso.id_programa_proceso', $admitido->id_programa_proceso)
            ->first(); // programa del usuario logueado
        $plan = Plan::where('id_plan', $programa->id_plan)->first();
        $ciclos = Ciclo::where(function ($query) use ($programa){
                $query->where('ciclo_programa', 0)
                    ->orWhere('ciclo_programa', $programa->programa_tipo);
            })->orderBy('id_ciclo', 'asc')
            ->get(); // ciclos del usuario logueado
        $ultima_matricula = Matricula::where('id_admitido', $admitido->id_admitido)->orderBy('id_matricula', 'desc')->first(); // ultima matricula del usuario logueado
        if ( $programa->programa_tipo == 1 ){
            $color = '#ebf9ff';
        } else {
            $color = '#ffebeb';
        }

        $data = [
            'admitido' => $admitido,
            'programa' => $programa,
            'plan' => $plan,
            'ciclos' => $ciclos,
            'ultima_matricula' => $ultima_matricula,
            'color' => $color,
        ];

        $pdf = Pdf::loadView('modulo-plataforma.record-academico.record-academico', $data);

        return $pdf->download('record-academico.pdf');
    }
}
