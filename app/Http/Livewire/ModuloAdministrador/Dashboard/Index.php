<?php

namespace App\Http\Livewire\ModuloAdministrador\Dashboard;

use App\Models\Admision;
use App\Models\Inscripcion;
use App\Models\Pago;
use App\Models\ProgramaProceso;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Str;
use NumberFormatter;

class Index extends Component
{

    public function render()
    {

        $programas_maestria = Inscripcion::join('programa_proceso','inscripcion.id_programa_proceso','=','programa_proceso.id_programa_proceso')
                                ->join('mencion_plan','programa_proceso.id_mencion_plan','=','mencion_plan.id_mencion_plan')
                                ->join('mencion','mencion_plan.id_mencion','=','mencion.id_mencion')
                                ->join('subprograma','mencion.id_subprograma','=','subprograma.id_subprograma')
                                ->join('programa','subprograma.id_programa','=','programa.id_programa')
                                ->select('subprograma.subprograma', 'mencion.mencion', 'programa.programa', ProgramaProceso::raw('count(programa_proceso.id_mencion_plan) as cantidad'))
                                ->where('mencion.mencion_estado',1)
                                ->where('programa.id_programa',1) // 1 = Maestria
                                ->groupBy('programa_proceso.id_mencion_plan')
                                ->orderBy(ProgramaProceso::raw('count(programa_proceso.id_mencion_plan)'), 'DESC')
                                ->get();
        
        $programas_doctorado = Inscripcion::join('programa_proceso','inscripcion.id_programa_proceso','=','programa_proceso.id_programa_proceso')
                                ->join('mencion_plan','programa_proceso.id_mencion_plan','=','mencion_plan.id_mencion_plan')
                                ->join('mencion','mencion_plan.id_mencion','=','mencion.id_mencion')
                                ->join('subprograma','mencion.id_subprograma','=','subprograma.id_subprograma')
                                ->join('programa','subprograma.id_programa','=','programa.id_programa')
                                ->select('subprograma.subprograma', 'mencion.mencion', 'programa.programa', ProgramaProceso::raw('count(programa_proceso.id_mencion_plan) as cantidad'))
                                ->where('mencion.mencion_estado',1)
                                ->where('programa.id_programa',2) // 2 = Doctorado
                                ->groupBy('programa_proceso.id_mencion_plan')
                                ->orderBy(ProgramaProceso::raw('count(programa_proceso.id_mencion_plan)'), 'DESC')
                                ->get();

        $admision = Admision::where('admision_estado', 1)->first();

        $ingreso_total = Pago::sum('pago_monto');
        $ingreso_por_dia_total = Pago::whereDate('pago_fecha', Carbon::today())->sum('pago_monto');
        $ingreso_por_dia_inscripcion = Pago::where('pago_estado', 3)->whereDate('pago_fecha', Carbon::today())->sum('pago_monto');
        $ingreso_por_dia_constancia = Pago::where('pago_estado', 4)->whereDate('pago_fecha', Carbon::today())->sum('pago_monto');
        
        $ingreso_inscripcion = Pago::where('pago_estado', 3)->sum('pago_monto');
        $ingreso_constancia = Pago::where('pago_estado', 4)->sum('pago_monto');

        return view('livewire.modulo-administrador.dashboard.index', [
            'programas_maestria' => $programas_maestria,
            'programas_doctorado' => $programas_doctorado,
            'ingreso_total' => $ingreso_total,
            'ingreso_inscripcion' => $ingreso_inscripcion, 
            'ingreso_constancia' => $ingreso_constancia,
            'ingreso_por_dia_total' => $ingreso_por_dia_total,
            'ingreso_por_dia_inscripcion' => $ingreso_por_dia_inscripcion,
            'ingreso_por_dia_constancia' => $ingreso_por_dia_constancia,
            'admision' => $admision
        ]);
    }
}
