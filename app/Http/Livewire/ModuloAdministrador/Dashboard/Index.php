<?php

namespace App\Http\Livewire\ModuloAdministrador\Dashboard;

use App\Models\Inscripcion;
use App\Models\MencionPlan;
use App\Models\Pago;
use Livewire\Component;

class Index extends Component
{
    
    public function render()
    {

        $programas_maestria = Inscripcion::join('programa_proceso','inscripcion.id_programa_proceso','=','programa_proceso.id_programa_proceso')
                                ->join('mencion_plan','programa_proceso.id_mencion_plan','=','mencion_plan.id_mencion_plan')
                                ->join('mencion','mencion_plan.id_mencion','=','mencion.id_mencion')
                                ->join('subprograma','mencion.id_subprograma','=','subprograma.id_subprograma')
                                ->join('programa','subprograma.id_programa','=','programa.id_programa')
                                ->select('subprograma.subprograma', 'mencion.mencion', 'programa.programa', MencionPlan::raw('count(mencion_plan.id_mencion) as cantidad_mencion'))
                                ->where('mencion.mencion_estado',1)
                                ->where('programa.id_programa',1) // 1 = Maestria
                                ->groupBy('mencion_plan.id_mencion')
                                ->orderBy(MencionPlan::raw('count(mencion_plan.id_mencion)'), 'DESC')
                                ->get();

        // $programas_maestria = Inscripcion::join('mencion','inscripcion.id_mencion','=','mencion.id_mencion')
        //                         ->join('subprograma','mencion.id_subprograma','=','subprograma.id_subprograma')
        //                         ->join('programa','subprograma.id_programa','=','programa.id_programa')
        //                         ->select('subprograma.subprograma', 'mencion.mencion', 'programa.descripcion_programa', Inscripcion::raw('count(inscripcion.id_mencion) as cantidad_mencion'))
        //                         ->where('mencion.mencion_estado',1)
        //                         ->where('programa.id_programa',1) // 1 = Maestria
        //                         ->groupBy('inscripcion.id_mencion')
        //                         ->orderBy(Inscripcion::raw('count(inscripcion.id_mencion)'), 'DESC')
        //                         ->get();
        
        // $programas_doctorado = Inscripcion::join('mencion','inscripcion.id_mencion','=','mencion.id_mencion')
        //                         ->join('subprograma','mencion.id_subprograma','=','subprograma.id_subprograma')
        //                         ->join('programa','subprograma.id_programa','=','programa.id_programa')
        //                         ->select('subprograma.subprograma', 'mencion.mencion', 'programa.descripcion_programa', Inscripcion::raw('count(inscripcion.id_mencion) as cantidad_mencion'))
        //                         ->where('mencion.mencion_estado',1)
        //                         ->where('programa.id_programa',2) // 2 = Doctorado
        //                         ->groupBy('inscripcion.id_mencion')
        //                         ->orderBy(Inscripcion::raw('count(inscripcion.id_mencion)'), 'DESC')
        //                         ->get();
        
        
        $ingreso_total = Pago::sum('pago_monto');
        $ingreso_inscripcion = Pago::where('pago_estado', 3)->sum('pago_monto');
        $ingreso_constancia = Pago::where('pago_estado', 4)->sum('pago_monto');

        return view('livewire.modulo-administrador.dashboard.index', [
            'programas_maestria' => $programas_maestria,
            // 'programas_doctorado' => $programas_doctorado,
            'ingreso_total' => $ingreso_total,
            'ingreso_inscripcion' => $ingreso_inscripcion,
            'ingreso_constancia' => $ingreso_constancia
        ]);
    }
}
