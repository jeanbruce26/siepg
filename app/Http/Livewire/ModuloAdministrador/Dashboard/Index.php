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

    public $admisiones, $admision; // variable para almacenar las admisiones y filtrar
    public $filtro_proceso; // variable para filtrar por proceso de admision
    public $ingreso_total, $ingreso_inscripcion, $ingreso_constancia; // variables para los totales
    public $ingreso_por_dia_total, $ingreso_por_dia_inscripcion, $ingreso_por_dia_constancia; // Variables para las cantidades diarias
    public $programas_maestria, $programas_doctorado; // variables para almacenar los programas

    public function mount()
    {
        $this->admisiones = Admision::all();
        $admision = Admision::where('admision_estado', 1)->first();
        $this->filtro_proceso = $admision->id_admision;
        $this->admision = Admision::where('id_admision', $this->filtro_proceso)->first();

        $this->ingreso_total = Pago::where('pago_estado', 2)
            ->where('pago_verificacion', 2)
            ->sum('pago_monto');
        $this->ingreso_constancia = Pago::where('pago_estado', 2)
            ->where('pago_verificacion', 2)
            ->sum('pago_monto');
        $this->ingreso_inscripcion = Inscripcion::join('pago', 'pago.id_pago', '=', 'inscripcion.id_pago')
            ->join('programa_proceso', 'programa_proceso.id_programa_proceso', '=', 'inscripcion.id_programa_proceso')
            ->where('programa_proceso.id_admision', $this->filtro_proceso)
            ->where('inscripcion.retiro_inscripcion', 0)
            ->where('pago.pago_estado', 2)
            ->where('pago.pago_verificacion', 2)
            ->sum('pago.pago_monto');
        // $this->ingreso_inscripcion = Pago::where('pago_estado', 1)->sum('pago_monto');

        $this->ingreso_por_dia_total = Pago::whereDate('pago_fecha', Carbon::today())->sum('pago_monto');
        $this->ingreso_por_dia_constancia = Pago::where('id_concepto_pago', 2)->whereDate('pago_fecha', Carbon::today())->sum('pago_monto');
        $this->ingreso_por_dia_inscripcion = Pago::where('id_concepto_pago', 1)->whereDate('pago_fecha', Carbon::today())->sum('pago_monto');

        $this->programas_maestria = Inscripcion::join('programa_proceso', 'programa_proceso.id_programa_proceso', '=', 'inscripcion.id_programa_proceso')
            ->join('programa_plan', 'programa_plan.id_programa_plan', '=', 'programa_proceso.id_programa_plan')
            ->join('programa','programa_plan.id_programa','=','programa.id_programa')
            ->select('programa.subprograma', 'programa.mencion', 'programa.programa', Inscripcion::raw('count(inscripcion.id_programa_proceso) as cantidad'), Inscripcion::raw('sum(case when inscripcion.inscripcion_estado = 1 then 1 else 0 end) as verificados'))
            ->where('programa.programa_estado',1)
            ->where('programa.programa_tipo',1) // 1 = Maestria
            ->where('programa_proceso.id_admision', $this->filtro_proceso)
            ->where('inscripcion.retiro_inscripcion', 0)
            ->where('inscripcion.retiro_inscripcion', 0)
            ->groupBy('inscripcion.id_programa_proceso')
            ->orderBy(Inscripcion::raw('count(inscripcion.id_programa_proceso)'), 'desc')
            ->get();
        $this->programas_doctorado = Inscripcion::join('programa_proceso', 'programa_proceso.id_programa_proceso', '=', 'inscripcion.id_programa_proceso')
            ->join('programa_plan', 'programa_plan.id_programa_plan', '=', 'programa_proceso.id_programa_plan')
            ->join('programa','programa_plan.id_programa','=','programa.id_programa')
            ->select('programa.subprograma', 'programa.mencion', 'programa.programa', Inscripcion::raw('count(inscripcion.id_programa_proceso) as cantidad'), Inscripcion::raw('sum(case when inscripcion.inscripcion_estado = 1 then 1 else 0 end) as verificados'))
            ->where('programa.programa_estado',1)
            ->where('programa.programa_tipo',2) // 1 = Doctorado
            ->where('programa_proceso.id_admision', $this->filtro_proceso)
            ->where('inscripcion.retiro_inscripcion', 0)
            ->groupBy('inscripcion.id_programa_proceso')
            ->orderBy(Inscripcion::raw('count(inscripcion.id_programa_proceso)'), 'desc')
            ->get();
    }

    public function aplicar_filtro()
    {
        if($this->filtro_proceso == null || $this->filtro_proceso == "")
        {
            $this->mount();
        }
        else
        {
            $this->admision = Admision::where('id_admision', $this->filtro_proceso)->first();
            $this->ingreso_inscripcion = Inscripcion::join('pago', 'pago.id_pago', '=', 'inscripcion.id_pago')
                                        ->join('programa_proceso', 'programa_proceso.id_programa_proceso', '=', 'inscripcion.id_programa_proceso')
                                        ->where('programa_proceso.id_admision', $this->filtro_proceso)
                                        ->sum('pago.pago_monto');
            $this->programas_maestria = Inscripcion::join('programa_proceso', 'programa_proceso.id_programa_proceso', '=', 'inscripcion.id_programa_proceso')
                ->join('programa_plan', 'programa_plan.id_programa_plan', '=', 'programa_proceso.id_programa_plan')
                ->join('programa','programa_plan.id_programa','=','programa.id_programa')
                ->select('programa.subprograma', 'programa.mencion', 'programa.programa', Inscripcion::raw('count(inscripcion.id_programa_proceso) as cantidad'), Inscripcion::raw('sum(case when inscripcion.inscripcion_estado = 1 then 1 else 0 end as verificados'))
                ->where('programa.programa_estado',1)
                ->where('programa.programa_tipo',1) // 1 = Maestria
                ->where('programa_proceso.id_admision', $this->filtro_proceso)
                ->where('inscripcion.retiro_inscripcion', 0)
                ->groupBy('inscripcion.id_programa_proceso')
                ->orderBy(Inscripcion::raw('count(inscripcion.id_programa_proceso)'), 'DESC')
                ->get();
            $this->programas_doctorado = Inscripcion::join('programa_proceso', 'programa_proceso.id_programa_proceso', '=', 'inscripcion.id_programa_proceso')
                ->join('programa_plan', 'programa_plan.id_programa_plan', '=', 'programa_proceso.id_programa_plan')
                ->join('programa','programa_plan.id_programa','=','programa.id_programa')
                ->select('programa.subprograma', 'programa.mencion', 'programa.programa', Inscripcion::raw('count(inscripcion.id_programa_proceso) as cantidad'), Inscripcion::raw('sum(case when inscripcion.inscripcion_estado = 1 then 1 else 0 end) as verificados'))
                ->where('programa.programa_estado',1)
                ->where('programa.programa_tipo',2) // 1 = Doctorado
                ->where('programa_proceso.id_admision', $this->filtro_proceso)
                ->where('inscripcion.retiro_inscripcion', 0)
                ->groupBy('inscripcion.id_programa_proceso')
                ->orderBy(Inscripcion::raw('count(inscripcion.id_programa_proceso)'), 'DESC')
                ->get();
        }
    }

    public function resetear_filtro()
    {
        $this->mount();
    }

    public function render()
    {
        return view('livewire.modulo-administrador.dashboard.index');
    }
}
