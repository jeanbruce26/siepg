<?php

namespace App\Http\Livewire\ModuloCoordinador\ReportePagos;

use App\Models\Ciclo;
use App\Models\ProgramaProceso;
use App\Models\ProgramaProcesoGrupo;
use Livewire\Component;

class ReportePrograma extends Component
{
    public $id_programa_proceso;

    public function render()
    {
        $programa_proceso = ProgramaProceso::join('programa_plan', 'programa_plan.id_programa_plan', '=', 'programa_proceso.id_programa_plan')
            ->join('programa', 'programa.id_programa', '=', 'programa_plan.id_programa')
            ->join('modalidad', 'modalidad.id_modalidad', '=', 'programa.id_modalidad')
            ->where('programa_proceso.id_programa_proceso', $this->id_programa_proceso)
            ->first();
        $grupos = ProgramaProcesoGrupo::where('id_programa_proceso', $this->id_programa_proceso)->get();
        $ciclos = Ciclo::where(function ($query) use ($programa_proceso){
                $query->where('ciclo_programa', 0)
                    ->orWhere('ciclo_programa', $programa_proceso->programa_tipo);
            })
            ->get();

        return view('livewire.modulo-coordinador.reporte-pagos.reporte-programa', [
            'programa_proceso' => $programa_proceso,
            'grupos' => $grupos,
            'ciclos' => $ciclos
        ]);
    }
}
